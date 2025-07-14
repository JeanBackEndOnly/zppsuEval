<?php function getHeader() { ?>

    <div class="header bg-linear w-100 d-flex flex-row justify-content-between mb-1">
        <div class="logo" style="display: flex; height: 100%; align-items: center;">
            <img src="../../assets/image/zppsu-logo.png" alt="pfp" id="pfpOnTop" class="ms-4 headerImg">
            <h3 id="userTitle" class="text-white m-0 ms-2">ZPPSU EVALUATION SYSTEM</h3>
        </div>
        <div class="otherButtons d-flex flex-row w-auto me-5">
            <button type="submit" onclick="profileMenu()" id="buttonpfpmenu" class="d-flex buttonNoBG flex-row w-auto align-items-center h-100">
                <img src="../../assets/image/zppsu-logo.png" class="headerImg" alt="pfp" id="pfpOnTop">
                <p class="d-flex h-100 p-0 m-0 align-items-center text-white"><?php echo isset($student_info["lname"]) ? $student_info["lname"] : "nope" ." "; ?><i class="ms-1 fa-solid fa-caret-down"></i></p>
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
        </script>
            <div class="profileMenu bg-linearOne shadow flex-column rounded-2 w-auto h-auto p-2" id="profileMenu" style="display: none; z-index: 5 !importantl">
                <li id="borderBottom" class="m-0 mb-2 mt-1"><a href="settings.php"><p class="m-0"><i class="fa-solid fa-gear"></i>SETTINGS</p></a></li>
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
        <button type="submit" onclick="getFaculty()" class="FacultyNav buttonNoBG text-white p-1 px-2 d-flex justify-content-between rounded-2 align-items-center fs-5 mb-1">
            Faculty<i class="fa-solid fa-caret-down ms-1" id="iLeft"></i>
        </button>
         <ul style="display: none;" id="Faculty" class="hrNavs flex-column p-0 m-0">
            <a class="text-white p-2" href="teachers.php"><p class="m-0"><i class="fa-solid fa-users me-2"></i>Faculty</p></a>
            <a class="text-white p-2" href="assignedProf.php"><p class="m-0"><i class="fa-solid fa-building-flag me-2"></i>Faculty Evaluation</p></a>
        </ul>
        <button type="submit" onclick="getCurriculum()" class="CurriculumeNav buttonNoBG text-white p-1 px-2 d-flex justify-content-between rounded-2 align-items-center fs-5">
            Curriculum<i class="fa-solid fa-caret-down ms-1" id="iLeft"></i>
        </button>
        <ul style="display: none;" id="Curriculum" class="hrNavs flex-column p-0 m-0">
            <a class="text-white p-2" href="subjects.php"><p class="m-0"><i class="fa-solid fa-briefcase me-2"></i>Subjects</p></a>
            <a class="text-white p-2" href="departments.php"><p class="m-0"><i class="fa-solid fa-building me-2"></i>Departments</p></a>
            <a class="text-white p-2" href="semester.php"><p class="m-0"><i class="fa-solid fa-calendar me-2"></i>Academic Year</p></a>
            <a class="text-white p-2" href="yearSection.php"><p class="m-0"><i class="fa-solid fa-building-flag me-2"></i>Year and Section</p></a>
        </ul>
    </div>
    <script>
        function getFaculty(){
            const sd = document.getElementById("Faculty");
            console.log("button clicked!")
            if(sd.style.display == 'none'){
                sd.style.display = 'flex';
            }else{
                sd.style.display = 'none';
            }
        }
         function getCurriculum(){
            const sd = document.getElementById("Curriculum");
            console.log("button clicked!")
            if(sd.style.display == 'none'){
                sd.style.display = 'flex';
            }else{
                sd.style.display = 'none';
            }
        }
    </script>

<?php } ?>

<?php function getAdminHeader() { ?>

    <div class="header bg-linear w-100 d-flex flex-row justify-content-between mb-1">
        <div class="logo" style="display: flex; height: 100%; align-items: center;">
            <img src="../../assets/image/zppsu-logo.png" alt="pfp" id="pfpOnTop" class="ms-4 headerImg">
            <h3 id="userTitle" class="text-white m-0 ms-2">ZPPSU EVALUATION SYSTEM</h3>
        </div>
        <div class="otherButtons d-flex flex-row w-auto me-5">
            <button type="submit" onclick="profileMenu()" id="buttonpfpmenu" class="d-flex buttonNoBG flex-row w-auto align-items-center h-100">
                <img src="../../assets/image/zppsu-logo.png" class="headerImg" alt="pfp" id="pfpOnTop">
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
        </script>
            <div class="profileMenu bg-linearOne shadow flex-column rounded-2 w-auto h-auto p-2" id="profileMenu" style="display: none; z-index: 5 !importantl">
                <li id="borderBottom" class="m-0 mb-2 mt-1"><a href="settings.php"><p class="m-0"><i class="fa-solid fa-gear me-2"></i>SETTINGS</p></a></li>
                <li class="m-0 mb-1"><a href="../logout.php" class="m-0" id="l"><p class="m-0"><i class="fa-solid fa-right-from-bracket"></i>LOGOUT</p></a></li>
            </div>
        </div>
    </div>

<?php } ?>