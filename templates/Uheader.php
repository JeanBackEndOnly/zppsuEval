<?php 
require_once '../../installer/session.php'; 
require_once '../../auth/view.php';
include_once '../../auth/control.php';  

$info = getUsersInfo();
$student_info = $info['student_info'];
$getSemester = $info['getSemester'];
$professortCount = $info['professortCount'];
$admin_info = $info['admin_info'];
$resultCount = $info['resultCount'];
$student_info = $info['student_info'];
$professortCount = $info['professortCount'];
$getSemester = $info['getSemester'];
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

    <style>
      @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
    </style>
    <script src="../../assets/js/main.js"></script>
</head>
<body>