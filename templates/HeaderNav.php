<?php include_once '../../auth/control.php'; 
$info = getUsersInfo();
$student_info = $info['student_info']; ?>

<?php function getHeader() { ?>

    <div class="header bg-linear w-100 d-flex flex-row justify-content-between mb-1">
        <div class="burger" style="display: none;">
            <button type="button" style="background: none; border: none;" onclick="burgerButton()"><i class="fa-solid fa-bars fs-3 ms-2 text-white"></i></button>
        </div>
        <div class="logo" style="display: flex; height: 100%; align-items: center;">
            <img src="../../assets/image/zppsu-logo.png" alt="pfp" id="pfpOnTop" class="ms-4 headerImg">
            <h3 id="userTitle" class="text-white m-0 ms-2">ZPPSU EVALUATION SYSTEM</h3>
        </div>
        <div class="otherButtons d-flex flex-row w-auto">
            <button type="submit" onclick="profileMenu()" id="buttonpfpmenu" class="d-flex buttonNoBG flex-row w-auto align-items-center h-100">
                <img src="../../assets/image/Avatar.png" class="headerImg" alt="pfp" id="pfpOnTop">
                <p class="d-flex h-100 p-0 m-0 align-items-center text-white ms-2"><?= $student_info["lname"] ?? 'Student' ?><i class="ms-2 fa-solid fa-caret-down"></i></p>
            </button>
        <script>
            function profileMenu(){
                const sd = document.getElementById("profileMenu");
                console.log("button clicked!")
                if(sd.style.display == 'none'){
                    sd.style.display = 'flex';
                }else{
                    sd.style.display = 'none';
                }
            }
           let isShifted = false; // <-- ADD THIS LINE at the top

            function profileMenu(){
                const sd = document.getElementById("profileMenu");
                console.log("button clicked!");
                if(sd.style.display == 'none'){
                    sd.style.display = 'flex';
                } else {
                    sd.style.display = 'none';
                }
            }

            function burgerButton() {
                const sidebar = document.querySelector(".sideNav");
                if (!sidebar) {
                    console.warn("Element with class 'sideNav' not found.");
                    return;
                }

                sidebar.style.display = 'flex';
                sidebar.style.flexDirection = 'column';
                sidebar.style.transition = 'transform 0.45s cubic-bezier(.4,0,.2,1)';
                sidebar.style.willChange = 'transform';

                if (!isShifted) {
                    sidebar.style.transform = 'translateX(0)';
                } else {
                    sidebar.style.transform = 'translateX(-50rem)';
                }

                isShifted = !isShifted;
            }
        </script>
            <div class="profileMenu bg-linearOne shadow flex-column rounded-2 w-auto h-auto p-3" id="profileMenu" style="display: none; z-index: 5 !important">
                <li id="borderBottom" class="m-0 mb-3 mt-1"><a href="settings.php"><p class="m-0"><i class="fa-solid fa-gear"></i>SETTINGS</p></a></li>
                <li class="m-0 mb-1"><a href="../logout.php" class="m-0" id="l"><p class="m-0"><i class="fa-solid fa-right-from-bracket"></i>LOGOUT</p></a></li>
            </div>
        </div>
    </div>

<?php } ?>

<?php function getNav() { ?>

    <div class="menuBox">
        <ul class="w-100 d-flex flex-column p-0 m-0">
            <a href="dashboard.php" class="aNav dashboardNav p-2 rounded-1 w-100 mb-2 text-white">
                <i class="fa-solid fa-house-user me-2"></i>DASHBOARD
            </a>
            <a href="evaluation.php" class="aNav evaluationNav p-2 rounded-1 w-100 text-white">
                <i class="fa-solid fa-file me-2"></i>Evaluate
            </a></ul>
    </div>

<?php } ?>

<!-- ===================== ADMIN AREA ===================== -->
<?php function getAdminNav() { ?>

    <div class="menuBox h-75 d-flex flex-column">
        <a href="dashboard.php" id="dashboard-a" class="text-white dashboardNav mb-1 p-2 col-md-12 rounded-1"><i class="fa-solid fa-house-user me-2"></i>DASHBOARD</a>
        <button type="submit" onclick="getFaculty()" class="FacultyNav buttonNoBG text-white p-1 px-2 d-flex justify-content-between rounded-2 align-items-center fs-5 mb-1" style="transition: .5s ease-in-out;">
            Faculty<i class="fa-solid fa-caret-down ms-1" id="iLeft"></i>
        </button>
         <ul style="display: none;" id="Faculty" class="hrNavs flex-column p-0 m-0">
            <a class="text-white p-2" href="teachers.php"><p class="m-0"><i class="fa-solid fa-users me-2"></i>Faculty</p></a>
            <a class="text-white p-2" href="assignedProf.php"><p class="m-0"><i class="fa-solid fa-user-pen me-2"></i>Faculty Evaluation</p></a>
        </ul>
        <button type="submit" style="transition: .5s ease-in-out;" onclick="getCurriculum()" class="CurriculumeNav buttonNoBG text-white p-1 px-2 d-flex justify-content-between rounded-2 align-items-center fs-5">
            Curriculum<i class="fa-solid fa-caret-down ms-1" id="iLeft"></i>
        </button>
        <ul style="display: none;" id="Curriculum" class="hrNavs flex-column p-0 m-0">
            <a class="text-white p-2" href="subjects.php"><p class="m-0"><i class="fa-solid fa-briefcase me-2"></i>Subjects</p></a>
            <a class="text-white p-2" href="departments.php"><p class="m-0"><i class="fa-solid fa-building me-2"></i>Departments</p></a>
            <a class="text-white p-2" href="semester.php"><p class="m-0"><i class="fa-solid fa-calendar me-2"></i>Academic Year</p></a>
            <a class="text-white p-2" href="yearSection.php"><p class="m-0"><i class="fa-solid fa-building-flag me-2"></i>Year and Section</p></a>
        </ul>
         <button type="submit" style="transition: .5s ease-in-out;" onclick="getEval()" class="EvalNav buttonNoBG mt-1 text-white p-1 px-2 d-flex justify-content-between rounded-2 align-items-center fs-5">
            Evaluation<i class="fa-solid fa-caret-down ms-1" id="iLeft"></i>
        </button>
        <ul style="display: none;" id="Eval" class="hrNavs flex-column p-0 m-0">
            <a class="text-white p-2" href="questions.php"><p class="m-0"><i class="fa-solid fa-briefcase me-2"></i>Questions</p></a>
            <a class="text-white p-2" href="criteria.php"><p class="m-0"><i class="fa-solid fa-building me-2"></i>Criteria</p></a>
            <a class="text-white p-2" href="scale.php"><p class="m-0"><i class="fa-solid fa-building me-2"></i>Grading Scale</p></a>
        </ul>
    </div>
    <script>
        function getFaculty(){
            const sd = document.getElementById("Faculty");
            console.log("button clicked!")
            if(sd.style.display == 'none'){
                sd.style.display = 'flex';
                sd.classList.add('fadeInAnimationNav');
            }else{
                sd.style.display = 'none';
            }
        }
         function getCurriculum(){
            const sd = document.getElementById("Curriculum");
            console.log("button clicked!")
            if(sd.style.display == 'none'){
                sd.style.display = 'flex';
                sd.classList.add('fadeInAnimationNav');
            }else{
                sd.style.display = 'none';
            }
        }
        function getEval(){
            const sd = document.getElementById("Eval");
            console.log("button clicked!")
            if(sd.style.display == 'none'){
                sd.style.display = 'flex';
                sd.classList.add('fadeInAnimationNav');
            }else{
                sd.style.display = 'none';
            }
        }
    </script>

<?php } ?>

<?php function getAdminHeader() { ?>

    <div class="header bg-linear w-100 d-flex flex-row justify-content-between mb-1">
        <div class="burger" style="display: none;">
            <button type="button" style="background: none; border: none;" onclick="burgerButtonAdmin()"><i class="fa-solid fa-bars fs-3 ms-2 text-white"></i></button>
        </div>
        <div class="logo" style="display: flex; height: 100%; align-items: center;">
            <img src="../../assets/image/zppsu-logo.png" alt="pfp" id="pfpOnTop" class="ms-4 headerImg">
            <h3 id="userTitle" class="text-white m-0 ms-2">ZPPSU EVALUATION SYSTEM</h3>
        </div>
        <div class="otherButtons d-flex flex-row w-auto">
            <button type="submit" onclick="profileMenu()" id="buttonpfpmenu" class="d-flex buttonNoBG flex-row w-auto align-items-center h-100">
                <img src="../../assets/image/admin.png" class="headerImg" alt="pfp" id="pfpOnTop">
                <p class="d-flex h-100 p-0 m-0 align-items-center text-white">ADMIN<i class="ms-1 fa-solid fa-caret-down"></i></p>
            </button>
        <script>
            function profileMenu(){
            const sd = document.getElementById("profileMenu");
            console.log("button clicked!")
            if(sd.style.display == 'none'){
                sd.style.display = 'flex';
            }else{
                sd.style.display = 'none';
            }
        }
        let isShifted = false;
        function burgerButtonAdmin() {
                const sidebar = document.querySelector(".sideNav");
                if (!sidebar) {
                    console.warn("Element with class 'sideNav' not found.");
                    return;
                }

                sidebar.style.display = 'flex';
                sidebar.style.flexDirection = 'column';
                sidebar.style.transition = 'transform 0.45s cubic-bezier(.4,0,.2,1)';
                sidebar.style.willChange = 'transform';

                if (!isShifted) {
                    sidebar.style.transform = 'translateX(0)';
                } else {
                    sidebar.style.transform = 'translateX(-50rem)';
                }

                isShifted = !isShifted;
            }
        </script>
            <div class="profileMenu bg-linearOne shadow flex-column rounded-2 w-auto h-auto p-3" id="profileMenu" style="display: none; z-index: 5 !importantl">
                <li id="borderBottom mb-1" class="m-0 mb-2 mt-1"><a href="settings.php"><p class="m-0"><i class="fa-solid fa-gear me-2"></i>SETTINGS</p></a></li>
                <li class="m-0 mb-1"><a href="../logout.php" class="m-0" id="l"><p class="m-0"><i class="fa-solid fa-right-from-bracket"></i>LOGOUT</p></a></li>
            </div>
        </div>
    </div>

<?php } ?>