<?php include_once '../../auth/control.php'; 
    $info = getUsersInfo();
    $admin_info = $info['admin_info'];
    $subject = $info['subject'];
    $professors = $info['professors'];
    $department = $info['department'];
    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVALUATEE MANAGEMENT</title>
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
        <div class="contentsss">
           <div class="d-flex justify-content-between align-items-center mb-2" style="margin-top: 1rem; width: 94%;">
             <li class="list-unstyled m-0">
                <form class="input-group">
                    <input type="text" name="search" id="search" class="form-control" placeholder="Search...">
                    <button class="btn btn-outline-secondary" type="button" id="buttonSearchHehe">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </li>

                <button type="button" class="btn btn-primary" style="width: 10%;" data-bs-toggle="modal" data-bs-target="#addEvaluateeModal">
                    Add
                </button>
            </div>

           <div class="table-responsive" style="width: 95%; max-height: 80vh; overflow-y: auto;">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark text-center">
                    <tr>
                        <th scope="col">Teacher ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Profession</th>
                        <th scope="col">Subjects</th>
                        <th scope="col">Department</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    <?php foreach ($professors as $row): ?>
                        <tr>
                        <td><?= htmlspecialchars($row["teacherID"]) ?></td>
                        <td><?= htmlspecialchars($row["lname"]) ?>, <?= htmlspecialchars($row["fname"]) ?></td>
                        <td><?= htmlspecialchars($row["profession"]) ?></td>
                        <td><?= htmlspecialchars($row["subject_names"]) ?></td>
                        <td><?= htmlspecialchars($row["department_name"]) ?> Department</td>
                        <td>
                            <a href="teachersInformations.php?id=<?= $row["id"] ?>" class="btn btn-primary btn-sm mb-1 w-100">View</a>
                            <button type="button" class="btn btn-danger btn-sm w-100" onclick="openDeleteForm(<?= $row["id"] ?>)">Delete</button>
                        </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow">
                  <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body text-center">
                    <p>Are you sure you want to delete this user?</p>
                    <form action="" method="post" id="deleteForm">
                      <input type="hidden" name="delete" value="userAccount">
                      <input type="hidden" name="id" id="usersID" value="">
                      <button type="submit" class="btn btn-danger me-2">Yes, Delete</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow">
                  <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body text-center">
                    <p>Are you sure you want to delete this user?</p>
                    <form action="" method="post" id="deleteForm">
                      <input type="hidden" name="delete" value="userAccount">
                      <input type="hidden" name="id" id="usersID" value="">
                      <button type="submit" class="btn btn-danger me-2">Yes, Delete</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="addEvaluateeModal" tabindex="-1" aria-labelledby="addEvaluateeLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content shadow-lg">
                    
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title ms-auto" id="addEvaluateeLabel">Add Evaluatee</h5>
                    <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="col-12 text-end">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addSubject()">Add Another Subject</button>
                        </div>
                <form action="../../auth/authentications.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="professorsds" value="asdmin">

                    <div class="modal-body custom-modal-body">
                    <?php getErrors_signups(); ?>

                    <div class="mb-3 text-center">
                        <label for="professor_Profile">
                        <img src="../../assets/image/users.png" alt="" id="propayl" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                        </label>
                        <input type="file" name="professor_Profile" id="professor_Profile" style="display: none;" onchange="previewImage(event)">
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                        <input type="text" class="form-control" name="teacherID" placeholder="ID:" required>
                        </div>

                        <div class="col-md-4">
                        <input type="text" class="form-control" name="Lname" placeholder="Last Name" required>
                        </div>
                        <div class="col-md-4">
                        <input type="text" class="form-control" name="Fname" placeholder="First Name" required>
                        </div>
                        <div class="col-md-4">
                        <input type="text" class="form-control" name="Mname" placeholder="Middle Name" required>
                        </div>

                        <div class="col-12">
                        <input type="email" class="form-control" name="email" placeholder="E-mail" required>
                        </div>
                        <div class="col-12" id="department-wrapper">
                            <div class="mb-2 department-group" style="width: 100%;">
                              <select id="departmentSelect" name="department_name[]" class="form-select" required>
                                <option value="">Select Department</option>
                                <?php foreach ($department as $depart): ?>
                                    <option value="<?= htmlspecialchars($depart['id']) ?>">
                                    <?= htmlspecialchars($depart['department_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-12" id="subject-wrapper">
                            <div class="mb-2 subject-group" style="width: 100%;">
                               <select id="subjectSelect" name="subject_name[]" class="form-select" required>
                                <option value="">Select Subject</option>
                                <?php foreach ($subject as $subj): ?>
                                   <option value="<?= htmlspecialchars($subj['id']) ?>" data-department-id="<?= htmlspecialchars($subj['department_id']) ?>">
                                      <?= htmlspecialchars($subj['subject_name']) ?>
                                  </option>
                                <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                      <div class="col-12">
                        <input type="text" class="form-control" name="prfession" placeholder="Position" required>
                      </div>

                    </div>
                  </div>

                  <div class="modal-footer mt-3">
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

   <script>
    function cancelAction(){
        document.getElementById("hiddenForm").style.display = "none";
    }
    function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            console.log("Image Loaded: ", e.target.result);
            document.getElementById("propayl").src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        console.log("No file selected");
    }
}

   function pleasewAS() {
    document.getElementById("professorAdd").style.display = "flex";
}

   function openDeleteForm(userId) {
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();

    document.getElementById("usersID").value = userId;

    document.getElementById("deleteForm").action = "../../auth/authentications.php?id=" + userId;
    }
   const subjectsData = <?php echo json_encode($subject); ?>;


function addSubject() {
    const wrapper = document.getElementById("subject-wrapper");

    const subjectGroup = document.createElement("div");
    subjectGroup.className = "mb-2 subject-group d-flex align-items-center gap-2";

    const select = document.createElement("select");
    select.name = "subject_name[]";
    select.className = "form-select";
    select.required = true;

    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = "Select Subject";
    select.appendChild(defaultOption);

    subjectsData.forEach(subject => {
        const option = document.createElement("option");
        option.value = subject.id;
        option.textContent = subject.subject_name;
        select.appendChild(option);
    });

    const removeBtn = document.createElement("button");
    removeBtn.type = "button";
    removeBtn.className = "btn btn-danger btn-sm";
    removeBtn.textContent = "Remove";
    removeBtn.onclick = () => wrapper.removeChild(subjectGroup);

    subjectGroup.appendChild(select);
    subjectGroup.appendChild(removeBtn);

    wrapper.appendChild(subjectGroup);
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
// Search filter for table rows
document.getElementById('search').addEventListener('input', function() {
  const searchTerm = this.value.toLowerCase();
  const table = document.querySelector('.table-responsive table tbody');
  const rows = table.querySelectorAll('tr');

  rows.forEach(row => {
    const cellsText = Array.from(row.cells)
      .map(cell => cell.textContent.toLowerCase())
      .join(' ');

    if (cellsText.includes(searchTerm)) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
});

document.getElementById('departmentSelect').addEventListener('change', function () {
    const selectedDeptId = this.value;
    const subjectSelect = document.getElementById('subjectSelect');
    const allSubjects = subjectSelect.querySelectorAll('option');

    subjectSelect.value = ''; // Reset selection
    allSubjects.forEach(option => {
        if (option.value === '') {
            option.hidden = false; // Always show the "Select Subject" placeholder
            option.style.display = '';
        } else if (option.dataset.departmentId === selectedDeptId) {
            option.hidden = false;
            option.style.display = '';
        } else {
            option.hidden = true;
            option.style.display = 'none';
        }
    });
});


   </script>
</body>
</html>