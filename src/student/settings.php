<?php
    include '../../templates/Uheader.php';  
    $info = getUsersInfo();
    $student_info = $info['student_info'];
    $resultCount = $info['resultCount'];
    $professortCount = $info['professortCount'];
?>

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
                        <a href="evaluation.php"><button type="submit">Evaluate</button></a>
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
                    <p><?php echo $student_info["lname"] ." "; ?><i class="fa-solid fa-caret-down"></i></p>
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
                    <form action="../../auth/authentications.php?id=<?php echo $usersAccountID['id']; ?>" method="post">
                        <input type="hidden" name="passwordChange" value="users">
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
<?php include '../../templates/footer.php'; ?>
