<?php include_once '../../auth/control.php'; $info = getUsersInfo();
    $admin_info = $info['admin_info'];
    // $getUsers = $info['getUsers'];
    // $hasPending = $info['StatusPending'];
    // $hasReject = $info['StatusRejected'];
    // $StatusApproved = $info['StatusApproved'];
    // $LoggedInHistory = $info['LoggedInHistory'];
    $departments = $info['department'];
    $subject = $info['subject'];
    ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUBJECTS MANAGEMENT</title>
    <!-- <link rel="stylesheet" href="../../assets/css/main_frontend.css?v=<?php echo time(); ?>"> -->
    <link rel="stylesheet" href="../../assets/css/hr.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <div class="container-fluid mt-0" style="width: 98%; height: 95%; padding: 0;">
                    <div class="d-flex justify-content-between align-items-center mb-2" style="margin: 1rem; width: 97.5%;">

                    <!-- Department filter -->
                    <select id="departmentFilter" class="form-select me-2" style="max-width: 200px;">
                        <option value="">All Departments</option>
                        <?php foreach ($departments as $dept): ?>
                        <option value="<?= htmlspecialchars($dept['id']) ?>"><?= htmlspecialchars($dept['department_name']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Search bar -->
                    <form class="input-group flex-grow-1 me-2" onsubmit="return false;">
                        <input type="text" name="search" id="search" class="form-control" placeholder="Search...">
                        <button class="btn btn-outline-secondary" type="button" id="buttonSearchHehe">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>

                    <!-- Add Subject button -->
                    <button type="button" style="width: 10%" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                        Add
                    </button>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive" style="height: 60vh;">
                   <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php foreach($subject as $row): ?>
                            <tr data-department-id="<?= htmlspecialchars($row['department_id']) ?>">
                                <td class="row-index"></td>
                                <td><?= htmlspecialchars($row["subject_name"]) ?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm" onclick="deleteSub(<?= $row['id'] ?>)">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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
                            <p>Are you sure you want to delete this subject?</p>
                        </div>
                        <div class="modal-footer">
                            <form action="" id="deleteForm" method="post" class="d-inline">
                            <input type="hidden" name="subjects" value="delete">
                            <input type="hidden" id="jobID" name="id" value="">
                            <button type="submit" class="btn btn-danger">Yes</button>
                            </form>
                            <button type="button" class="btn btn-secondary" onclick="CancelJob()">No</button>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Add Subject Modal -->
                <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addSubjectLabel">Add Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="../../auth/authentications.php" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="subject" value="admin">

                            <div class="mb-3">
                            <label for="departmentSelect" class="form-label">Department</label>
                            <select id="departmentSelect" name="department_id" class="form-select" required>
                                <option value="" disabled selected>Select Department</option>
                                <?php foreach ($departments as $dept): ?>
                                <option value="<?= htmlspecialchars($dept['id']) ?>"><?= htmlspecialchars($dept['department_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            </div>

                            <div class="mb-3">
                            <label for="subjectInput" class="form-label">Subject Name:</label>
                            <input type="text" class="form-control" id="subjectInput" name="subject_name" placeholder="e.g. English 101" required>
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
    <script src="../../assets/js/hr/hrJ.js"></script>

    <script>
        function deleteSub(id) {
            document.getElementById("jobID").value = id;
            var deleteModal = new bootstrap.Modal(document.getElementById('deletesubjects'));
            deleteModal.show();
            document.getElementById("jobID").value = id;
            document.getElementById("deleteForm").action = "../../auth/authentications.php?id=" + id;
        }

        function CancelJob() {
            var deleteModal = bootstrap.Modal.getInstance(document.getElementById('deletesubjects'));
            deleteModal.hide();
        }

    document.getElementById("buttonSearchHehe").addEventListener("click", function () {
        filterSubjectTable();
    });

    document.getElementById("search").addEventListener("input", function () {
        filterSubjectTable();
    });

    function filterSubjectTable() {
        const searchValue = document.getElementById("search").value.toLowerCase();
        const rows = document.querySelectorAll("tbody tr");

        rows.forEach(row => {
            const subjectText = row.cells[1].textContent.toLowerCase();
            if (subjectText.includes(searchValue)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }
const searchInput = document.getElementById('search');
  const searchBtn = document.getElementById('buttonSearchHehe');
  const departmentFilter = document.getElementById('departmentFilter');
  const rows = document.querySelectorAll('tbody tr');

  function filterTable() {
    const query = searchInput.value.trim().toLowerCase();
    const selectedDept = departmentFilter.value;

    rows.forEach(row => {
      const subjectName = row.cells[1].textContent.toLowerCase();
      const departmentId = row.getAttribute('data-department-id');

      const matchesSearch = subjectName.includes(query);
      const matchesDept = !selectedDept || departmentId === selectedDept;

      row.style.display = (matchesSearch && matchesDept) ? '' : 'none';
    });
  }

  searchInput.addEventListener('input', filterTable);
  searchBtn.addEventListener('click', filterTable);
  departmentFilter.addEventListener('change', filterTable);

  function updateRowNumbers() {
    const rows = document.querySelectorAll('tbody tr');
    let count = 0;
    rows.forEach(row => {
        if (row.style.display !== 'none') {
            count++;
            row.querySelector('.row-index').textContent = count;
        }
    });
}

// Example function that filters rows by department id
function filterByDepartment(departmentId) {
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        if (departmentId === 'all' || row.getAttribute('data-department-id') === departmentId) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    updateRowNumbers(); // Update numbering after filtering
}

// Call updateRowNumbers() initially to set numbering on page load
updateRowNumbers();
    </script>


</body>
</html>