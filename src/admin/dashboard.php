<?php include '../../templates/Uheader.php'; include '../../templates/HeaderNav.php'; ?>
<style>
    .dashboardNav{
        background-color: var(--new);
        font-weight: 500;
    }
    .FacultyNav{
        background-color: #be0b11ff !important;
    }
    .CurriculumeNav{
        background-color: #be0b11ff !important;
    }
</style>
<div class="main w-100 h-100 d-flex flex-column">
    <?= getAdminHeader() ?>
    <div class="row w-100 p-0 m-0">
        <div class="col-auto sideNav bg-linear h-100">
            <div class="sideContents" id="sideContents">
                <div class="profileBox w-100 d-flex flex-column justify-content-center align-items-center mt-2 mb-3">
                    <img src="../../assets/image/zppsu-logo.png" alt="pfp" id="pfpOnTop">
                    <label class="fw-bold text-white"><?= $student_info["SchoolID"] ?></label>
                    <h5 class="text-white"><?= $student_info["lname"] . ", " . $student_info["fname"]  ?></h5>
                </div>
                <?= getAdminNav() ?>
            </div>
        </div>

        <div class="col content p-0">
            <div class="rowCount">
                <div class="evaluatorsCount">
                    <h2>TOTAL STUDENTS</h2>
                    <h1><?php echo $resultCount;?></h1>
                    <p>Users</p>
                </div>
                <div class="evaluatorsCounts">
                    <h2>TOTAL EVALUATEE</h2>
                    <h1><?php echo $professortCount;?></h1>
                    <p>Teachers</p>
                </div>
            </div>
            <div class="containerDashboard" style="margin-top: -5rem; margin-left: 1.2rem; height: 20vh; width: 60%;">

                <div class="card shadow-sm border-start border-2 border-info px-1 py-1" style="max-width: 500px; padding: 0;">
                    <div class="card-body text-start">
                        <h5 class="fw-bold mb-1" style="color: #000; font-size: 23px; margin: 15px 0;">
                            Academic Year: <?php echo isset($getSemester["school_year"]) ? $getSemester["school_year"] . " " . $getSemester["semester"] . " Semester" : "No Available Semester" ?>
                        </h5>
                        <p class="mb-0" style="color: #000; font-size: 20px;">Evaluation Status: <span class="fw-semibold"><?php echo isset($getSemester["status"]) ? $getSemester["status"] : "Closed"; ?></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
    <!-- <script src="../../assets/js/hr/hrLL.js"></script> -->
<?php include '../../templates/Ufooter.php'; ?>
            