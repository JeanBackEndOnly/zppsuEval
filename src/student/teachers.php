<?php include_once '../../auth/control.php'; $info = getUsersInfo();
    $student_info = $info['student_info'];
    $resultCount = $info['resultCount'];
    $professortCount = $info['professortCount'];

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASHBOARD</title>
    <!-- <link rel="stylesheet" href="../../assets/css/main_frontend.css?v=<?php echo time(); ?>"> -->
    <link rel="stylesheet" href="../../assets/css/hr.css?v=<?php echo time(); ?>">
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
                    <p><?php echo isset($student_info["lname"]) ? $student_info["lname"] : "nope" ." "; ?><i class="fa-solid fa-caret-down"></i></p>
                </button>
                
                <div class="profileMenu" id="profileMenu" style="display: none;">
                    <li id="borderBottom"><a href="settings.php"><p><i class="fa-solid fa-gear"></i>SETTINGS</p></a></li>
                    <li><a href="../logout.php" id="l"><p><i class="fa-solid fa-right-from-bracket"></i>LOGOUT</p></a></li>
                </div>
            </div>
        </div>
        <div class="contents">
           <div class="card p-3 m-3 shadow">
            <h4 class="mb-3">Professors in Your Department</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Professor ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Position</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $student_department_id = $student_info['department_id'];

                        // Sample query, adjust based on your actual DB structure
                        $query = "SELECT * FROM professor WHERE department_id = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->execute([$student_department_id]);
                        $professors = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($professors):
                            $count = 1;
                            foreach ($professors as $prof):
                        ?>
                            <tr>
                                <td><?= $count++ ?></td>
                                <td><?= htmlspecialchars($prof['teacherID']) ?></td>
                                <td><?= htmlspecialchars($prof['Fname'] . ' ' . $prof['Lname']) ?></td>
                                <td><?= htmlspecialchars($prof['email']) ?></td>
                                <td><?= htmlspecialchars($prof['prfession']) ?></td>
                            </tr>
                        <?php
                            endforeach;
                        else:
                        ?>
                            <tr>
                                <td colspan="5">No professors found for your department.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        </div>
    </div>
    <?php
        approvedSuccess();
    ?>
    <script src="../../assets/js/hr/hrLL.js"></script>
</body>
</html>