<?php

declare(strict_types=1);
require_once '../../installer/session.php';
require_once '../../installer/config.php';
require_once '../../auth/view.php';

function getUsersInfo(){
    $pdo = db_connect();

    $query = "SELECT * FROM admin;";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $admin_info = $stmt->fetch(PDO::FETCH_ASSOC);

    isset($_SESSION["user_id"]) ? $users_id = $_SESSION["user_id"] : "potang ina";

    if(!$users_id){
        header("Location:../index.php");
    }

    $query = "SELECT * FROM users
    INNER JOIN students ON users.id = students.user_id
    INNER JOIN department ON students.department_id = department.id
    WHERE users.id = :id;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":id", $users_id);
    $stmt->execute();
    $student_info = $stmt->fetch(PDO::FETCH_ASSOC);

    isset($_GET["users_id"]) ? $id = $_GET["users_id"] : null;

    $query = "SELECT * FROM users ORDER BY users.id DESC;";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $getUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query = "SELECT * FROM subjects;";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $subject = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    $query = "SELECT * FROM department;";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $department = $stmt->fetchAll(PDO::FETCH_ASSOC); 


    $query = "SELECT * FROM school_year_semester;";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $semester = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query = "SELECT * FROM year_and_section ORDER BY year_level, section;";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $yearSection = $stmt->fetchAll(PDO::FETCH_ASSOC);


$query = "
    SELECT 
        professor.id,
        professor.teacherID,
        professor.lname,
        professor.fname,
        professor.email,
        professor.profession,
        department.department_name,
        GROUP_CONCAT(subjects.subject_name SEPARATOR ', ') AS subject_names
    FROM professor
    LEFT JOIN department ON professor.department_id = department.id
    LEFT JOIN professor_subject ON professor.id = professor_subject.professor_id
    LEFT JOIN subjects ON professor_subject.subject_id = subjects.id
    GROUP BY 
        professor.id,
        professor.teacherID,
        professor.lname,
        professor.fname,
        professor.email,
        professor.profession,
        department.department_name
";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $professors = $stmt->fetchAll(PDO::FETCH_ASSOC);


    if (isset($_SESSION["user_id"])) {
        $users_id = $_SESSION["user_id"];
    } else {
        die("User not logged in");
    }

    $query = "SELECT department_id FROM students WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $users_id]);
    $departmentID = $stmt->fetch(PDO::FETCH_ASSOC);

    $departmentIDs = $departmentID['department_id'] ?? '';

    $query = "SELECT id FROM professor WHERE department_id = :department_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":department_id", $departmentIDs);
    $stmt->execute();
    $resultProfID = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $profIDs = []; 

    foreach ($resultProfID as $hehe) {
        if (isset($hehe["id"])) {
            $profIDs[] = $hehe["id"];
            '<br>Found professor ID: ' . $hehe["id"] . '<br>';
        }
    }


    $placeholders = implode(',', array_fill(0, count($profIDs), '?'));

    $stmt = $pdo->prepare("
        SELECT p.*
        FROM professor p
        INNER JOIN professor_school_year_semester ps ON p.id = ps.professor_id 
        INNER JOIN department d ON p.department_id = d.id
        WHERE ps.professor_id IN ($placeholders)
        AND p.department_id = ?
    ");

    // $params = array_merge($profIDs, [$departmentIDs]);
    // $stmt->execute($params);

    $professorInDept = $stmt->fetchAll(PDO::FETCH_ASSOC);


 $subjects = isset($_GET["subjects"]) ? $_GET["subjects"] : "";

$professorsGet = [];

if (!empty($subjects)) {
    $query = "SELECT * FROM professor WHERE LOWER(subject_name) LIKE LOWER(:subject_name)";
    $subjectSearch = "%" . $subjects . "%";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":subject_name", $subjectSearch);
    $stmt->execute();
    $professorsGet = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    $questions = $pdo->query("SELECT * FROM questionnaire")->fetchAll(PDO::FETCH_ASSOC);

    $query = "SELECT id FROM subjects WHERE subject_name = :subject_name;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":subject_name", $subjects);
    $stmt->execute();
    $resultID = $stmt->fetch(PDO::FETCH_ASSOC);
    // ========================== student ID =========================== //
    $evaluatorID = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;

    $query = "SELECT 
            professor.id AS professor_id,
            professor.professor_Profile,
            professor.Lname,
            professor.Fname,
            professor.teacherID,
            professor.profession,
            subjects.subject_name AS subject_name,
            grade.evaluator_id
        FROM grade
        INNER JOIN professor ON grade.professor_id = professor.id
        INNER JOIN subjects ON grade.subject_id = subjects.id
        WHERE grade.evaluator_id = :evaluator_id
        GROUP BY professor.id, grade.subject_id, grade.evaluator_id";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":evaluator_id", $evaluatorID, PDO::PARAM_INT);
        $stmt->execute();
        $evaluated = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $query = "SELECT COUNT(*) FROM users;";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $resultCount = $stmt->fetchColumn();

        $query = "SELECT COUNT(*) FROM professor;";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $professortCount = $stmt->fetchColumn();
        
        isset($_GET["id"]) ? $id = $_GET["id"] : $id = null;

        $query = "SELECT DISTINCT 
            professor.id AS professor_id,
            professor.Fname,
            professor.Lname,
            professor.Mname,
            professor.teacherID,
            professor.professor_Profile,
            professor.profession,
            subjects.subject_name AS subject_name,
            total_grade.*
        FROM grade
        INNER JOIN professor ON grade.professor_id = professor.id
        INNER JOIN subjects ON grade.subject_id = subjects.id
        INNER JOIN total_grade ON professor.id = total_grade.professor_id  
        WHERE professor.id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $gradeInnformation = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // $query = "SELECT subjects FROM professor WHERE id = :id";
        // $stmt = $pdo->prepare($query);
        // $stmt->bindParam(":id", $id);
        // $stmt->execute();
        // $subjectsProf = $stmt->fetch(PDO::FETCH_ASSOC);

        isset($_SESSION["admin_id"]) ? $adminID = $_SESSION["admin_id"] : null;

        $query = "SELECT * FROM admin WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $adminID);
        $stmt->execute();
        $usersAccount = $stmt->fetch(PDO::FETCH_ASSOC);

        isset($_GET["profID"]) ? $profID = $_GET["profID"] : null;
         $query = "SELECT * FROM professor WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $profID);
        $stmt->execute();
        $profEdit = $stmt->fetch(PDO::FETCH_ASSOC);

         $query = "SELECT * FROM professor WHERE id = :id;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $professor = $stmt->fetch(PDO::FETCH_ASSOC);

        // $query = "SELECT * FROM feedback WHERE professor_id = :professor_id;";
        // $stmt = $pdo->prepare($query);
        // $stmt->bindParam(":professor_id", $id);
        // $stmt->execute();
        // $feedback = $stmt->fetchAll(PDO::FETCH_ASSOC);

        isset($_SESSION["user_id"]) ? $usersID = $_SESSION["user_id"] : null;

        // if (!empty($usersID)) {
        //     $query = "SELECT * FROM admin WHERE id = :id";
        //     $stmt = $pdo->prepare($query);
        //     $stmt->bindParam(":id", $usersID, PDO::PARAM_INT);
        //     $stmt->execute();
        //     $usersAccountID = $stmt->fetch(PDO::FETCH_ASSOC);
        // } else {
        //     $usersAccountID = false;
        // }
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $usersID);
        $stmt->execute();
        $usersAccountID = $stmt->fetch(PDO::FETCH_ASSOC);


        $query = "SELECT * FROM school_year_semester WHERE status = 'open';";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $getSemester = $stmt->fetch(PDO::FETCH_ASSOC);

    return [
        'admin_info' => $admin_info,
        'getUsers' => $getUsers,
        'subject' => $subject,
        'professors' => $professors,
        'professor' => $professor,
        'professorsGet' => $professorsGet,
        'questions' => $questions,
        'evaluated' => $evaluated,
        'resultID' => $resultID,
        'professortCount' => $professortCount,
        'gradeInnformation' => $gradeInnformation,
        'resultCount' => $resultCount,
        'profEdit' => $profEdit,
        'usersAccount' => $usersAccount,
        'department' => $department,
        'semester' => $semester,
        'yearSection' => $yearSection,
        'student_info' => $student_info,
        'professorInDept' => $professorInDept,
        'getSemester' => $getSemester,
        'usersAccountID' => $usersAccountID
    ];
}
// function getDepartment(){
//     $pdo = db_connect();
//     $query = "SELECT * FROM department;";
//     $stmt = $pdo->prepare($query);
//     $stmt->execute();
//     $department = $stmt->fetchAll(PDO::FETCH_ASSOC); 

//     return [
//         'department' => $department
//     ];
// }
function getProfActiveEval(){
    $pdo = db_connect();
    $usersID = $_SESSION['user_id'] ?? 'asdasd';
    $query = "SELECT department_id  FROM students WHERE user_id  = :user_id ;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $usersID);
    $stmt->execute();
    $departmentID = $stmt->fetch(PDO::FETCH_ASSOC);
    $getDeptId = $departmentID["department_id"];

    $query = "SELECT * FROM school_year_semester 
    INNER JOIN professor_school_year_semester ON school_year_semester.id = professor_school_year_semester.school_year_semester_id
    INNER JOIN professor ON professor_school_year_semester.professor_id = professor.id
    WHERE status = 'open' AND department_id = :department_id;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':department_id', $getDeptId);
    $stmt->execute();
    $professor = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return ['professor' => $professor];
}

