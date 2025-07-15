<?php 
require_once '../../installer/session.php'; 
require_once '../../installer/config.php'; 
require_once '../../auth/view.php';
include_once '../../auth/control.php';  

$info = getUsersInfo();
$student_info = $info['student_info'];
$getSemester = $info['getSemester'];
$professortCount = $info['professortCount'];
$admin_info = $info['admin_info'];
$resultCount = $info['resultCount'];
$subject = $info['subject'];
$professors = $info['professors'];
$department = $info['department'];
$professorInDept = $info['professorInDept'];
$prof = getProfActiveEval();
$professor = $prof["professor"];
$profInfo = getProfProfile();
$facultyInfo = $profInfo["facultyInfo"];
$semester = $info['semester'];
$departments = $info['department'];
$yearSection = $info['yearSection'];

    $CurrentPass = false;
    $Email = false;
    $Password = false;
    $Settingspassword = false;
    $usernameNotmatch = false;
    $deleteds = false;
    $professorsAdded = false;
    $AddQuestion = false;
    $DeleteQuestion = false;
    $AddCriteria = false;
    $DeleteCriteria = false;
    if(isset($_GET['CurrentPass']) && $_GET['CurrentPass'] === 'notMatch'){
        $CurrentPass = true;
    }elseif(isset($_GET['Email']) && $_GET['Email'] === 'registered'){
        $Email = true;
    }elseif(isset($_GET['passwordSettings']) && $_GET['passwordSettings'] === 'notsMatch'){
        $Password = true;
    }elseif(isset($_GET['Settingspassword']) && $_GET['Settingspassword'] === 'success'){
        $Settingspassword = true;
    }elseif(isset($_GET['username']) && $_GET['username'] === 'notMatch'){
        $usernameNotmatch = true;
    }elseif(isset($_GET['deleteds']) && $_GET['deleteds'] === 'success'){
        $deleteds = true;
    }elseif(isset($_GET['professors']) && $_GET['professors'] === 'success'){
        $professorsAdded = true;
    }elseif(isset($_GET['AddQuestion']) && $_GET['AddQuestion'] === 'success'){
        $AddQuestion = true;
    }elseif(isset($_GET['DeleteQuestion']) && $_GET['DeleteQuestion'] === 'success'){
        $DeleteQuestion = true;
    }elseif(isset($_GET['AddCriteria']) && $_GET['AddCriteria'] === 'success'){
        $AddCriteria = true;
    }elseif(isset($_GET['DeleteCriteria']) && $_GET['DeleteCriteria'] === 'success'){
        $DeleteCriteria = true;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASHBOARD</title>
    <link rel="stylesheet" href="../../assets/css/user.css?v=<?php echo time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
      @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
    </style>
    <!-- <script src="../../assets/js/main.js"></script> -->
    <script>
        const CurrentPass = <?php echo json_encode($CurrentPass); ?>;
        const Email = <?php echo json_encode($Email); ?>;
        const Password = <?php echo json_encode($Password); ?>;
        const Settingspassword = <?php echo json_encode($Settingspassword); ?>;
        const usernameNotmatch = <?php echo json_encode($usernameNotmatch); ?>;
        const deleteds = <?php echo json_encode($deleteds); ?>;
        const professorsAdded = <?php echo json_encode($professorsAdded); ?>;
        const AddQuestion = <?php echo json_encode($AddQuestion); ?>;
        const DeleteQuestion = <?php echo json_encode($DeleteQuestion); ?>;
        const AddCriteria = <?php echo json_encode($AddCriteria); ?>;
        const DeleteCriteria = <?php echo json_encode($DeleteCriteria); ?>;
    </script>
</head>
<body>