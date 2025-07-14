<?php include_once '../../auth/control.php'; $info = getUsersInfo();
    $admin_info = $info['admin_info'];
    $resultCount = $info['resultCount'];
    $professortCount = $info['professortCount'];
    $usersAccount = $info['usersAccount'];
    $profEdit = $info['profEdit'];
    $subject = $info['subject'];

    // echo isset($_GET["profID"]) ? $profID = $_GET["profID"] : "null";
    $profSubjects = isset($profEdit['subjects']) ? array_map('trim', explode(',', $profEdit['subjects'])) : [];
    ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDIT INFORMATION</title>
    <!-- <link rel="stylesheet" href="../../assets/css/main_frontend.css?v=<?php echo time(); ?>"> -->
    <link rel="stylesheet" href="../../assets/css/hr.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../assets/css/profile.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
      @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
    </style>
    <script src="../../assets/js/main.js"></script>
</head>
<body>
    <div class="sideNav">
            <div class="sideContents" id="sideContents">
                <div class="profileBox">
                <img src="../../assets/image/zppsu-logo.png" alt="pfp" id="pfpOnTop">
                    <h4>Since 1913</h4>
                    <p>Zamboanga Peninsula Region IX</p>
                </div>
                <div class="menuBox">
                    <ul>
                       <a href="dashboard.php" id="dashboard-a"><button id="buttonDashboard"><i class="fa-solid fa-house-user"></i>DASHBOARD</button></a>
                        <button type="submit" onclick="getHrNavs()">Faculty & Curriculum<i class="fa-solid fa-caret-down" id="iLeft"></i></button>
                        <ul style="display: none;" id="hrNavs" class="hrNavs">
                            <a href="teachers.php"><p><i class="fa-solid fa-users"></i>Faculty</p></a>
                            <a href="subjects.php"><p><i class="fa-solid fa-briefcase"></i>Subjects</p></a>
                           <a href="departments.php"><p><i class="fa-solid fa-building"></i>Departments</p></a>
                           <a href="semester.php"><p><i class="fa-solid fa-calendar"></i>Academic Year</p></a>
                           <a href="yearSection.php"><p><i class="fa-solid fa-building-flag"></i>Year and Section</p></a>
                           <a href="assignedProf.php"><p><i class="fa-solid fa-users-gear"></i>Faculty Evaluation</p></a>
                        </ul>
                    </ul>
                </div>
            </div>
        </div>
    
    <div class="columnFlex">
        <div class="header">
        <div class="logo" style="display: flex; height: 100%; align-items: center;">
            <!-- <img src="../../assets/image/zppsu-logo.png" alt="pfp" id="pfpOnTop" style="height: 50px; width: 50px; border-radius: 50%; margin-right: 10px;"> -->
            <h3 id="userTitle">ZPPSU EVALUATION SYSTEM</h3>
        </div>
        <div class="otherButtons">
            <button type="submit" onclick="profileMenu()" id="buttonpfpmenu">
                <img src="../../assets/image/zppsu-logo.png" alt="pfp" id="pfpOnTop">
                <p><?php echo $admin_info["firstname"] ." "; ?><i class="fa-solid fa-caret-down"></i></p>
            </button>
            
            <div class="profileMenu" id="profileMenu" style="display: none;">
                <li id="borderBottom"><a href="settings.php"><p><i class="fa-solid fa-gear"></i>SETTINGS</p></a></li>
                <li><a href="../logout.php" id="l"><p><i class="fa-solid fa-right-from-bracket"></i>LOGOUT</p></a></li>
            </div>
        </div>
    </div>
        <div class="contents">
            <div class="editDiv">
                <div class="headerEdit">
                    <h2>Edit Professor</h2><button type="button" onclick="addSubject()">Add Another Subject</button><br><br>
                </div>
                <form method="POST" action="../../auth/authentications.php?profIdPo=<?php echo $profEdit["id"] ?>" enctype="multipart/form-data">
                    <input type="hidden" name="editProf" value="admin">
                    <!-- <input type="hidden" name="profID" value="<?= htmlspecialchars($profID) ?>"> -->
                    <div class="profile">
                        <label for="profPic"><img id="imageProfile" src="../../assets/image/upload/<?= htmlspecialchars($profEdit['professor_Profile'] ?? '') ?>" style="width: 150px; height: 150px; border-radius: 50%;" alt="profile"></label>
                        <input type="file" name="professor_Profile" id="profPic" value="<?= htmlspecialchars($profEdit['professor_Profile'] ?? '') ?>" onchange="previewImage(event)">
                    </div>
                    <label>Teacher ID:</label><br>
                    <input type="text" name="teacherID" value="<?= htmlspecialchars($profEdit['teacherID'] ?? '') ?>"><br>

                    <label>Last Name:</label><br>
                    <input type="text" name="Lname" value="<?= htmlspecialchars($profEdit['Lname'] ?? '') ?>"><br>

                    <label>First Name:</label><br>
                    <input type="text" name="Fname" value="<?= htmlspecialchars($profEdit['Fname'] ?? '') ?>"><br>

                    <label>Middle Name:</label><br>
                    <input type="text" name="Mname" value="<?= htmlspecialchars($profEdit['Mname'] ?? '') ?>"><br>

                    <label>Email:</label><br>
                    <input type="email" name="email" style="width: 98%; height: 1rem; padding: 5px 10px;" value="<?= htmlspecialchars($profEdit['email'] ?? '') ?>"><br>

                    <label>Subjects:</label><br>
                    <div id="subject-wrapper">
                        <?php if (count($profSubjects) > 0): ?>
                            <?php foreach ($profSubjects as $subj): ?>
                                <div class="subject-field" style="margin-top: 10px;">
                                    <select name="subjects[]" required>
                                        <option value="">Select Subject</option>
                                        <?php foreach ($subject as $s): ?>
                                            <option value="<?= htmlspecialchars($s['subjects']) ?>" <?= $s['subjects'] == $subj ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($s['subjects']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="button" onclick="this.parentElement.remove()">Remove</button>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="subject-field">
                                <select name="subjects[]" required>
                                    <option value="">Select Subject</option>
                                    <?php foreach ($subject as $s): ?>
                                        <option value="<?= htmlspecialchars($s['subjects']) ?>">
                                            <?= htmlspecialchars($s['subjects']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>
                    </div>

                    <label>Department:</label><br>
                    <input type="text" name="department" value="<?= htmlspecialchars($profEdit['department'] ?? '') ?>"><br>

                    <label>Profession:</label><br>
                    <input type="text" name="prfession" value="<?= htmlspecialchars($profEdit['prfession'] ?? '') ?>"><br><br>

                    <div class="buttonDivEdit">
                        <input type="submit" value="Update Professor">
                    </div>
                </form>
            </div>
        </div>  
        <?php
            approvedSuccess();
        ?>
    </div>
    <script src="../../assets/js/hr/hrLL.js"></script>

    <script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            console.log("Image Loaded: ", e.target.result);
            document.getElementById("imageProfile").src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        console.log("No file selected");
    }
}

        function addSubject() {
    const wrapper = document.getElementById('subject-wrapper');
    const field = document.createElement('div');
    field.classList.add('subject-field');
    field.style.marginTop = '10px';

    const select = document.createElement('select');
    select.name = "subjects[]";
    select.required = true;

    const defaultOption = document.createElement('option');
    defaultOption.value = "";
    defaultOption.textContent = "Select Subject";
    select.appendChild(defaultOption);

    const subjectsFromPHP = <?= json_encode($subject) ?>;
    subjectsFromPHP.forEach(subject => {
        const option = document.createElement('option');
        option.value = subject.subjects;
        option.textContent = subject.subjects;
        select.appendChild(option);
    });

    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.textContent = 'Remove';
    removeBtn.style.marginLeft = '10px';
    removeBtn.onclick = () => field.remove();

    field.appendChild(select);
    field.appendChild(removeBtn);

    wrapper.appendChild(field);
}

function getHrNavs(){
    const sd = document.getElementById("hrNavs");
    console.log("button clicked!")
    if(sd.style.display == 'none'){
        sd.style.display = 'flex';
    }else{
        sd.style.display = 'none';
    }
}
function profileMenu(){
    const sd = document.getElementById("profileMenu");
    console.log("button clicked!")
    if(sd.style.display == 'none'){
        sd.style.display = 'flex';
    }else{
        sd.style.display = 'none';
    }
}
function menuBar(){
    const buttonMenu = document.getElementById("sideContents");
    console.log("button clicked!")
    if(buttonMenu.style.display == 'none'){
    buttonMenu.style.display = 'flex';
    }else{
    buttonMenu.style.display = 'none';
    }
}

    </script>
</body>
</html>