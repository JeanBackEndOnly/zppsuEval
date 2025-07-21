<?php include '../../templates/Uheader.php'; include '../../templates/HeaderNav.php'; ?>
<?php
    $pdo = db_connect();
    $query = "SELECT * FROM school_year_semester WHERE status = 'open'";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $reSemesterID = $result["id"];

    $faculty_id = $_GET["facultyID"] ?? '';
    $query = "SELECT * FROM total_grade
        INNER JOIN professor ON total_grade.professor_id = professor.id
        INNER JOIN professor_school_year_semester ON professor.id = professor_school_year_semester.professor_id
        INNER JOIN school_year_semester ON professor_school_year_semester.school_year_semester_id = school_year_semester.id
        WHERE total_grade.school_year_semester_id = :semesterID";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([':semesterID' => $reSemesterID]);
    $facultyData = $stmt->fetch(PDO::FETCH_ASSOC); // faculty data for the current semester

    // Get evaluator weight for students
     $query = "SELECT weight_percent FROM evaluator_group_weight WHERE evaluator_type = 'STUDENT'";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $studentWeightData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($studentWeightData) {
        // If a result is found, assign the weight_percent to the variable
        $studentWeight = $studentWeightData['weight_percent'];
    } else {
        // If no result is found, handle the case (default value or error message)
        $studentWeight = 0; // Default to 0 or any other value you prefer
        echo "<p>Error: Student weight not found in the database.</p>";
    }

    // Get grades for the professor from student evaluators
    $query = "SELECT AVG(score) as avg_rating FROM grade 
              WHERE professor_id = :faculty_id AND school_year_semester_id = :semesterID AND evaluator_type = 'STUDENT'";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([':faculty_id' => $faculty_id, ':semesterID' => $reSemesterID]);
    $gradeData = $stmt->fetch(PDO::FETCH_ASSOC);
    $studentsAvgRating = $gradeData['avg_rating'] ?? 0;

    // Calculate equivalent points for students
    $studentsEquivalentPoint = ($studentsAvgRating * $studentWeight) / 100;

    // Calculate total weighted average (since it's only students, it's just equivalent point)
    $totalWeightedAverage = $studentsEquivalentPoint;
?>
<div class="main w-100 h-100 d-flex flex-column">
    <?= getAdminHeader() ?>
    <div class="row w-100 p-0 m-0">
        <div class="col-auto sideNav bg-linear h-100">
            <div class="sideContents" id="sideContents">
                <div class="profileBox w-100 d-flex flex-column justify-content-center align-items-center mt-2 mb-3">
                    <img src="../../assets/image/admin.png" alt="pfp" id="pfpOnTop">
                    <h5 class="text-white">ADMIN</h5>
                </div>
                <?= getAdminNav() ?>
            </div>
        </div>

        <div class="col content p-0 d-flex flex-column justify-content-start align-items-center fadeInAnimation">
            <div class="backToProfile col-md-11 col-11 d-flex justify-content-start">
                <a href="teachersInformations.php?id=<?= $facultyData["teacherID"] ?>" class="fw-bold mb-3">Go back to profile</a>
            </div>
            <div class="col-md-11 col-11 h-auto shadow rounded-2 d-flex flex-column justify-content-start align-items-center p-3">
                <h4>ZAMBOANGA CITY STATE POLYTECHNIC COLLEGE</h4>
                <span>Region IX, Zamboanga Peninsula</span>
                <span>R.T.Lim, Boulevard, Zamboanga City</span>
                <br>
                <h5>KEY RESULT AREA 1: INSTRUCTION</h5>
                <h5>A. TEACHING EFFECTIVENESS</h5>
                <span>Faculty Performance Evaluation by Students</span>
                <span>Evaluation Period: </span>
                <br>
                <h5>SUMMARY SHEET FOR</h5>
                <h5>FIRST SEMESTER, SCHOOL YEAR 2019 - 2020</h5>
                <br>
                <div class="col-md-10 col-10 d-flex justify-content-between">
                    <label for="">Name of Faculty: <strong><?= $facultyData["fname"] . " " . $facultyData["mname"] . ". " . $facultyData["lname"] ?></strong></label>
                    <label for="">Present Academic Rank: <strong> <?= $facultyData["profession"] ?> </strong></label>
                </div>
                <br>
                <table class="table table-bordered table-responsive col-md-10 col-10">
                    <thead>
                        <tr>
                            <th>EVALUATOR</th>
                            <th>AVERAGE RATING</th>
                            <th>WEIGHT</th>
                            <th>EQUIVALENT POINT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>STUDENTS</td>
                            <td><?= number_format($studentsAvgRating, 2) ?></td>
                            <td><?= number_format($studentWeight, 2) ?>%</td>
                            <td><?= number_format($studentsEquivalentPoint, 2) ?></td>
                        </tr>
                        <tr>
                            <td>TOTAL/WEIGHTED AVERAGE</td>
                            <td><?= number_format($totalWeightedAverage, 2) ?></td>
                            <td>100%</td>
                            <td><?= number_format($totalWeightedAverage, 2) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../../templates/Ufooter.php'; ?>
