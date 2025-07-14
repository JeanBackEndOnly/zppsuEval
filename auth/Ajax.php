<?php
header('Content-Type: application/json');
include './functions.php';

$server_name = $_SERVER['SERVER_NAME'];
$is_ip_access = filter_var($server_name, FILTER_VALIDATE_IP);

$action = $_POST['action'] ?? '';

if ($action === 'save_installation_data') {
    $system_title = $_POST['system_title'];
    $system_description = $_POST['system_description'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = 'administrator';

    if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/image/upload/';
        $uploadPath = $uploadDir . basename($_FILES['site_logo']['name']);

        if (move_uploaded_file($_FILES['site_logo']['tmp_name'], $uploadPath)) {
            save_option('system_logo', basename($_FILES['site_logo']['name']));
        }
    }

    $passwordhash = password_hash($password, PASSWORD_DEFAULT);

    $user = save_user([
        'firstname' => $firstname,
        'middlename' => $middlename,
        'lastname' => $lastname,
        'email' => $email,
        'username' => $username,
        'password' => $passwordhash,
        'user_role' => $role,
    ]);

    if (!$user['is_error']) {
        save_option('system_title', $system_title);
        save_option('system_description', $system_description);
        echo json_encode([
            'success' => true,
            'message' => 'Installation Complete'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Something went wrong. Please try again.'
        ]);
    }
    exit;
}

if ($action === 'register-data') {
    $firstname = $_POST['Lname'];
    $lastname = $_POST['Fname'];
    $middlename = $_POST['Mname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (isset($_FILES['user_profile']) && $_FILES['user_profile']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/image/upload/';
        $uploadPath = $uploadDir . basename($_FILES['user_profile']['name']);
        move_uploaded_file($_FILES['user_profile']['tmp_name'], $uploadPath);
        // Optionally save the profile image name
    }

    // Add your user registration logic here if needed

    echo json_encode([
        'success' => true,
        'message' => 'User registered successfully (stub response)'
    ]);
    exit;
}


// Initialize action and check if it's set
$action = $_POST['action'] ?? ''; // Default to empty string if 'action' is not set

if ($action === 'login') {
    try {
        // Get the database connection
        $conn = db_connect();

        // Fetch username and password from POST data
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Debugging: Check if the action is 'login' and print POST data
        error_log('Action: ' . $action);
        error_log('Username: ' . $username);

        // Check in users table
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['user_role'] ?? 'student';

            echo json_encode([
                'success' => true,
                'message' => 'Welcome student!',
                'role' => 'student',
                'status' => $user['status'] ?? 'active',
            ]);
            exit;
        }

        // Check in admin table
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            session_start();
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['user_role'] = 'admin';

            echo json_encode([
                'success' => true,
                'message' => 'Welcome admin!',
                'role' => 'admin'
            ]);
            exit;
        }

        // If no match
        echo json_encode([
            'success' => false,
            'message' => 'Invalid username or password.'
        ]);
        exit;

    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
        exit;
    }
}

// If no valid action was set
echo json_encode([
    'success' => false,
    'message' => 'No valid action specified. Received action: ' . $action // Debugging output
]);
exit;


// if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'login') {
//     $conn = db_connect();

//     $username = trim($_POST['username'] ?? '');
//     $password = trim($_POST['password'] ?? '');

//     try {
//         $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
//         $stmt->execute([$username]);
//         $user = $stmt->fetch(PDO::FETCH_ASSOC);

//         if ($user && password_verify($password, $user['password'])) {
//             session_start();
//             $_SESSION['user_id'] = $user['id'];
//             $_SESSION['user_role'] = $user['user_role'] ?? 'student'; 

//             echo json_encode([
//                 'success' => true,
//                 'message' => 'Welcome student!',
//                 'role' => 'student',
//                 'status' => $user['status'] ?? 'active', 
//             ]);
//             exit;
//         }
//         $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
//         $stmt->execute([$username]);
//         $admin = $stmt->fetch(PDO::FETCH_ASSOC);

//         if ($admin && password_verify($password, $admin['password'])) {
//             session_start();
//             $_SESSION['admin_id'] = $admin['id'];
//             $_SESSION['user_role'] = 'admin';

//             echo json_encode([
//                 'success' => true,
//                 'message' => 'Welcome admin!',
//                 'role' => 'admin'
//             ]);
//             exit;
//         }

//         echo json_encode([
//             'success' => false,
//             'message' => 'Invalid username or password.'
//         ]);
//         exit;

//     } catch (PDOException $e) {
//         echo json_encode([
//             'success' => false,
//             'message' => 'Database error: ' . $e->getMessage()
//         ]);
//         exit;
//     }
// }


