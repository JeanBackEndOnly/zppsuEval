<?php include '../auth/functions.php'; require_once '../installer/session.php';  require_once '../auth/view.php';  ?>
<?php 
initInstaller();
    $username = false;
    $Email = false;
    $Password = false;
    $signup = false;
    $usernameNotmatch = false;
    if(isset($_GET['username']) && $_GET['username'] === 'taken'){
        $username = true;
    }elseif(isset($_GET['Email']) && $_GET['Email'] === 'registered'){
        $Email = true;
    }elseif(isset($_GET['Password']) && $_GET['Password'] === 'notMatch'){
        $Password = true;
    }elseif(isset($_GET['signup']) && $_GET['signup'] === 'success'){
        $signup = true;
    }elseif(isset($_GET['username']) && $_GET['username'] === 'notMatch'){
        $usernameNotmatch = true;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo get_option('system_description')?>">
    <title><?php echo get_option('system_title')?></title>
    <?php render_styles()?>
    <link rel="stylesheet" href="../assets/css/index.css?v=<?php echo time() ?>">
    <link rel="manifest" href="../webApp/manifest.json">
    <link rel="stylesheet" href="../assets/css/all.min.css?v=<?php echo time() ?>">
    <link rel="stylesheet" href="../assets/css/custom-bs.min.css?v=<?php echo time() ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        var base_url = '<?php
            echo base_url();
        ?>';
         const username = <?php echo json_encode($username); ?>;
         const Email = <?php echo json_encode($Email); ?>;
         const Password = <?php echo json_encode($Password); ?>;
         const signup = <?php echo json_encode($signup); ?>;
         const usernameNotmatch = <?php echo json_encode($usernameNotmatch); ?>;
    </script>


</head>
    <body class="bg-light-300">