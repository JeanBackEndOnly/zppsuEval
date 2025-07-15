<?php
// Include templates and DB connection
include '../../templates/Uheader.php';
include '../../templates/HeaderNav.php';

$pdo = db_connect();
// Handle POST (if not separated)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['AddQuestions']) && $_POST['AddQuestions'] === 'true') {
        $title = trim($_POST['title']);
        $criteria_id = intval($_POST['criteria']);
        if ($title && $criteria_id) {
            $stmt = $pdo->prepare("INSERT INTO questionnaire (question_text, criteria_id) VALUES (?, ?)");
            $stmt->execute([$title, $criteria_id]);
        }
        header("Location: ".$_SERVER['PHP_SELF'] . '?AddQuestion=success');
        exit;
    }

    if (isset($_POST['DeleteQuestionnaire']) && isset($_POST['id'])) {
        $stmt = $pdo->prepare("DELETE FROM questionnaire WHERE id = ?");
        $stmt->execute([intval($_POST['id'])]);
        header("Location: ".$_SERVER['PHP_SELF'] . '?DeleteQuestion=success');
        exit;
    }

    
}
?>

<style>
    .EvalNav {
        background-color: #77070A !important;
        font-weight: 500;
    }
    .FacultyNav, .CurriculumeNav, .dashboardNav {
        background: linear-gradient(40deg , #77070b62,#77070b62, #77070A, #77070b62, #77070b62) !important;
    }
</style>

<div class="main w-100 h-100 d-flex flex-column">
    <?= getAdminHeader() ?>

    <div class="row w-100 p-0 m-0">
        <!-- Sidebar -->
        <div class="col-auto sideNav bg-linear h-100">
            <div class="sideContents" id="sideContents">
                <div class="profileBox w-100 d-flex flex-column justify-content-center align-items-center mt-2 mb-3">
                    <img src="../../assets/image/admin.png" alt="pfp" id="pfpOnTop">
                    <h5 class="text-white">ADMIN</h5>
                </div>
                <?= getAdminNav() ?>
            </div>
        </div>

        <!-- Content -->
        <div class="col content p-0 d-flex flex-column justify-content-start align-items-center fadeInAnimation">
            <!-- Title -->
            <div class="title m-0 col-md-11 col-11 d-flex justify-content-start mb-4 fadeInAnimation">
                <label class="text-black fw-bold fs-2 text-muted">QUESTIONNAIRES MANAGEMENT</label>
            </div>

            <!-- Add + Search -->
            <div class="col-md-11 col-11 d-flex justify-content-between align-items-center mb-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionnaireModal">
                    Add Questionnaire
                </button>

                <form class="d-flex" method="GET">
                    <input type="text" name="search" class="form-control me-2" placeholder="Search questionnaire...">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </form>
            </div>

            <!-- Table -->
            <div class="col-md-11 col-11 table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Question</th>
                            <th>Criteria</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $pdo = db_connect();

                        $query = "
                            SELECT
                                questionnaire.id,
                                questionnaire.question_text,
                                questionnaire.created_at,
                                criteria.name AS criteria_name
                            FROM questionnaire
                            LEFT JOIN criteria ON questionnaire.criteria_id = criteria.id
                            ORDER BY questionnaire.id DESC
                        ";
                        $rows  = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

                        $count = 1;
                        foreach ($rows as $row):
                        ?>
                        <tr>
                            <td><?= $count++ ?></td>
                            <td><?= htmlspecialchars($row['question_text']) ?></td>
                            <td><?= htmlspecialchars($row['criteria_name'] ?? '') ?></td> <!-- fixed -->
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                            <td>
                                <button
                                    class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal"
                                    data-id="<?= $row['id'] ?>">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <form method="POST" class="modal-content">
              <input type="hidden" name="DeleteQuestionnaire" value="true">
              <div class="modal-header">
                <h5 class="modal-title">Delete Questionnaire</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <p>Are you sure you want to delete this questionnaire?</p>
                <input type="hidden" name="id" id="delete-id">
              </div>
              <div class="modal-footer">
                <button class="btn btn-danger" type="submit">Delete</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Add Questionnaire Modal -->
        <div class="modal fade" id="addQuestionnaireModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <form method="POST">
              <input type="hidden" name="AddQuestions" value="true">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Add Questionnaire</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Criterion</label>
                    <select name="criteria" class="form-select" required>
                        <?php
                        $crit = $pdo->query("SELECT id, name FROM criteria ORDER BY name")->fetchAll();
                        foreach ($crit as $c) {
                            echo '<option value="'.$c['id'].'">'.htmlspecialchars($c['name']).'</option>';
                        }
                        ?>
                    </select>
                  </div>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-primary" type="submit">Add</button>
                  <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                </div>
              </div>
            </form>
          </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (AddQuestion) {
            console.log("Showing updateReq toast");
            Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Question Added successfully!.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: { popup: 'swal2-row-toast' }
            });
            removeUrlParams(['AddQuestion']);
        }else if (DeleteQuestion) {
            console.log("Showing updateReq toast");
            Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Question deleted successfully!.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: { popup: 'swal2-row-toast' }
            });
            removeUrlParams(['DeleteQuestion']);
        }
        function removeUrlParams(params) {
            const url = new URL(window.location);
            params.forEach(param => url.searchParams.delete(param));
            window.history.replaceState({}, document.title, url.toString());
        }
    });
</script>
<script>
document.getElementById('deleteModal').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    document.getElementById('delete-id').value = id;
});
</script>

<?php include '../../templates/Ufooter.php'; ?>
