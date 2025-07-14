<?php include '../../templates/Uheader.php'; 
$info = getUsersInfo();
$admin_info = $info['admin_info'];
$questions = $info['questions'];

$pdo = db_connect();

$evaluatorID = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : "null";
$subjectsID = isset($_GET["subjectsID"]) ? $_GET["subjectsID"] : "null";

$professor_id = isset($_GET['id']) ? $_GET['id'] : null;
if ($professor_id) {
    $query = "SELECT * FROM professor WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":id", $professor_id);
    $stmt->execute();
    
    $professor_data = $stmt->fetch(PDO::FETCH_ASSOC); 

    if ($professor_data) {
        $professor_name = $professor_data["Lname"]; 
    } else {
        die("No professor found with the given ID.");
    }
} else {
    die("Professor ID is required.");
}
?>

    <div class="header">
        <div class="logo" style="display: flex; height: 100%; align-items: center;">
            <!-- <img src="../../assets/image/zppsu-logo.png" alt="pfp" id="pfpOnTop" style="height: 50px; width: 50px; border-radius: 50%; margin-right: 10px;"> -->
              <h3 id="userTitle">ITE DEPARTMENT EVALUATION SYSTEM</h3>
        </div>
        <div class="otherButtons">
            <button type="submit" onclick="profileMenu()" id="buttonpfpmenu">
                <img src="../../assets/image/users.png"alt="pfp" id="pfpOnTop">
                <p>Anonymous<i class="fa-solid fa-caret-down"></i></p>
            </button>
            
            <div class="profileMenu" id="profileMenu" style="display: none;">
                <li id="borderBottom"><a href="settings.php"><p><i class="fa-solid fa-gear"></i>SETTINGS</p></a></li>
                <li><a href="../logout.php" id="l"><p><i class="fa-solid fa-right-from-bracket"></i>LOGOUT</p></a></li>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="sideNav">
            <div class="sideContents" id="sideContents">
                <div class="profileBox">
                <img src="../../assets/image/zppsu-logo.png" alt="pfp" id="pfpOnTop">
                    <h4>Since 1913</h4>
                    <p>Zamboanga Peninsula Region IX</p>
                </div>
                <div class="menuBox">
                    <ul>
                        <a href="dashboard.php" id="dashboard-a"><button id="buttonMen"><i class="fa-solid fa-house-user"></i>DASHBOARD</button></a>
                        <a href="teachers.php"><button id="buttonMen"><i class="fa-solid fa-users"></i>EVALUATION</button></a>
                    </ul>
                </div>
            </div>
        </div>
        <div class="contents">
            <div class="evaluationTeacher">
                <h2 id="profh">Professor Evaluation Form</h2>
                <h3>Evaluation Questions:</h3>
                <form action="../../auth/authentications.php?id=<?php echo $professor_id; ?>" method="post">
                    <input type="hidden" name="professor_id" value="<?= htmlspecialchars($professor_id) ?>">
                    <input type="hidden" name="evaluation" value="student">
                    <input type="hidden" name="evaluator_id" value="<?php echo $evaluatorID ?>">
                    <input type="hidden" name="subjectID" value="<?php echo $subjectsID ?>">
                    <label for="professor" id="labelProf">Evaluating Professor: </label>
                    <input type="text" id="professor" value="<?= htmlspecialchars($professor_name) ?>" readonly>

                    
                    <?php foreach ($questions as $question): ?>
                        <fieldset>
                            <legend><?= htmlspecialchars($question['question_text']) ?></legend>

                            <label>
                                <span>1</span>
                                <input name="question_<?= $question['id'] ?>" id="radioButton" value="1" type="radio" required>
                            </label>

                            <label>
                                <span>2</span>
                                <input name="question_<?= $question['id'] ?>" id="radioButton" value="2" type="radio" required>
                            </label>

                            <label>
                                <span>3</span>
                                <input name="question_<?= $question['id'] ?>" id="radioButton" value="3" type="radio" required>
                            </label>

                            <label>
                                <span>4</span>
                                <input name="question_<?= $question['id'] ?>" id="radioButton" value="4" type="radio" required>
                            </label>

                            <label>
                                <span>5</span>
                                <input name="question_<?= $question['id'] ?>" id="radioButton" value="5" type="radio" required>
                            </label>
                        </fieldset>
                    <?php endforeach; ?>
                    <textarea name="feedback" id="studentFB" placeholder="Give Feed Back..."></textarea>
                    <button type="submit" id="eval-button">Submit Evaluation</button>
                </form>

            </div>
           
        </div>
    </div>
    <!-- <script src="../../assets/js/hr/hrLL.js"></script> -->
<?php include '../../templates/footer.php'; ?>