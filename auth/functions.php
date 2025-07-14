<?php
include "../installer/config.php";
// include 'control.php';


function initInstaller() {
    $pdo = db_connect(); 

    try {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE user_role = :user_role");
        $stmt->execute(['user_role' => 'administrator']);
        $admins = $stmt->fetchAll();

        $currentUrl = $_SERVER['REQUEST_URI'];
        $installerPath = "/github/zppsuEval/installer/";

        if (count($admins) === 0) {
            if ($currentUrl !== $installerPath) {
                header("Location: " . base_url() . "installer/");
                exit;
            }
        } else {
            if ($currentUrl === $installerPath) {
                header("Location: " . base_url()."SRC/");
                exit;
            }
        }

    } catch (PDOException $e) {
        die("Installer check failed: " . $e->getMessage());
    }

    $pdo = null;
}

function base_url() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
    
    $server_name = $_SERVER['SERVER_NAME']; 
    
    if (in_array($server_name, ['127.0.0.1', '::1', 'localhost'])) {
        return $protocol . '://' . $server_name . '/github/zppsuEval/'; 
    }
    
    return $protocol . '://' . $server_name . '/'; 
}


function get_current_page() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];

    return $protocol . '://' . $host . $uri;
}

function render_styles(){

    $styles =[ base_url() . 'assets/css/all.min.css', 
    base_url() . 'assets/css/custom-bs.min.css', 
    base_url() . 'assets/css/main_frontend.css', 
    base_url() . 'assets/css/main.css'];

    foreach($styles as $style){
        echo '<link rel="stylesheet" href="' . $style . '">';
    }
    
}

function render_json(){

    $json =[ base_url() . '../templates/manifest.json'];

    foreach($json as $jsons){
        echo '<link rel="manifest" href="' . $jsons . '">';
    }
    
}

function render_scripts(){

    $scripts = [base_url() . 'assets/js/jquery.min.js', 
    base_url() . 'assets/js/perfect-scrollbar.min.js', 
    base_url() . 'assets/js/smooth-scrollbar.min.js', 
    base_url() . 'assets/js/sweetalert.min.js' ,
    base_url() . 'assets/js/all.min.js' ,
    base_url() . 'assets/js/bootstrap.min.js', 
    base_url() . 'assets/js/custom-bs.js' ,
    base_url() . 'assets/js/main.js',
    base_url() . 'assets/js/hr.js' ,
    base_url() . 'assets/js/main2.js',
    base_url() . 'assets/js/service-worker.js'
    ];

    foreach($scripts as $script){
        echo '<script type="text/javascript" src="' . $script . '"></script>';
    }

}


function get_option($key) {
    try {
        $pdo = db_connect(); 

        $stmt = $pdo->prepare("SELECT meta_value FROM options WHERE meta_key = :key");
        $stmt->execute(['key' => $key]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $row['meta_value'];
        }
        return '';

    } catch (PDOException $e) {
        error_log("Database error in get_option(): " . $e->getMessage());
        return '';
    }
}

// =========================== save profile =========================== //

function save_profile($args = array(), $user_id = 0) {
    $conn = db_connect();

    try {
        if (!$user_id) {
            $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
            $stmt->execute([$args['username']]);

            if ($stmt->rowCount() > 0) {
                return array(
                    'is_error' => true,
                    'message' => 'Username already exists'
                );
            }

            $columns = implode(', ', array_keys($args));
            $placeholders = implode(', ', array_map(function($key) {
                return ":$key";
            }, array_keys($args)));

            $sql = "INSERT INTO admin ($columns) VALUES ($placeholders)";
            $stmt = $conn->prepare($sql);
            $stmt->execute($args);
        } else {
            $setPart = implode(', ', array_map(function($key) {
                return "$key = :$key";
            }, array_keys($args)));

            $sql = "UPDATE admin SET $setPart WHERE id = :id";
            $stmt = $conn->prepare($sql);

            $args['id'] = $user_id;
            $stmt->execute($args);
        }

        return array(
            'is_error' => false,
            'message' => 'Success'
        );
    } catch (PDOException $e) {
        return array(
            'is_error' => true,
            'message' => 'Database Error: ' . $e->getMessage()
        );
    }
}

// =============================== system logo ====================================//

function save_option($key, $value) {
    $conn = db_connect(); 

    if (is_array($value)) {
        $value = json_encode($value);
    }

    try {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM options WHERE meta_key = :meta_key");
        $stmt->execute([':meta_key' => $key]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            $stmt = $conn->prepare("UPDATE options SET meta_value = :meta_value WHERE meta_key = :meta_key");
        } else {
            $stmt = $conn->prepare("INSERT INTO options (meta_key, meta_value) VALUES (:meta_key, :meta_value)");
        }

        $result = $stmt->execute([
            ':meta_key'   => $key,
            ':meta_value' => $value
        ]);

        return $result;
    } catch (PDOException $e) {
        error_log("Database error in save_option: " . $e->getMessage());
        return false;
    }
}
function save_user($args = array(), $user_id = 0) {
    $conn = db_connect();

    try {
        if (!$user_id) {
            $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
            $stmt->execute([$args['username']]);

            if ($stmt->rowCount() > 0) {
                return array(
                    'is_error' => true,
                    'message' => 'Username already exists'
                );
            }

            $columns = implode(', ', array_keys($args));
            $placeholders = implode(', ', array_map(function($key) {
                return ":$key";
            }, array_keys($args)));

            $sql = "INSERT INTO admin ($columns) VALUES ($placeholders)";
            $stmt = $conn->prepare($sql);
            $stmt->execute($args);
        } else {
            $setPart = implode(', ', array_map(function($key) {
                return "$key = :$key";
            }, array_keys($args)));

            $sql = "UPDATE admin SET $setPart WHERE id = :id";
            $stmt = $conn->prepare($sql);

            $args['id'] = $user_id;
            $stmt->execute($args);
        }

        return array(
            'is_error' => false,
            'message' => 'Success'
        );
    } catch (PDOException $e) {
        return array(
            'is_error' => true,
            'message' => 'Database Error: ' . $e->getMessage()
        );
    }
}


function fileSize_notCompatible(array $profile){
    if($profile["size"] > 5 * 1024 * 1024){
        return true;
    }else{
        return false;
    }
}

function image_notCompatible(array $profile, array $allowed_types){
    if(!in_array($profile["type"], $allowed_types)){
        return true;
    }else{
        return false;
    }
}

function file_notUploaded(array $profile, string $target_file){
    if (!move_uploaded_file($profile["tmp_name"], $target_file)) {
        return true;
    }else{
        return false;
    }
}
function empty_image(array $profile){
    if(empty($profile)){
        return true;
    }else{
        return false;
    }
}

function fileSize_notCompatibles(array $prof){
    if($prof["size"] > 5 * 1024 * 1024){
        return true;
    }else{
        return false;
    }
}

function image_notCompatibles(array $prof, array $allowed_types){
    if(!in_array($prof["type"], $allowed_types)){
        return true;
    }else{
        return false;
    }
}

function file_notUploadeds(array $prof, string $target_file){
    if (!move_uploaded_file($prof["tmp_name"], $target_file)) {
        return true;
    }else{
        return false;
    }
}
function empty_images(array $prof){
    if(empty($prof)){
        return true;
    }else{
        return false;
    }
}
function empty_inputs($teacherID, $Lname, $Fname, $Mname, $email, $department, $prfession) {
    if (
        empty($Lname) || empty($Fname) || empty($Mname) ||
        empty($email) || empty($department) || empty($prfession)
    ) {
        return true;
    } else {
        return false;
    }
}
function emptyInputs($inputs) {
    foreach ($inputs as $input) {
        if (empty($input)) {
            return true;
        }
    }
    return false;
}

function addTeachers($pdo, $profile, $teacherID, $Lname, $Fname, $Mname, $email, $subjects, $department, $prfession) {
    $sql = "INSERT INTO professor (
        professor_Profile, teacherID, Lname, Fname, Mname, email, subjects, department, prfession
    ) VALUES (
        :professor_Profile, :teacherID, :Lname, :Fname, :Mname, :email, :subjects, :department, :prfession
    )";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'professor_Profile' => $profile,
        'teacherID'         => $teacherID,
        'Lname'             => $Lname,
        'Fname'             => $Fname,
        'Mname'             => $Mname,
        'email'             => $email,
        'subjects'          => $subjects, // comma-separated string
        'department'        => $department,
        'prfession'         => $prfession
    ]);
}


function admin_empty_inputs($email, $username,
$password, $confirm_password){
    if(empty($email) || empty($username) || empty($password) || empty($confirm_password)){
        return true;
        }else{
            return false;
        }
}
function idNotFound(object $pdo, string $employee_id){
    if(getID($pdo, $employee_id)){
        return false;
    }else{
        return true;
    }
}
function emptyLEaveForms($employee_id, $leave_Type, $dates){
    if(empty($employee_id) || empty($leave_Type) || empty($dates)){
        return true;
    }else{
        return false;
    }
}
function noLeave(object $pdo, string $employee_id, string $leave_Type){
    if(getLeaveCounts($pdo, $employee_id, $leave_Type) == 0){
        return true;
    }else{
        return false;
    }
}
function invalid_email(string $email){
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return true;
    }else{
        return false;
    }
}
function password_notMatch(string $confirm_password, string $hashedPassword){
    if($confirm_password !== $hashedPassword){
        return false;
    }else{
        return true;
    }
}
function username_taken(object $pdo, string $username){
    if(get_username($pdo, $username)) {
        return true;
   }else{
        return false;
   }
}

function email_registered(object $pdo, string $email){
   if(get_email($pdo, $email)) {
        return true;
   }else{
        return false;
   }
}

function wrong_username(bool|array $result){
    if(!$result){
        return true;
    }else{
        return false;
    }
}
function password_secured(string $password) {
    if (strlen($password) < 8) {
        return false;
    }else{
        return true;
    }
}

function password_security(string $password){
    if (preg_match('/[A-Z]/', $password) &&    
        preg_match('/[0-9]/', $password) &&    
        preg_match('/[\W_]/', $password)) {      

        return true;
    } else {
        return false;
    }
}

function registerLink(){
    return "../auth/authentications.php";
}




function getUser_account(object $pdo, string $email, string $username, string $password) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $userRole = "student";
    $query = "INSERT INTO users (email, username, password, user_role) VALUES (:email, :username, :password, :user_role);";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':user_role', $userRole);
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "User inserted successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to insert user."]);
    }
}
function registerStudent(
    object $pdo,
    string $email, string $username, string $password
) {
    getUser_account($pdo, $email, $username, $password);
    
}
function currentPassword($pdo, $id, $current_password){
    $hashed_password = get_passwordAdmin($pdo, $id);
    if ($hashed_password === null) {
        return true; 
    }
    if (!password_verify($current_password, $hashed_password)) {
        return true; 
    }
    return false; 
}
function currentPasswordUsers($pdo, $id, $current_password){
    $hashed_password = get_password($pdo, $id);
    if ($hashed_password === null) {
        return true; 
    }
    if (!password_verify($current_password, $hashed_password)) {
        return true; 
    }
    return false; 
}
function updatePasswordUsers(object $pdo, int $id, string $new_password){
    $hash = password_hash($new_password, PASSWORD_BCRYPT);
    $query = "UPDATE users SET password = :password WHERE id = :id;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->bindParam(":password", $hash, PDO::PARAM_STR);
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Password updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update password."]);
    }
}

function updatePassword(object $pdo, int $id, string $new_password){
    $hash = password_hash($new_password, PASSWORD_BCRYPT);
    $query = "UPDATE admin SET password = :password WHERE id = :id;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->bindParam(":password", $hash, PDO::PARAM_STR);
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Password updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update password."]);
    }
}
?>