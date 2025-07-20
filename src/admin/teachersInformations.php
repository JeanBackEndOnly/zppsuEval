<?php include '../../templates/Uheader.php'; include '../../templates/HeaderNav.php'; ?>
<?php
    // GET PROF ID
    $pdo = db_connect();
    $profSchoolId = $_GET["id"] ?? '';
    $query = "SELECT id FROM professor
    WHERE teacherID = :teacherID";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':teacherID', $profSchoolId);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $profID = $result["id"];
?>
<style>
    .FacultyNav, .CurriculumeNav, .dashboardNav, .EvalNav{
        background: linear-gradient(40deg , #77070b62,#77070b62, #77070A, #77070b62, #77070b62) !important;
    }
</style>
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
            <a href="teachers.php" class="col-md-11 d-flex justify-content-start fw-bold">Go back to faculty management</a>
            <div class="title m-0 col-md-11 d-flex justify-content-start mb-4 fadeInAnimation">
                <label class="text-black fw-bold fs-2 text-muted">FACULTY INFORMATION</label>
            </div>
            <div class="d-flex flex-row col-md-12 col-12 align-items-start justify-content-evenly flex-wrap">
                <div class="profile bg-linear d-flex flex-column col-md-4 col-11 align-items-center justify-content-center py-3 rounded-2">
                    <?php if($facultyInfo["professor_profile"] ?? '' !== null){ ?>
                    <img src="../../assets/image/uploads/<?= $facultyInfo["professor_profile"] ?? 'asdws' ?>" alt="no" style="height: 150px; width: 150px; border-radius: 50%;" class="m-0 p-0">
                    <?php }else{ ?>
                    <img src="../../assets/image/Avatar.png" alt="no" style="height: 150px; width: 150px; border-radius: 50%;">
                    <?php } ?>
                    <label class="text-white fw-bold"><?= $facultyInfo["teacherID"] ?? '' ?></label>
                    <label class="text-white fw-bold"><?= $facultyInfo["lname"] . ", " . $facultyInfo["fname"] . " " . $facultyInfo["mname"] ?></label>
                    <label class="text-white fw-bold"><?= $facultyInfo["department_name"] . " - DEPARTMENT" ?></label>
                    <label class="text-white fw-bold"><?= $facultyInfo["profession"] ?></label>
                    <label class="text-white fw-bold"><?= $facultyInfo["email"] ?></label>
                    <!-- <a href="" class="btn btn-success col-md-11 col-11">Get grade</a> -->
                </div>
                <div class="informations col-md-7 col-11">
                    <h4 class="text-muted fw-bold">EVALUATION INFROMATIONS</h4>
                    <h5 class="fw-bold mb-1">
                        Academic Year: <?php echo isset($getSemester["school_year"]) ? $getSemester["school_year"] . " " . $getSemester["semester"] . " Semester" : "No Available Semester" ?>
                    </h5>
                    <p class="mb-0">
                        <?php

                            $query = "SELECT assigned_at FROM professor_school_year_semester
                            INNER JOIN professor ON professor_school_year_semester.professor_id = professor.id
                            INNER JOIN school_year_semester ON professor_school_year_semester.school_year_semester_id = school_year_semester.id
                            WHERE professor_school_year_semester.professor_id = :professor_id AND school_year_semester.status = 'open' AND professor.evalStatus = 'assigned'";
                            $stmt = $pdo->prepare($query);
                            $stmt->bindParam(':professor_id', $profID);
                            $stmt->execute();
                            $profAssign = $stmt->fetch(PDO::FETCH_ASSOC);
                            $dateString = isset($profAssign["assigned_at"])
                            ? "Assigned at " . date('F-d-Y', strtotime($profAssign["assigned_at"]))
                            : "Not Assigned";
                        ?>
                       <strong>Evaluation Status:</strong>  <span class="fw-semibold text-muted fw-bold"><?php echo $dateString; ?></span>
                    </p>
                   <a href="facultyGrading.php?facultyID=<?= htmlspecialchars($profID) ?>" class="btn btn-success col-md-3 m-1">View grade</a>
                    
                   <div class="mt-4" style="height: 45vh; overflow-y: auto;">
                    <span class="fw-bold text-muted fs-5">RECENT EVALUATIONS</span>    
                    <?php
                        $query = "SELECT * FROM school_year_semester 
                                INNER JOIN professor_school_year_semester 
                                    ON school_year_semester.id = professor_school_year_semester.school_year_semester_id
                                INNER JOIN professor 
                                    ON professor_school_year_semester.professor_id = professor.id
                                WHERE professor.id = :id AND school_year_semester.status = 'closed'";
                        $stmt = $pdo->prepare($query);
                        $stmt->bindParam(':id', $profID);
                        $stmt->execute();
                        $recentEvals = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    ?>
                    <?php if($recentEvals !== '') { ?>
                        <?php foreach($recentEvals as $hehe) : ?>
                            <div class="">
                                <?php echo $hehe["school_year"] ?? 'WALA MAN NAG LABAS KAHIT ISA YAWA NA' ?>
                            </div>
                        <?php endforeach; ?>
                    <?php }else{ ?>
                        <h1>yawa</h1>
                    <?php }  ?>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../templates/Ufooter.php'; ?>
            