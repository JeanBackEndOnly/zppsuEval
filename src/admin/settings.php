<?php include_once '../../auth/control.php'; $info = getUsersInfo();
    $admin_info = $info['admin_info'];
    $resultCount = $info['resultCount'];
    $professortCount = $info['professortCount'];
    $usersAccount = $info['usersAccount'];
    ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SETTINGS</title>
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
             <div class="chnage-password">
                <?php
                echo '<div class="change">';
                    getErrors_signups();
                echo '</div>';
                
                ?>
            
                <h3>CHANGE PASSWORD HERE</h3>
                    <form action="../../auth/authentications.php?id=<?php echo $usersAccount['id']; ?>" method="post">
                        <input type="hidden" name="password" value="admin">
                        <input type="password" name="current_password" placeholder="Current Password:" required>
                        <input type="password" name="new_password" placeholder="New Password:" required>
                        <input type="password" name="confirm_password" placeholder="Confirm Password: " required>
                        <div class="buttonDiv">
                            <button>Change password</button>
                        </div>
                    </form>

            </div>
        </div>  
        <?php
            approvedSuccess();
        ?>
    </div>
    <script src="../../assets/js/hr/hrLL.js"></script>
</body>
</html>