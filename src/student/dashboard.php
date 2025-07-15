<?php include '../../templates/Uheader.php'; include '../../templates/HeaderNav.php'; ?>
<style>
    .dashboardNav{
        background-color: var(--new);
        font-weight: 500;
    }
    .evaluationNav{
        background: linear-gradient(40deg , #77070b62,#77070b62, #77070A, #77070b62, #77070b62) !important;
    }
</style>
<div class="main w-100 h-100 d-flex flex-column">
    <?= getHeader() ?>
    <div class="row w-100 p-0 m-0">
        <div class="col-auto sideNav bg-linear h-100">
            <div class="sideContents" id="sideContents">
                <div class="profileBox w-100 d-flex flex-column justify-content-center align-items-center mt-2 mb-3">
                    <?php if($student_info["user_profile"] != '') { ?><img src="../../assets/image/<?= $student_info["user_profile"] ?>" alt="pfp" id="pfpOnTop"><?php }else{ ?>
                    <img src="../../assets/image/Avatar.png" alt="pfp" id="pfpOnTop"><?php } ?>
                    <label class="fw-bold text-white"><?= $student_info["SchoolID"] ?></label>
                    <label class="text-white text-center fw-bold"><?= $student_info["lname"] . ", " . $student_info["fname"]  ?></label>
                </div>
                <?= getNav() ?>
            </div>
        </div>

        <div class="col content p-0">
            <div class="containerDashboard w-100">
                <h3 class="fw-semibold mb-4 ms-3" style="color: #000">
                    Welcome <span class="text-uppercase text-center"><?php echo htmlspecialchars($student_info["lname"] . " " . $student_info["lname"]); ?>!</span>
                </h3>

                <div class="card shadow-sm border-start border-2 border-info px-1 py-1 ms-3 col-md-5 col-11">
                    <div class="card-body text-start">
                        <h5 class="fw-bold mb-1">
                            Academic Year: <?php echo isset($getSemester["school_year"]) ? $getSemester["school_year"] . " " . $getSemester["semester"] . " Semester" : "No Available Semester" ?>
                        </h5>
                        <p class="mb-0">
                            Evaluation Status: <span class="fw-semibold"><?php echo isset($getSemester["status"]) ? $getSemester["status"] : "Closed"; ?></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
    <!-- <script src="../../assets/js/hr/hrLL.js"></script> -->
<?php include '../../templates/Ufooter.php'; ?>