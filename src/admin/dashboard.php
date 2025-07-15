<?php include '../../templates/Uheader.php'; include '../../templates/HeaderNav.php'; ?>
<style>
    .dashboardNav{
        background-color: var(--new);
        font-weight: 500;
    }
    .FacultyNav, .CurriculumeNav, .EvalNav{
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
            <div class="title m-0 col-md-11 col-11 d-flex justify-content-start mb-4 fadeInAnimation">
                <label class="text-black fw-bold fs-2 text-muted">DASHBOARD</label>
            </div>
            <div class="rowCount d-flex h-auto flex-wrap col-md-11 col-12 align-items-center justify-content-start">
                <div class="evaluatorsCount rounded-2 shadow me-2 my-2 col-md-4 col-11 h-auto p-2 px-3 bg-linear">
                    <h2 class="m-0 text-white">TOTAL STUDENTS</h2>
                    <h1 class="m-0 text-white"><?php echo $resultCount;?></h1>
                    <p class="m-0 text-white">Users</p>
                </div>
                <div class="evaluatorsCounts rounded-2 shadow my-2 col-md-4 col-11 h-auto p-2 px-3 bg-linear">
                    <h2 class="m-0 text-white">TOTAL EVALUATEE</h2>
                    <h1 class="m-0 text-white"><?php echo $professortCount;?></h1>
                    <p class="m-0 text-white">Teachers</p>
                </div>
            </div>
            <div class="containerDashboard d-flex justify-content-start align-items-center col-md-11 col-11">

                <div class="card shadow-sm border-start border-2 border-info">
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
            