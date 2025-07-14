<?php

declare(strict_types=1);


function signup_inputs(){  
    $pdo = db_connect();
    echo '<div class="AddEmployee container py-5 d-flex justify-content-center" id="AddEmployees">';

echo '<form action="../auth/authentications.php" id="registerForm" method="post" enctype="multipart/form-data" class="needs-validation" novalidate style="max-width: 620px; width: 100%;">';

echo '<input type="hidden" name="addeemployee" value="admin">';

echo '<div class="CenterInfo card shadow p-4" style="width: fit-content; max-width: 100%;">';
getErrors_signups();

echo '<div class="back d-flex align-items-center mb-3">';
echo '<button type="button" id="bb" class="btn btn-outline-secondary me-2"><a href="index.php" class="text-decoration-none text-dark"><i class="fa-solid fa-arrow-left" style="font-size: 20px;"></i></a></button>';
echo '<h3 id="h3WithBack" class="mb-0">Sign-Up</h3>';
echo '</div>';

echo '<ul class="list-unstyled">';

echo '<li class="mb-3">';
echo '<input type="text" class="form-control w-100" id="schoolID" name="username" placeholder="School ID" value="' . ($_SESSION["signup_data"]["username"] ?? '') . '">';
echo '</li>';

echo '<div class="row justify-content-between mb-3 gx-3 gy-2" style="width: 100%;">';
    echo '<div class="col-md-4 col-sm-6">';
    echo '<input type="text" style="width: 100%;" class="form-control" id="lname" name="lname" placeholder="Last Name" value="' . ($_SESSION["signup_data"]["lname"] ?? '') . '">';
    echo '</div>';

    echo '<div class="col-md-4 col-sm-6">';
    echo '<input type="text" style="width: 100%;" class="form-control" id="fname" name="fname" placeholder="First Name" value="' . ($_SESSION["signup_data"]["fname"] ?? '') . '">';
    echo '</div>';

    echo '<div class="col-md-4 col-sm-6">';
    echo '<input type="text" style="width: 100%;" class="form-control" id="mname" name="mname" placeholder="Middle Name" value="' . ($_SESSION["signup_data"]["mname"] ?? '') . '">';
    echo '</div>';

echo '</div>';


echo '<select class="form-control w-100 mb-3" id="year_level" name="year_level" required>';
    echo '<option disabled selected>Select Year Level</option>';
    $stmt = $pdo->query("SELECT DISTINCT year_level FROM year_and_section ORDER BY year_level");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $selected = ($_SESSION["signup_data"]["year_level"] ?? '') == $row['year_level'] ? 'selected' : '';
        echo '<option value="' . htmlspecialchars($row['year_level']) . '" ' . $selected . '>' . htmlspecialchars($row['year_level']) . '</option>';
    }
echo '</select>';

echo '<select class="form-control w-100 mb-3" id="section" name="section" required>';
    echo '<option disabled selected>Select Section</option>';
    
  $stmt = $pdo->query("SELECT DISTINCT section FROM year_and_section ORDER BY section");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
       $selected = ($_SESSION["signup_data"]["section"] ?? '') == $row['section'] ? 'selected' : '';
        echo '<option value="' . htmlspecialchars($row['section']) . '" ' . $selected . '>' . htmlspecialchars($row['section']) . '</option>';
   }
echo '</select>';


echo '<li class="mb-3">';
echo '<select class="form-control w-100" id="department" name="department">';
echo '<option disabled selected>Select Department</option>';

$stmt = $pdo->prepare("SELECT * FROM department ORDER BY department_name ASC");
$stmt->execute();
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($departments as $dept) {
    $selected = ($_SESSION["signup_data"]["department"] ?? '') == $dept['department_name'] ? 'selected' : '';
    echo '<option value="' . htmlspecialchars($dept['department_name']) . '" ' . $selected . '>' . htmlspecialchars($dept['department_name']) . '</option>';
}
echo '</select>';
echo '</li>';

echo '<li class="mb-3">';
echo '<input type="email" class="form-control w-100" id="email" name="email" placeholder="E-mail" value="' . ($_SESSION["signup_data"]["email"] ?? '') . '">';
echo '</li>';

echo '<li class="mb-3">';
echo '<input type="password" class="form-control w-100" id="password" name="password" placeholder="Password" required>';
echo '</li>';

echo '<li class="mb-3">';
echo '<input type="password" class="form-control w-100" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>';
echo '</li>';

echo '<div class="text-center">';
echo '<button type="submit" id="naniButton" class="btn btn-primary w-100">SIGN UP</button>';
echo '</div>';

echo '</ul>';
echo '</div>'; 
echo '</form>';
echo '</div>';

}


function getErrors_signups(){
    if(isset($_SESSION['signup_errors'])){
        $errors = $_SESSION['signup_errors'];
        
        foreach($errors as $error){
            echo '<div class="errors-register">';
                echo '<p><li>*' . $error . '</li></p>';
            echo '</div>';
        }

        unset($_SESSION['signup_errors']);
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('professorAdd').style.display = 'flex';
            });
          </script>";
    }
}

function adminEmployee(){
    if(isset($_SESSION['admin_Errors'])){
        $errors = $_SESSION['admin_Errors'];
        echo '<div class="errors-admin">';
        foreach($errors as $error){
            
                echo '<ul class="errors-register">';
                    echo '<p><li>*' . $error . '</li></p>';
                echo '</ul>';
            
        }
        echo '</div>';
        unset($_SESSION['admin_Errors']);
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('addmamhen').style.display = 'flex';
            });
          </script>";
    }
}
function approvedSuccess() {
    $messages = [
        "approve"  => "Account Approved successfully!",
        "rejected" => "Account Rejected successfully!",
        "deleteds"  => "Account deleted successfully!",
        "add"      => "Employee Account Added successfully!",
        "password" => "Password Changed successfully!",
         "yearSection"      => [
            "success" => "Year and Section Added successfully!",
            "empty"   => "Empty Input, Try again!"
        ],
         "semester"      => [
            "success" => "Year and Semester Added successfully!",
            "empty"   => "Empty Input, Try again!"
        ],
        "departments"      => [
            "success" => "Subject Added successfully!",
            "empty"   => "Empty Input, Try again!"
        ],
        "job"      => [
            "success" => "Subject Added successfully!",
            "empty"   => "Empty Input, Try again!"
        ],
        "admin"    => "Register successfully!",
        "update"   => "Profile Updated successfully!",
        "signup"   => "Register successfully!",
        "leave"    => "Employee Leave Successfully!",
        "rejectedL"    => "Employee Leave Rejected Successfully!",
        "leaveAksep"    => "Employee Leave Accepted Successfully!",
        "leaveE"    => "Leave Request Successfully!",
        "subjects"    => "Subject Added Successfully!",
        "nani"    => "Failed Sdding Subject!",
        "evaluate"    => "Evaluated Successfully!",
        "professors"    => "Professor added successfully!",
        "updatedProfessor"    => "Information updated successfully!",
        "close"  => [
            "job" => "Year and Semester Closed successfully!"
        ],
        "opens"  => [
            "job" => "Year and Semester Opened successfully!"
        ],
        "deletedss"  => [
            "job" => "Year and Semester deleted successfully!"
        ],
        "deleteds"  => [
            "job" => "Department deleted successfully!"
        ],
        "deleted"  => [
            "job" => "Subject deleted successfully!"
        ]
    ];

    foreach ($messages as $key => $value) {
        if (is_array($value)) {
            foreach ($value as $subkey => $msg) {
                if (isset($_GET[$key]) && $_GET[$key] === $subkey) {
                    echo '<div class="' . ($subkey === "empty" ? 'unsuccess-registers' : 'success-register') . '" id="sideSuccess">';
                    echo "<p>$msg</p>";
                    echo '</div>';
                }
            }
        } else {
            if (isset($_GET[$key]) && $_GET[$key] === "success") {
                $customClass = ($key === "admin" || $key === "signup") ? "success-registers" : "success-register";
                echo '<div class="' . $customClass . '" id="sideSuccess">';
                echo "<p>$value</p>";
                echo '</div>';
            }
        }
    }

    if (!empty(array_intersect(array_keys($_GET), array_keys($messages)))) {
        echo '<script>
            setTimeout(function() {
                var successDiv = document.getElementById("sideSuccess");
                if(successDiv) {
                    successDiv.style.display = "none";
                }
            }, 2000);
        </script>';
    }
}


