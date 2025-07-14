<?php

require_once '../installer/config.php';
require_once 'login_model.php';
require_once 'login_cntrl.php';
require_once '../installer/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';
    $errors = [];

    if (empty_inputs($username, $password)) {
        $errors["empty_inputs"] = "Fill all fields!";
        $_SESSION["errors_login"] = $errors;
        header("Location: ../index.php");
        exit;
    }

    $conn = db_connect();

    try {
        $pdo = $conn;

        // Try to find admin first
        $result = get_username_Admin($pdo, $username);
        $role = null;

        if ($result) {
            $role = 'administrator';
        } else {
            // Try student if not admin
            $result = get_username_Student($pdo, $username);
            if ($result) {
                $role = 'student';
            }
        }

        if (!$result) {
            $errors["login_incorrect"] = "Incorrect username!";
        } elseif (wrong_password($password, $result["password"])) {
            $errors["login_incorrect"] = "Wrong password!";
        }

        if ($errors) {
            $_SESSION["errors_login"] = $errors;
            header("Location: ../src/index.php");
            exit;
        }

        // Create unique session ID
        $newSessionId = session_create_id();
        $sessionId = $newSessionId . "_" . $result["id"];
        session_id($sessionId);

        $_SESSION["user_id"] = $result["id"];
        $_SESSION["user_username"] = htmlspecialchars($result["username"]);
        $_SESSION["roles"] = $role;
        $_SESSION["last_regeneration"] = time();

        if ($role === "student") {
            header("Location: ../src/student/dashboard.php");
        } elseif ($role === "administrator") {
            header("Location: ../src/admin/dashboard.php");
        }

        $pdo = null;

    } catch (PDOException $e) {
        die("Query Failed: " . $e->getMessage());
    }
} else {
    header("Location: ../src/index.php");
    exit;
}
