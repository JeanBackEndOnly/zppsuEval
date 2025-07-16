<?php include '../../templates/Uheader.php'; include '../../templates/HeaderNav.php'; ?>
<style>
    .dashboardNav, .evaluationNav{
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

        <div class="col content p-0 d-flex flex-column justify-content-start align-items-center">
            <div class="title m-0 col-md-11 d-flex justify-content-start mb-4">
                <label class="text-black fw-bold fs-2 text-muted">SETTINGS</label>
            </div>
            <div class="row d-flex col-md-12 col-12 align-items-start justify-content-center p-0 m-0 gap-3">
                <div class="col-md-5 col-11 p-0 m-0 shadow rounded-2 p-3 ms-2 bg-linear">
                    <label class="ms-2 fw-bold fs-5 text-white mb-3">Change password here</label>
                    <form action="../../auth/authentications.php" method="post" class="w-100 d-flex flex-column px-2">
                        <input type="hidden" name="passwordChange" value="users">
                        <div class="my-2">
                            <label for="" class="ms-2 fw-bold text-white">Current Password</label>
                            <input type="password" name="current_password" class="form-control">
                        </div>
                        <div class="my-2">
                            <label for="" class="ms-2 fw-bold text-white">New Password</label>
                            <input type="password" name="new_password" class="form-control">
                        </div>
                        <div class="my-2">
                            <label for="" class="ms-2 fw-bold text-white">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control">
                        </div>
                        <div class="buttonChange col-md-12 col-12 d-flex justify-content-end">
                            <button class="btn btn-success">Change Password</button>
                        </div>
                    </form>
                </div>  
                <div class="col-md-6 col-12 p-0 m-0 rounded-2 d-flex flex-column align-items-center justify-content-center  ms-2">
                    <label class="ms-2 fw-bold fs-5 text-muted mb-3">Update your profile here</label>
                    <div class="profile col-md-12 col-11 bg-linear d-flex align-items-center flex-column rounded-2 pt-2">
                        <label for="user_profile">
                            <img src="../../assets/image/Avatar.png" alt="" style="height: 150px; width: 150px; border-radius: 50%;">
                        </label>
                        <label for="" class="text-white fw-bold">Profile Picture</label>
                        <input type="file" style="display: none;" name="users_profile" id="user_profile" value="<?= $student_info["user_profile"] ?>">
                    </div>
                    <div class="usersInfo col-md-12 col-12 d-flex flex-row flex-wrap justify-content-evenly">
                        <div class="m-1 col-md-5 col-11">
                            <label for="" class="fw-bold text-muted">Stduent ID:</label>
                            <input type="text" name="SchoolID" value="<?= $student_info["SchoolID"] ?>" class="form-control">
                        </div>
                        <div class="m-1 col-md-5 col-11">
                            <label for="" class="fw-bold text-muted">Last name:</label>
                            <input type="text" name="SchoolID" value="<?= $student_info["lname"] ?>" class="form-control">
                        </div>
                        <div class="m-1 col-md-5 col-11">
                            <label for="" class="fw-bold text-muted">First Name:</label>
                            <input type="text" name="SchoolID" value="<?= $student_info["fname"] ?>" class="form-control">
                        </div>
                        <div class="m-1 col-md-5 col-11">
                            <label for="" class="fw-bold text-muted">Middle Name:</label>
                            <input type="text" name="SchoolID" value="<?= $student_info["mname"] ?>" class="form-control">
                        </div>
                        <div class="m-1 col-md-5 col-11">
                            <label for="" class="fw-bold text-muted">Departmetn:</label>
                            <input type="text" name="SchoolID" value="<?= $student_info["department_name"] ?>" class="form-control">
                        </div>
                        <div class="m-1 col-md-5 col-11">
                            <label for="" class="fw-bold text-muted">Year and Section:</label>
                            <input type="text" name="SchoolID" value="<?= $student_info["year_level"] . " - " .  $student_info["section"] ?>" class="form-control">
                        </div>
                        <div class="buttonUPdate col-md-12 col-12 d-flex justify-content-end">
                            <button class="btn btn-success">Update Profile</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (CurrentPass) {
            console.log("Showing updateReq toast");
            Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'Current Password not match!.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: { popup: 'swal2-row-toast' }
            });
            removeUrlParams(['CurrentPass']);
        }else if (Settingspassword) {
            console.log("Showing updateReq toast");
            Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Paasword changed successfully!.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: { popup: 'swal2-row-toast' }
            });
            removeUrlParams(['Settingspassword']);
        }else if (Password) {
            console.log("Showing updateReq toast");
            Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'Password not match!.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: { popup: 'swal2-row-toast' }
            });
            removeUrlParams(['Password']);
        }
        function removeUrlParams(params) {
            const url = new URL(window.location);
            params.forEach(param => url.searchParams.delete(param));
            window.history.replaceState({}, document.title, url.toString());
        }
    });
</script>
<?php include '../../templates/Ufooter.php'; ?>