<?php include '../templates/header.php';?>

    <main id="main" class="login-page mb-3">
        <div class="container w-auto">
            <div class="row">
                <!-- <div class="col-md-4 col-12 mx-auto"> -->
                    <div class="card p-0 m-0">
                        <div class="headerLogin rounded-2 p-3">
                            <div class="logoZppsu w-100 d-flex justify-content-center">
                                <img src="../assets/image/zppsuLogo.png" alt="" style="width: 120px; height: 120px;">
                            </div>
                            <div class="card-headers">
                                <h1 class="fs-3 text-white p-0 text-center" style="font-family: 'Poppins', sans-serif; background:none; padding-top: 2rem;">ZPPSU EVALUATION SYSTEM</h1>
                            </div>
                        </div>
                        <div class="card-headers w-100 d-flex justify-content-start">
                            <h4 class="text-center ms-3 mt-1" style="font-family: 'Poppins', sans-serif; background:none;">Login</h4>
                        </div>
                        <div class="card-body w-100 m-0 px-3 py-1">
                            <form action = "../login/login.php" method = "post" class="col-md-12 col-12 mx-auto">

                                <div class="mb-3">
                                    <input type="text" class="form-control col-md-11 col-11" name="username" placeholder="Username: " required>
                                </div>
                                <div class="mb-3">
                                    <input type="password" class="form-control col-md-11 col-11" name="password" placeholder="Password: " required>
                                </div>
                                <div class="m-0 text-center">
                                    <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
                                </div>
                                <div class="mb-3 text-center">
                                    <a href="register.php" style="color: blue;">Register</a>
                                </div>
                            </form>
                        </div>
                    </div>
                <!-- </div> -->
            </div>
        </div>
    </main>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (signup) {
            console.log("Showing updateReq toast");
            Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Sign-up Successfully!.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: { popup: 'swal2-row-toast' }
            });
            removeUrlParams(['signup']);
        }else if (usernameNotmatch) {
            console.log("Showing updateReq toast");
            Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'Username not match!.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: { popup: 'swal2-row-toast' }
            });
            removeUrlParams(['username']);
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../webApp/main.js"></script>
<?php include '../templates/footer.php'?>