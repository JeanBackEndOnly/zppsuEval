<?php 
include_once '../../auth/control.php'; 
$info = getUsersInfo();
$admin_info = $info['admin_info'];
$resultCount = $info['resultCount'];
$professortCount = $info['professortCount'];
$pdo = db_connect();
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['assign'])) {
        $professor_id = $_POST['professor_id'];
        $sysem_id = $_POST['school_year_semester_id'];

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM professor_school_year_semester WHERE professor_id = ? AND school_year_semester_id = ?");
        $stmt->execute([$professor_id, $sysem_id]);
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            $insert = $pdo->prepare("INSERT INTO professor_school_year_semester (professor_id, school_year_semester_id, assigned_at) VALUES (?, ?, NOW())");
            if ($insert->execute([$professor_id, $sysem_id])) {
                $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Professor successfully assigned!'];
            } else {
                $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Error assigning professor.'];
            }
        } else {
            $_SESSION['flash_message'] = ['type' => 'warning', 'text' => 'Professor already assigned to this term.'];
        }
        header("Location: assignedProf.php");
        exit();
    }

    if (isset($_POST['delete'])) {
        $professor_id = $_POST['professor_id'];
        $sysem_id = $_POST['school_year_semester_id'];

        $delete = $pdo->prepare("DELETE FROM professor_school_year_semester WHERE professor_id = ? AND school_year_semester_id = ?");
        if ($delete->execute([$professor_id, $sysem_id])) {
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Assignment deleted successfully.'];
        } else {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Failed to delete assignment.'];
        }
        header("Location: assignedProf.php");
        exit();
    }
}

$selected_term = $_GET['filter_term'] ?? '';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>DASHBOARD</title>
    <link rel="stylesheet" href="../../assets/css/hr.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
      @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');

      .top-bar {
          display: flex;
          justify-content: flex-end;
          margin-bottom: 1rem;
          gap: 1rem;
          flex-wrap: wrap;
      }

      .filter-term-select {
          width: 60%;
          min-width: 200px;
          max-width: 400px;
      }

      .professors-table-wrapper {
          width: 90%;
          margin: 0 auto;
      }
    </style>
    <script src="../../assets/js/main.js"></script>
</head>
<body>
    <div class="sideNav">
        <div class="sideContents" id="sideContents">
            <div class="profileBox">
                <img src="../../assets/image/zppsu-logo.png" alt="pfp" id="pfpOnTop" />
                <h4>Since 1913</h4>
                <p>Zamboanga Peninsula Region IX</p>
            </div>
            <div class="menuBox">
                <ul>
                    <a href="dashboard.php" id="dashboard-a">
                        <button id="buttonDashboard">
                            <i class="fa-solid fa-house-user"></i>DASHBOARD
                        </button>
                    </a>
                    <button type="submit" onclick="getHrNavs()">
                        Faculty & Curriculum<i class="fa-solid fa-caret-down" id="iLeft"></i>
                    </button>
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
                <h3 id="userTitle">ZPPSU EVALUATION SYSTEM</h3>
            </div>
            <div class="otherButtons">
                <button type="submit" onclick="profileMenu()" id="buttonpfpmenu">
                    <img src="../../assets/image/zppsu-logo.png" alt="pfp" id="pfpOnTop" />
                    <p><?php echo $admin_info["firstname"] . " "; ?><i class="fa-solid fa-caret-down"></i></p>
                </button>

                <div class="profileMenu" id="profileMenu" style="display: none;">
                    <li id="borderBottom"><a href="settings.php"><p><i class="fa-solid fa-gear"></i>SETTINGS</p></a></li>
                    <li><a href="../logout.php" id="l"><p><i class="fa-solid fa-right-from-bracket"></i>LOGOUT</p></a></li>
                </div>
            </div>
        </div>

        <div class="contents">
            <div class="container py-5 d-flex flex-column align-items-center">
<?php if (!empty($_SESSION['flash_message'])): ?>
    <div class="container mt-3">
        <div class="alert alert-<?php echo htmlspecialchars($_SESSION['flash_message']['type']); ?> alert-dismissible fade show" role="alert" style="position: absolute; transform: translate(-50%, -50%); top: 10%; left: 50%; z-index: 3;">
            <?php echo htmlspecialchars($_SESSION['flash_message']['text']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>

                <div class="top-bar w-100 d-flex justify-content-between">
                    <li class="list-unstyled m-0">
                        <form class="input-group">
                            <input type="text" id="searchInput" class="form-control width-60" placeholder="Search...">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </form>
                    </li>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignProfessorModal">
                        Assign Faculty Member
                    </button>
                </div>
                <div class="selectSemesterHEhe">
                    <form method="GET" class="d-flex gap-2 align-items-center">
                        <select name="filter_term" class="form-select filter-term-select" onchange="this.form.submit()">
                            <option value="">-- Filter by Semester --</option>
                            <?php
                            $stmt = $pdo->query("SELECT id, school_year, semester FROM school_year_semester ORDER BY id DESC");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $selected = ($selected_term == $row['id']) ? "selected" : "";
                                echo "<option value='{$row['id']}' $selected>{$row['school_year']} - {$row['semester']}</option>";
                            }
                            ?>
                        </select>
                        <noscript><button class="btn btn-secondary">Filter</button></noscript>
                    </form>
                </div>
                <div class="modal fade" id="assignProfessorModal" tabindex="-1" aria-labelledby="assignProfessorModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="assignProfessorModalLabel">Assign Faculty Member for Evaluation</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <?php
                                    if (!empty($message)) {
                                        echo "<div class='alert " . (str_contains($message, "successfully") ? "alert-success" : (str_contains($message, "Error") ? "alert-danger" : "alert-warning")) . "'>$message</div>";
                                    }
                                    ?>

                                    <div class="mb-3">
                                        <label class="form-label">Select Department</label>
                                        <select name="department_id" class="form-select" onchange="filterProfessors(this.value)" required>
                                            <option value="">-- Choose Department --</option>
                                            <?php
                                            $stmt = $pdo->query("SELECT id, department_name FROM department ORDER BY department_name");
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='{$row['id']}'>{$row['department_name']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Select Professor</label>
                                        <select name="professor_id" class="form-select" id="professorDropdown" required>
                                            <option value="">-- Select Department First --</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Select School Year & Semester</label>
                                        <select name="school_year_semester_id" class="form-select" required>
                                            <option value="">-- Choose Term --</option>
                                            <?php
                                            $stmt = $pdo->query("SELECT id, school_year, semester FROM school_year_semester ORDER BY id DESC");
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='{$row['id']}'>{$row['school_year']} - {$row['semester']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="assign" class="btn btn-primary">Assign</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="professors-table-wrapper">
                    <div class="card shadow rounded-4 w-100" style="height: 70vh;">
                        <div class="card-header bg-secondary text-white rounded-top-4">
                            <h5 class="mb-0">Assigned Professors</h5>
                </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-hover table-striped" id="professorTable">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Faculty Name</th>
                                    <th>Term</th>
                                    <th>Date Assigned</th>
                                    <th style="width: 120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($selected_term) {
                                $stmt = $pdo->prepare("SELECT p.id AS prof_id, p.fname, p.lname, sys.id AS sysem_id, sys.school_year, sys.semester, psys.assigned_at
                                    FROM professor_school_year_semester psys
                                    JOIN professor p ON psys.professor_id = p.id
                                    JOIN school_year_semester sys ON psys.school_year_semester_id = sys.id
                                    WHERE sys.id = ?
                                    ORDER BY psys.assigned_at DESC");
                                $stmt->execute([$selected_term]);
                            } else {
                                $stmt = $pdo->query("SELECT p.id AS prof_id, p.fname, p.lname, sys.id AS sysem_id, sys.school_year, sys.semester, psys.assigned_at
                                    FROM professor_school_year_semester psys
                                    JOIN professor p ON psys.professor_id = p.id
                                    JOIN school_year_semester sys ON psys.school_year_semester_id = sys.id
                                    ORDER BY psys.assigned_at DESC");
                            }
                            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if (count($rows) > 0) {
                                foreach ($rows as $row) {
                                    $fullname = htmlspecialchars($row['lname'] . ", " . $row['fname']);
                                    $term = htmlspecialchars($row['school_year'] . " - " . $row['semester']);
                                    $dateAssigned = date("M d, Y", strtotime($row['assigned_at']));
                                    ?>
                                    <tr>
                                        <td><?php echo $fullname; ?></td>
                                        <td><?php echo $term; ?></td>
                                        <td><?php echo $dateAssigned; ?></td>
                                        <td>
                                            <form method="POST" style="display:inline-block;">
                                                <input type="hidden" name="professor_id" value="<?php echo $row['prof_id']; ?>">
                                                <input type="hidden" name="school_year_semester_id" value="<?php echo $row['sysem_id']; ?>">
                                                <!-- <button type="submit" name="edit" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button> -->
                                            </form>
                                            <form method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                                                <input type="hidden" name="professor_id" value="<?php echo $row['prof_id']; ?>">
                                                <input type="hidden" name="school_year_semester_id" value="<?php echo $row['sysem_id']; ?>">
                                                <button type="submit" name="delete" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="4" class="text-center">No assigned professors found.</td></tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/hr/hrLL.js"></script>
<script>
//    document.getElementById('departmentSelect').addEventListener('change', function () {
//     const deptId = this.value;

//     fetch('get_professors.php?department_id=' + deptId)
//         .then(response => response.json())
//         .then(data => {
//             const professorSelect = document.getElementById('professorSelect');
//             professorSelect.innerHTML = '<option value="">-- Choose Professor --</option>';
//             data.forEach(prof => {
//                 professorSelect.innerHTML += `<option value="${prof.id}">${prof.lname}, ${prof.fname}</option>`;
//             });
//         })
//         .catch(err => console.error('Fetch error:', err));
// });
function filterProfessors(departmentId) {
    const professorDropdown = document.getElementById('professorDropdown');
    professorDropdown.innerHTML = '<option>Loading...</option>';

    fetch(`../api/ajax.php?department_id=${departmentId}`)
        .then(response => response.json())
        .then(data => {
            professorDropdown.innerHTML = '<option value="">-- Choose Professor --</option>';
            if (data.length > 0) {
                data.forEach(prof => {
                    const option = document.createElement('option');
                    option.value = prof.id;
                    option.textContent = `${prof.lname}, ${prof.fname}`;
                    professorDropdown.appendChild(option);
                });
            } else {
                professorDropdown.innerHTML = '<option value="">No professors found</option>';
            }
        });
}

document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const table = document.getElementById("professorTable");
    const rows = table.querySelectorAll("tbody tr");

    searchInput.addEventListener("input", function () {
        const query = this.value.toLowerCase().trim();

        rows.forEach(row => {
            const cells = row.querySelectorAll("td");
            const match = Array.from(cells).some(cell =>
                cell.textContent.toLowerCase().includes(query)
            );

            row.style.display = match ? "" : "none";
        });
    });
});

</script>
