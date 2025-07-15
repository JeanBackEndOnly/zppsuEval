<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once '../installer/session.php';
require_once 'functions.php';
require_once 'model.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $pdo = db_connect();
    // =============================== REGISTER ===================================== //
    
  if (isset($_POST["addeemployee"]) && $_POST["addeemployee"] === "admin") {
    $fname = trim($_POST['fname'] ?? '');
    $mname = trim($_POST['mname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $year_level = trim($_POST['year_level'] ?? '');
    $section = trim($_POST['section'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $errors = [];

    if (invalid_email($email)) {
        $errors['invalid_email'] = "Invalid email address.";
    }

    if (email_registered($pdo, $email)) {
        header("Location: ../src/register.php?Email=registered");
        die();
    }

    if (username_taken($pdo, $username)) {
        header("Location: ../src/register.php?username=taken");
        die();
    }

    if (!password_notMatch($password, $confirm_password)) {
        header("Location: ../src/register.php?Password=notMatch");
        die();
    }

    if (!password_secured($password)) {
        $errors['password_length'] = "Password must be at least 8 characters.";
    }

    if (!password_security($password)) {
        $errors['password_security'] = "Password must contain uppercase letters, numbers, and special characters.";
    }

if (empty($year_level) || empty($section)) {
    $errors['invalid_year_section'] = "Year Level and Section must be selected.";
    $_SESSION['signup_errors'] = $errors;
    $_SESSION['signup_data'] = $_POST;
    header("Location: ../src/register.php");
    exit();
}
    $stmt = $pdo->prepare("SELECT id FROM year_and_section WHERE year_level = ? AND section = ? LIMIT 1");
    $stmt->execute([$year_level, $section]);
    $year_section_id = $stmt->fetchColumn();

    if (!$year_section_id) {
        $errors['invalid_year_section'] = "Year and Section not found.";
        $_SESSION['signup_errors'] = $errors;
        $_SESSION['signup_data'] = $_POST;
        header("Location: ../src/register.php");
        exit();
    }

        
    $stmt = $pdo->prepare("SELECT id FROM department WHERE department_name = ? LIMIT 1");
    $stmt->execute([$department]);
    $department_id = $stmt->fetchColumn();

    if (!$department_id) {
        $errors['invalid_department'] = "Department not found.";
        $_SESSION['signup_errors'] = $errors;
        $_SESSION['signup_data'] = $_POST;
        header("Location: ../src/register.php");
        exit();
    }

    if ($errors) {
        $_SESSION['signup_errors'] = $errors;
        $_SESSION['signup_data'] = $_POST;
        header("Location: ../src/register.php");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $user_role = 'student';

    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, user_role) VALUES (:username, :password, :email, :user_role)");
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":password", $hashedPassword);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":user_role", $user_role);
    $stmt->execute();
    $user_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO students (user_id, SchoolID, fname, mname, lname, year_section_id, department_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $username, $fname, $mname, $lname, $year_section_id, $department_id]);


    unset($_SESSION['signup_errors'], $_SESSION['signup_data']);

    header("Location: ../src/index.php?signup=success");
    exit();
}

    
if (isset($_POST["professorsds"]) && $_POST["professorsds"] === "asdmin") {
    $teacherID  = $_POST["teacherID"];
    $Lname      = $_POST["Lname"];
    $Fname      = $_POST["Fname"];
    $Mname      = $_POST["Mname"];
    $email      = $_POST["email"];
    $subjectIds = $_POST["subject_name"] ?? [];
    $departmentIds = $_POST["department_name"] ?? [];
    $prfession  = $_POST["prfession"];

    $errors = [];

    if (!is_array($subjectIds) || count($subjectIds) === 0) {
        $errors["subjects_empty"] = "Please select at least one subject.";
    }
    if (!is_array($departmentIds) || count($departmentIds) === 0) {
        $errors["department_empty"] = "Please select at least one department.";
    }

    if (isset($_FILES["professor_Profile"]) && $_FILES["professor_Profile"]["error"] === 0) {
                $profile = $_FILES["professor_Profile"];

                if (empty_image($profile)) {
                    $errors["image_Empty"] = "Please insert your profile image!";
                }

                if (fileSize_notCompatible($profile)) {
                    $errors["large_File"] = "The image must not exceed 5MB!";
                }

                $allowed_types = [
                    "image/jpeg",
                    "image/jpg",
                    "image/png"
                ];

                if (image_notCompatible($profile, $allowed_types)) {
                    $errors["file_Types"] = "Only JPG, JPEG, PNG files are allowed.";
                }

                if (!$errors) {
                    $target_dir = "../assets/image/uploads/";
                    $image_file_name = uniqid() . "-" . basename($profile["name"]);
                    $target_file = $target_dir . $image_file_name;

                    if (move_uploaded_file($profile["tmp_name"], $target_file)) {
                        $profile = $image_file_name;
                    } else {
                        $errors["upload_Error"] = "There was an error uploading your image.";
                    }
                }
            } else {
                $default_image = "../assets/image/users.png";
                $target_dir = "../assets/image/uploads/";
                $image_file_name = uniqid() . "-users.png";
                $target_file = $target_dir . $image_file_name;

                if (copy($default_image, $target_file)) {
                    $profile = $image_file_name;
                } else {
                    $errors["upload_Error"] = "Failed to assign default profile image.";
                }
            }

    // if (empty_inputs($teacherID, $Lname, $Fname, $Mname, $email, $prfession)) {
    //     $errors["empty_inputs"] = "Please fill in all fields.";
    // }

    if ($errors) {
        $_SESSION["signup_errors"] = $errors;
        header("Location: ../src/admin/teachers.php");
        exit();
    }
    
    try {
        $departmentId = $departmentIds[0]; // use department ID here

        $stmt = $pdo->prepare("INSERT INTO professor 
            (professor_profile, teacherID, lname, fname, mname, email, department_id, profession) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([$profile, $teacherID, $Lname, $Fname, $Mname, $email, $departmentId, $prfession]);

        $professorId = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO professor_subject (professor_id, subject_id) VALUES (?, ?)");
        foreach ($subjectIds as $subjectId) {
            $stmt->execute([$professorId, $subjectId]);
        }

        header("Location: ../src/admin/teachers.php?professors=success");
        exit();
    } catch (PDOException $e) {
        die("QUERY FAILED: " . $e->getMessage());
    }
}


if(isset($_POST["department"]) && $_POST["department"] == "admin"){
    $department = $_POST["department_name"];
    
    $errors=[];
try {

     if(empty($department)){
            header("Location: ../src/admin/departments.php?departments=empty");
            die();
        }else{
 $query = "INSERT INTO department (department_name) VALUES (:department_name);";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":department_name", $department);
    $stmt->execute();

    header("Location: ../src/admin/departments.php?departments=success");
    $stmt = null;
    $pdo = null;
    
    die();
    }
} catch (PDOException $e) {
     die("QUERY FAILED: " . $e->getMessage());
}
    
}

if(isset($_POST["semesterHEhe"]) && $_POST["semesterHEhe"] == "admin"){
    $school_year = $_POST["school_year"];
    $semester = $_POST["semester"];
    
    $errors=[];
try {

     if(empty($school_year) || empty($semester)){
            header("Location: ../src/admin/semester.php?semester=empty");
            die();
        }else{
            $status = "closed";
 $query = "INSERT INTO school_year_semester (school_year, semester, status) VALUES (:school_year, :semester, :status);";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":school_year", $school_year);
    $stmt->bindParam(":semester", $semester);
    $stmt->bindParam(":status", $status);
    $stmt->execute();

    header("Location: ../src/admin/semester.php?semester=success");
    $stmt = null;
    $pdo = null;
    
    die();
    }
} catch (PDOException $e) {
     die("QUERY FAILED: " . $e->getMessage());
}
    
}

if(isset($_POST["sectionHEhe"]) && $_POST["sectionHEhe"] == "admin"){
    $year_level = $_POST["year_level"];
    $section = $_POST["section"];
    
    $errors=[];
try {

     if(empty($year_level) || empty($section)){
            header("Location: ../src/admin/yearSection.php?yearSection=empty");
            die();
        }else{
 $query = "INSERT INTO year_and_section (year_level, section) VALUES (:year_level, :section);";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":year_level", $year_level);
    $stmt->bindParam(":section", $section);
    $stmt->execute();

    header("Location: ../src/admin/yearSection.php?yearSection=success");
    $stmt = null;
    $pdo = null;
    
    die();
    }
} catch (PDOException $e) {
     die("QUERY FAILED: " . $e->getMessage());
}
    
}


if(isset($_POST["semesternd"]) && $_POST["semesternd"] == "open"){
    $semesterID = $_POST["id"];

    if($semesterID){
        $query = "UPDATE school_year_semester SET status = 'open' WHERE id = :id;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $semesterID);
        if ($stmt->execute()) {
            header("Location: ../src/admin/semester.php?opens=job");
                $pdo=null;
                $stmt=null;
                die();
            } else {
                header("Location: ../src/admin/semester.php?error=open_failed");
                exit;
            }
        }else{
            echo $semesterID;
            die();
        }
    
}

if(isset($_POST["semesterrd"]) && $_POST["semesterrd"] == "close"){
    $semesterID = $_POST["id"];

    if($semesterID){
        $query = "UPDATE school_year_semester SET status = 'closed' WHERE id = :id;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $semesterID);
        if ($stmt->execute()) {
            header("Location: ../src/admin/semester.php?close=job");
                $pdo=null;
                $stmt=null;
                die();
            } else {
                header("Location: ../src/admin/semester.php?error=open_failed");
                exit;
            }
        }else{
            echo "BIlat walang ID HJAHAHAHAAH";
            die();
        }
    
}

if(isset($_POST["department"]) && $_POST["department"] == "delete"){
    $departmentID = $_POST["id"];

    if($departmentID){
        $query = "DELETE FROM department WHERE id = :id;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $departmentID);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "User inserted successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to insert user."]);
        }
        header("Location: ../src/admin/departments.php?deleteds=job");
        $pdo=null;
        $stmt=null;
        die();
    }else{
        echo $jobID;
        die();
    }
    
}


if(isset($_POST["semesterst"]) && $_POST["semesterst"] == "delete"){
    $semesterID = $_POST["id"];

    if($semesterID){
        $query = "DELETE FROM school_year_semester WHERE id = :id;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $semesterID);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "User inserted successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to insert user."]);
        }
        header("Location: ../src/admin/semester.php?deleteds=job");
        $pdo=null;
        $stmt=null;
        die();
    }else{
        // header("Location: ../src/admin/Jobs.php?no=id");
        echo $jobID;
        die();
    }
    
}


if (isset($_POST["subject"]) && $_POST["subject"] == "admin") {
    $subjectName = trim($_POST["subject_name"]);
    $departmentId = $_POST["department_id"];  // get department id from form

    if (empty($subjectName) || empty($departmentId)) {
        // Redirect if subject name or department is empty
        header("Location: ../src/admin/subjects.php?job=empty");
        die();
    }

    try {
        $query = "INSERT INTO subjects (subject_name, department_id) VALUES (:subject_name, :department_id)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":subject_name", $subjectName);
        $stmt->bindParam(":department_id", $departmentId, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: ../src/admin/subjects.php?job=success");
        $stmt = null;
        $pdo = null;
        die();
    } catch (PDOException $e) {
        die("QUERY FAILED: " . $e->getMessage());
    }
}


if(isset($_POST["subjects"]) && $_POST["subjects"] == "delete"){
    $subjectsID = $_POST["id"];

    if($subjectsID){
        $query = "DELETE FROM subjects WHERE id = :id;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $subjectsID);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "User inserted successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to insert user."]);
        }
        header("Location: ../src/admin/subjects.php?deleted=job");
        $pdo=null;
        $stmt=null;
        die();
    }else{
        // header("Location: ../src/admin/Jobs.php?no=id");
        echo $jobID;
        die();
    }
    
}

if(isset($_POST["delete"]) && $_POST["delete"] == "userAccount"){
    $usersID = $_POST["id"];

    if($usersID){
        $query = "DELETE FROM professor WHERE id = :id;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $usersID);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "User inserted successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to insert user."]);
        }
        header("Location: ../src/admin/teachers.php?deleteds=success");
        $pdo=null;
        $stmt=null;
        die();
    }else{
        header("Location: ../src/admin/teachers.php?no=id");
        die();
    }
    

}

if(isset($_POST["subject"]) && $_POST["subject"] == "subjectValue"){
    echo isset($_GET["id"]) ? $subjectID = $_GET["id"] : $subjectID = "";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['evaluation']) && $_POST['evaluation'] === 'student') {
    echo $professor_id = $_POST['professor_id'];
    $evaluatorID = $_POST['evaluator_id'];
    $subjectID  = $_POST["subjectID"];
    $feedback  = $_POST["feedback"];

    // Extract answers
    $grades = [];
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'question_') === 0) {
            $question_id = str_replace('question_', '', $key);
            $score = (int)$value;
            $grades[] = [
                'professor_id' => $professor_id,
                'questionnaire_id' => $question_id,
                'score' => $score,
                'evaluator_id' => $evaluatorID,
                'subjectID' => $subjectID,
            ];
        }
    }

    $insertGrade = $pdo->prepare("INSERT INTO grade (professor_id, questionnaire_id, score, evaluator_id, subjectID) VALUES (:professor_id, :questionnaire_id, :score, :evaluator_id, :subjectID)");
    
    $totalScore = 0;
    foreach ($grades as $grade) {
        $insertGrade->execute([
            ':professor_id' => $grade['professor_id'],
            ':questionnaire_id' => $grade['questionnaire_id'],
            ':score' => $grade['score'],
            ':evaluator_id' => $grade['evaluator_id'],
            ':subjectID' => $grade['subjectID'] 
        ]);
        $totalScore += $grade['score'];
}

    $questionCount = count($grades);
    $averageScore = $questionCount > 0 ? $totalScore / $questionCount : 0;

    // Check if total_grade record exists for the professor
    $checkTotal = $pdo->prepare("SELECT * FROM total_grade WHERE professor_id = :professor_id");
    $checkTotal->execute([':professor_id' => $professor_id]);

    if ($checkTotal->rowCount() > 0) {
        // Update existing record
        $existing = $checkTotal->fetch(PDO::FETCH_ASSOC);
        $newTotal = $existing['total_score'] + $totalScore;
        $newCount = $existing['evaluation_count'] + 1;
        $newAverage = $newTotal / ($questionCount * $newCount); // average per question over time

        $update = $pdo->prepare("UPDATE total_grade SET total_score = :total_score, evaluation_count = :count, average_score = :average WHERE professor_id = :professor_id");
        $update->execute([
            ':total_score' => $newTotal,
            ':count' => $newCount,
            ':average' => $newAverage,
            ':professor_id' => $professor_id
        ]);
    } else {
        // Insert new record
        $insert = $pdo->prepare("INSERT INTO total_grade (professor_id, average_score, total_score, evaluation_count) VALUES (:professor_id, :average_score, :total_score, :count)");
        $insert->execute([
            ':professor_id' => $professor_id,
            ':average_score' => $averageScore,
            ':total_score' => $totalScore,
            ':count' => 1
        ]);
    }

    $query = "INSERT INTO feedback (professor_id, feedback) VALUES  (:professor_id, :feedback);";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":professor_id", $professor_id);
    $stmt->bindParam(":feedback", $feedback);
    $stmt->execute();

    // Redirect or show success message
    header("Location: ../src/student/teachers.php?evaluate=success");
    exit;
}

if (isset($_POST["password"]) && $_POST["password"] == "admin") {
    isset($_GET["id"]) ? $id = $_GET["id"] : null;
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    try {
        $errors = [];

        if (!password_notMatch($confirm_password, $new_password)) {
            $errors["password_notMatch"] = "Password not match!";
        }
        if (currentPassword($pdo, $id, $current_password)) {
            $errors["password_notMatch"] = "Current Password not match!";
        }
        if ($errors) {
            $_SESSION["signup_errors"] = $errors;
            header("Location: ../src/admin/settings.php");
            die();
        }

        updatePassword($pdo, $id, $new_password);
        header("Location: ../src/admin/settings.php?password=success");

        $stmt = null;
        $pdo = null;

        die();

    } catch (PDOException $e) {
        die("QUERY FAILED: " . $e->getMessage());
    }
}

if (isset($_POST["passwordChange"]) && $_POST["passwordChange"] == "users") {
    isset($_SESSION["user_id"]) ? $id = $_SESSION["user_id"] : "null";
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    try {
        $errors = [];

        if (!password_notMatch($confirm_password, $new_password)) {
            header("Location: ../src/student/settings.php?passwordSettings=notsMatch");
            die();
        }
        if (currentPasswordUsers($pdo, $id, $current_password)) {
            header("Location: ../src/student/settings.php?CurrentPass=notMatch");
            die();
        }
        if ($errors) {
            $_SESSION["signup_errors"] = $errors;
            header("Location: ../src/student/settings.php");
            die();
        }

        updatePasswordUsers($pdo, $id, $new_password);
        header("Location: ../src/student/settings.php?Settingspassword=success");

        $stmt = null;
        $pdo = null;

        die();

    } catch (PDOException $e) {
        die("QUERY FAILED: " . $e->getMessage());
    }
}

if (isset($_POST["editProf"]) && $_POST["editProf"] == "admin") {
    isset($_GET["profIdPo"]) ? $profID = $_GET["profIdPo"] : null;
    // $professor_Profile = $_POST["professor_Profile"];
    $teacherID = $_POST["teacherID"];
    $Lname = $_POST["Lname"];
    $Fname = $_POST["Fname"];
    $Mname = $_POST["Mname"];
    $email = $_POST["email"];
    $department = $_POST["department"];
    $prfession = $_POST["prfession"];
    $subjectsArray = $_POST["subjects"] ?? [];
    $subjects = implode(',', $subjectsArray);

    $errors = [];

    // 🔄 Use the correct input name here
    if (isset($_FILES["professor_Profile"]) && $_FILES["professor_Profile"]["error"] === 0) {
        $profile = $_FILES["professor_Profile"];

        if (fileSize_notCompatible($profile)) {
            $errors["large_File"] = "The image must not exceed 5MB!";
        }

        $allowed_types = ["image/jpeg", "image/jpg", "image/png"];

        if (image_notCompatible($profile, $allowed_types)) {
            $errors["file_Types"] = "Only JPG, JPEG, PNG files are allowed.";
        }

        if (!$errors) {
            $target_dir = "../assets/image/upload/";
            $image_file_name = uniqid() . "-" . basename($profile["name"]);
            $target_file = $target_dir . $image_file_name;

            if (move_uploaded_file($profile["tmp_name"], $target_file)) {
                $professor_Profile = $image_file_name; // ✅ Set the filename for DB
            } else {
                $errors["upload_Error"] = "There was an error uploading your image.";
            }
        }
    } else {
        // No new upload, get the current profile filename from DB
        $stmtOld = $pdo->prepare("SELECT professor_Profile FROM professor WHERE id = :id");
        $stmtOld->bindParam(":id", $profID);
        $stmtOld->execute();
        $professor_Profile = $stmtOld->fetchColumn();
        $stmtOld = null;
    }

    if (empty($errors)) {
        $query = "UPDATE professor SET 
                    professor_Profile = :professor_Profile,
                    teacherID = :teacherID,
                    Lname = :Lname,
                    Fname = :Fname,
                    Mname = :Mname,
                    email = :email,
                    subjects = :subjects,
                    department = :department,
                    prfession = :prfession
                  WHERE id = :id";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":professor_Profile", $professor_Profile);
        $stmt->bindParam(":teacherID", $teacherID);
        $stmt->bindParam(":Lname", $Lname);
        $stmt->bindParam(":Fname", $Fname);
        $stmt->bindParam(":Mname", $Mname);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":subjects", $subjects);
        $stmt->bindParam(":department", $department);
        $stmt->bindParam(":prfession", $prfession);
        $stmt->bindParam(":id", $profID);

        if ($stmt->execute()) {
            header("Location: ../src/admin/teachersInformations.php?id=" . $profID . '&updatedProfessor=success');
            $stmt = null;
            $pdo = null;
            die();
        } else {
            echo "<p>Error updating professor.</p>";
        }
    } else {
        foreach ($errors as $e) {
            echo "<p style='color:red;'>$e</p>";
        }
    }
}

    // unset($_SESSION['csrf_token']);
}