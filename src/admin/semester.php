<?php include_once '../../auth/control.php'; $info = getUsersInfo();
    $admin_info = $info['admin_info'];
    // $getUsers = $info['getUsers'];
    // $hasPending = $info['StatusPending'];
    // $hasReject = $info['StatusRejected'];
    // $StatusApproved = $info['StatusApproved'];
    // $LoggedInHistory = $info['LoggedInHistory'];
    // $count = $info['count'];
    $semester = $info['semester'];
    ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semesters MANAGEMENT</title>
    <!-- <link rel="stylesheet" href="../../assets/css/main_frontend.css?v=<?php echo time(); ?>"> -->
    <link rel="stylesheet" href="../../assets/css/hr.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

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
                <div class="container-fluid mt-0" style="width: 98%; height: 95%; padding
                0;">

                <div class="d-flex justify-content-between align-items-center mb-2" style="margin-top: 1rem; width: 99%;">
                    <li class="list-unstyled m-0">
                        <form class="input-group">
                            <input type="text" name="search" id="search" class="form-control" placeholder="Search...">
                            <button class="btn btn-outline-secondary" type="button" id="buttonSearchHehe">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </form>
                    </li>
                   <button type="button" class="btn btn-primary" style="width: 10%;" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                        Add
                    </button>
                </div>

                <div class="table-responsive" style="height: 60vh;">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">School Year</th>
                                <th scope="col">Semester</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php foreach($semester as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row["id"]) ?></td>
                                    <td><?= htmlspecialchars($row["school_year"]) ?></td>
                                    <td><?= htmlspecialchars($row["semester"]) ?></td>
                                    <td><?= htmlspecialchars($row["status"]) ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="openSemesterModal(<?= $row['id'] ?>)">
                                            <i class="fa-solid fa-unlock"></i>
                                        </button>
                                         <button style="margin: 0 10px;" class="btn btn-danger btn-sm" onclick="CloseSemsterModaL(<?= $row['id'] ?>)">
                                            <i class="fa-solid fa-lock"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteSub(<?= $row['id'] ?>)">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- IPEN SEMESTER -->
                <div class="modal fade" id="openSemester" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <form action="" id="openForm" method="post">
                            <input type="hidden" id="semesterID" name="id" value="">
                            <input type="hidden" name="semesternd" value="open">
                            <div class="modal-header">
                            <h5 class="modal-title" style="color: #000;">Openning Semester</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to Open This Semester?</p>
                            </div>
                            <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
                <!-- cLOSE SEMESTER -->
                <div class="modal fade" id="closeSemester" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <form action="" id="closeForm" method="post">
                            <input type="hidden" id="semesterIDClose" name="id" value="">
                            <input type="hidden" name="semesterrd" value="close">
                            <div class="modal-header">
                            <h5 class="modal-title" style="color: #000;">Closing Semester</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to Close This Semester?</p>
                            </div>
                            <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
                <!-- Delete confirmation modal -->
                <div id="deletesubjects" class="modal fade" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Confirm Deletion</h5>
                                <button type="button" class="btn-close" onclick="CancelJob()" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this Semester?</p>
                            </div>
                            <div class="modal-footer">
                                <form action="" id="deleteForm" method="post" class="d-inline">
                                    <input type="hidden" name="semesterst" value="delete">
                                    <input type="hidden" id="jobID" name="id" value="">
                                    <button type="submit" class="btn btn-danger">Yes</button>
                                </form>
                                <button type="button" class="btn btn-secondary" onclick="CancelJob()">No</button>
                            </div>
                        </div>
                    </div>
                </div>

                    
                </div>
                <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="addSubjectLabel">Add Year and Semester</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="../../auth/authentications.php" method="post">
                            <div class="modal-body">
                            <input type="hidden" name="semesterHEhe" value="admin">
                            <div class="mb-3">
                                <label for="subjectInput" class="form-label">Year and Semester</label>
                                <input type="text" class="form-control" id="schoolYearInput" name="school_year" placeholder="e.g. 2021" required>
                                <input type="text" class="form-control" id="semesterInput" name="semester" placeholder="e.g. 1st" required>
                            </div>
                            </div>
                            <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Add</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            approvedSuccess();
        ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/hr/hrJ.js"></script>

    <script>
        function openSemesterModal(id) {
            console.log("Open modal with id:", id);
            const modal = new bootstrap.Modal(document.getElementById('openSemester'));
            modal.show();
            document.getElementById("semesterID").value = id;
            document.getElementById("openForm").action = "../../auth/authentications.php";
        }
        function CloseSemsterModaL(id) {
            console.log("Open modal with id:", id);
            const modal = new bootstrap.Modal(document.getElementById('closeSemester'));
            modal.show();
            document.getElementById("semesterIDClose").value = id;
            document.getElementById("closeForm").action = "../../auth/authentications.php";
        }

       function deleteSub(id) {
            const deleteModal = new bootstrap.Modal(document.getElementById('deletesubjects'));
            deleteModal.show();
            document.getElementById("jobID").value = id;
            document.getElementById("deleteForm").action = "../../auth/authentications.php?id=" + id;
        }

     

        function CancelJob() {
            const deleteModalEl = document.getElementById('deletesubjects');
            const openModalEl = document.getElementById('openSemester');

            const deleteModal = bootstrap.Modal.getInstance(deleteModalEl);
            const openModal = bootstrap.Modal.getInstance(openModalEl);

            if (deleteModal) deleteModal.hide();
            if (openModal) openModal.hide();
        }

        document.getElementById("search").addEventListener("keypress", function (event) {
            if (event.key === "Enter") {
                event.preventDefault();
                filterSemesterTable();
            }
        });

        document.getElementById("buttonSearchHehe").addEventListener("click", filterSemesterTable);

        function filterSemesterTable() {
            const input = document.getElementById("search").value.toLowerCase();
            const rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                const schoolYear = row.cells[1].textContent.toLowerCase();
                const semester = row.cells[2].textContent.toLowerCase();
                row.style.display = (schoolYear.includes(input) || semester.includes(input)) ? "" : "none";
            });
        }

    </script>


</body>
</html>