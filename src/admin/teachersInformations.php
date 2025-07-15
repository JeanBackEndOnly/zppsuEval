<?php include '../../templates/Uheader.php'; include '../../templates/HeaderNav.php'; ?>
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
            <div class="d-flex flex-row col-md-12 col-12 align-items-center justify-content-evenly">
                <div class="profile bg-linear d-flex flex-column col-md-4 col-11 align-items-center justify-content-center py-3 rounded-2">
                    <?php if($facultyInfo["professor_profile"] !== null){ ?>
                    <img src="../../assets/image/uploads/<?= $facultyInfo["professor_profile"]?>" alt="no" style="height: 150px; width: 150px; border-radius: 50%;" class="m-0 p-0">
                    <?php }else{ ?>
                    <img src="../../assets/image/Avatar.png" alt="no" style="height: 150px; width: 150px; border-radius: 50%;">
                    <?php } ?>
                    <label class="text-white fw-bold"><?= $facultyInfo["teacherID"] ?? '' ?></label>
                    <label class="text-white fw-bold"><?= $facultyInfo["lname"] . ", " . $facultyInfo["fname"] . " " . $facultyInfo["mname"] ?></label>
                    <label class="text-white fw-bold"><?= $facultyInfo["department_name"] . " - DEPARTMENT" ?></label>
                    <label class="text-white fw-bold"><?= $facultyInfo["profession"] ?></label>
                    <label class="text-white fw-bold"><?= $facultyInfo["email"] ?></label>
                </div>
                <div class="informations col-md-7 col-11">
                    <h4 class="text-muted fw-bold">EVALUATION INFROMATIONS</h4>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../templates/Ufooter.php'; ?>
            