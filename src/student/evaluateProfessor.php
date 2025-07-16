<?php
/* ==============================================================
   Evaluation Form with auto-updating grade, total_grade, and
   evaluator_group_summary tables using ON DUPLICATE KEY UPDATE
   ============================================================== */

include '../../templates/Uheader.php';
include '../../templates/HeaderNav.php';
$pdo = db_connect();

if (!isset($_GET['teacherID'])) {
    header('Location: dashboard.php');
    exit;
}
$teacherID = urldecode($_GET['teacherID']);

$profStmt = $pdo->prepare("SELECT p.*, d.department_name FROM professor p LEFT JOIN department d ON p.department_id = d.id WHERE p.teacherID = ?");
$profStmt->execute([$teacherID]);
$prof = $profStmt->fetch(PDO::FETCH_ASSOC) ?: die('Professor not found');

$criteria = $pdo->query("SELECT * FROM criteria ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
$qRows = $pdo->query("SELECT * FROM questionnaire ORDER BY criteria_id, id")->fetchAll(PDO::FETCH_ASSOC);
$questions = [];
foreach ($qRows as $row) {
    $questions[$row['criteria_id']][] = $row;
}
$scale = $pdo->query("SELECT * FROM grading_scale ORDER BY score_value DESC")->fetchAll(PDO::FETCH_ASSOC);

$subjectStmt = $pdo->prepare("SELECT s.id, s.subject_name FROM professor_subject ps JOIN subjects s ON ps.subject_id = s.id WHERE ps.professor_id = ?");
$subjectStmt->execute([$prof['id']]);
$subjects = $subjectStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_eval'])) {
    $termId = $pdo->query("SELECT id FROM school_year_semester WHERE status = 'open' LIMIT 1")->fetchColumn();
    if (!$termId) die('No active term.');

    $subjectId = $_POST['subject_id'] ?? null;
    if ($subjectId === "") $subjectId = null;
    $studentId = $student_info['user_id'];

    $dup = $pdo->prepare("SELECT 1 FROM grade WHERE professor_id = ? AND evaluator_id = ? AND evaluator_type = 'STUDENT' AND school_year_semester_id = ? AND subject_id <=> ? LIMIT 1");
    $dup->execute([$prof['id'], $studentId, $termId, $subjectId]);
    if ($dup->fetchColumn()) {
        header("Location: evaluateProfessor.php?teacherID=" . urlencode($teacherID) . "&already=1");
        exit;
    }

    $pdo->beginTransaction();
    $stmt = $pdo->prepare("INSERT INTO grade (professor_id, evaluator_id, evaluator_type, subject_id, questionnaire_id, school_year_semester_id, score) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($_POST['response'] as $qId => $score) {
        $stmt->execute([$prof['id'], $studentId, 'STUDENT', $subjectId, $qId, $termId, (int)$score]);
    }

    $sum = $pdo->prepare("INSERT INTO total_grade (professor_id, subject_id, school_year_semester_id, average_score, total_score, evaluation_count) SELECT :prof_id, :sub_id, :term_id, AVG(score), SUM(score), COUNT(*) FROM grade WHERE professor_id = :prof_id AND subject_id <=> :sub_id AND school_year_semester_id = :term_id GROUP BY professor_id, subject_id, school_year_semester_id ON DUPLICATE KEY UPDATE average_score = VALUES(average_score), total_score = VALUES(total_score), evaluation_count = VALUES(evaluation_count), updated_at = CURRENT_TIMESTAMP");
    $sum->execute([':prof_id' => $prof['id'], ':sub_id' => $subjectId, ':term_id' => $termId]);

    $weightStmt = $pdo->prepare("SELECT weight_percent FROM evaluator_group_weight WHERE evaluator_type = 'STUDENT' LIMIT 1");
    $weightStmt->execute();
    $weightPercent = $weightStmt->fetchColumn();

    if ($weightPercent !== false) {
        $summaryStmt = $pdo->prepare("INSERT INTO evaluator_group_summary (professor_id, subject_id, school_year_semester_id, evaluator_type, average_score, weight_percent, equivalent_point) SELECT :prof_id, :sub_id, :term_id, 'STUDENT', AVG(score), :weight, ROUND(AVG(score) * (:weight / 100), 2) FROM grade WHERE professor_id = :prof_id AND subject_id <=> :sub_id AND school_year_semester_id = :term_id AND evaluator_type = 'STUDENT' GROUP BY professor_id, subject_id, school_year_semester_id ON DUPLICATE KEY UPDATE average_score = VALUES(average_score), weight_percent = VALUES(weight_percent), equivalent_point = VALUES(equivalent_point), created_at = CURRENT_TIMESTAMP");
        $summaryStmt->execute([':prof_id' => $prof['id'], ':sub_id' => $subjectId, ':term_id' => $termId, ':weight' => $weightPercent]);
    }

    $pdo->commit();
    header("Location: evaluateProfessor.php?teacherID=" . urlencode($teacherID) . "&saved=1");
    exit;
}
?>

<style>
    .dashboardNav { background: var(--new); font-weight: 500; }
    .evaluationNav {
        background: linear-gradient(40deg, #77070b62, #77070b62, #77070A, #77070b62, #77070b62) !important;
    }
    .likert th, .likert td { padding: .45rem; vertical-align: middle; }
    .likert-desc { font-size: .8rem; }
</style>

<div class="main w-100 h-100 d-flex flex-column">
    <?= getHeader() ?>
    <div class="row w-100 m-0 p-0">
        <div class="col-auto sideNav bg-linear h-100">
            <div class="sideContents" id="sideContents">
                <div class="profileBox w-100 d-flex flex-column justify-content-center align-items-center mt-2 mb-3">
                    <?php if ($student_info["user_profile"] != '') { ?>
                        <img src="../../assets/image/<?= $student_info["user_profile"] ?>" id="pfpOnTop">
                    <?php } else { ?>
                        <img src="../../assets/image/Avatar.png" id="pfpOnTop">
                    <?php } ?>
                    <label class="fw-bold text-white"><?= $student_info["SchoolID"] ?></label>
                    <label class="text-white text-center fw-bold">
                        <?= $student_info["lname"] . ", " . $student_info["fname"] ?>
                    </label>
                </div>
                <?= getNav() ?>
            </div>
        </div>

        <div class="col content p-0">
            <div class="p-4">
                <h4 class="mb-3">Evaluation Form</h4>

                <form method="POST">
                    <input type="hidden" name="save_eval" value="1">

                    <!-- (optional) subject dropdown removed for brevity -->

                    <?php foreach ($criteria as $c): ?>
                        <h5 class="mb-1 w-100 text-start p-2 ps-3 rounded-2 bg-linear text-white">
                            <?= htmlspecialchars($c['name']) ?>
                        </h5>

                        <table class="table table-bordered likert mb-3">
                            <thead class="table-light text-center">
                                <tr>
                                    <th class="text-start" style="width:45%">Question</th>
                                    <?php foreach ($scale as $s): ?>
                                        <th>
                                            <div class="fw-bold"><?= htmlspecialchars($s['description']) ?></div>
                                            <span class="likert-desc">(<?= $s['score_value'] ?>)</span>
                                        </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($questions[$c['id']] ?? [] as $q): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($q['question_text']) ?></td>
                                        <?php foreach ($scale as $s): ?>
                                            <td class="text-center">
                                                <input type="radio"
                                                       name="response[<?= $q['id'] ?>]"
                                                       value="<?= $s['score_value'] ?>" required>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($questions[$c['id']])): ?>
                                    <tr>
                                        <td colspan="<?= count($scale) + 1 ?>" class="text-center text-muted">
                                            No questions defined for this criterion.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    <?php endforeach; ?>

                    <button class="btn btn-primary">Submit Evaluation</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ==============================================================
     SUCCESS / DUPLICATE TOASTS
     ============================================================== -->
<?php if (isset($_GET['saved'])): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Evaluation submitted!', showConfirmButton: false, timer: 3000 });
</script>
<?php elseif (isset($_GET['already'])): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: "You've already evaluated this teacher", showConfirmButton: false, timer: 3000 });
</script>
<?php endif ?>

<?php include '../../templates/Ufooter.php'; ?>

