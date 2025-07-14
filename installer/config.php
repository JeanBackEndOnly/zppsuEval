<?php

function db_connect()
{
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'zppsu_evaluation_db';

    try {
        $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

        $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $tableQueries = [
           "CREATE TABLE IF NOT EXISTS users (
                id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(10) NOT NULL,
                password VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                user_role VARCHAR(20) NOT NULL,
                created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            );",

            "CREATE TABLE IF NOT EXISTS admin (
                id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                firstname VARCHAR(50) NOT NULL,
                middlename VARCHAR(50) NOT NULL,
                lastname VARCHAR(50) NOT NULL,
                email VARCHAR(100) NOT NULL,
                username VARCHAR(50) NOT NULL,
                password VARCHAR(255) NOT NULL,
                user_role VARCHAR(20) NOT NULL,
                created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            );",

            "CREATE TABLE IF NOT EXISTS options (
                id INT(6) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                meta_key LONGTEXT NOT NULL,
                meta_value LONGTEXT NULL
            );",

            "CREATE TABLE IF NOT EXISTS department (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    department_name VARCHAR(100) NOT NULL,
                    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                );",

                "CREATE TABLE IF NOT EXISTS subjects (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    subject_name VARCHAR(50) NOT NULL,
                    department_id INT NOT NULL,
                    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (department_id) REFERENCES department(id) ON DELETE CASCADE
                );",

            "CREATE TABLE IF NOT EXISTS professor (
                id INT AUTO_INCREMENT PRIMARY KEY,
                professor_profile VARCHAR(255),
                teacherID VARCHAR(20) NOT NULL,
                lname VARCHAR(100) NOT NULL,
                fname VARCHAR(150) NOT NULL,
                mname VARCHAR(150) NOT NULL,
                email VARCHAR(150) NOT NULL,
                department_id INT DEFAULT NULL,
                profession VARCHAR(150) NOT NULL,
                FOREIGN KEY (department_id) REFERENCES department(id) ON DELETE SET NULL
            );",


            "CREATE TABLE IF NOT EXISTS professor_subject (
                id INT AUTO_INCREMENT PRIMARY KEY,
                professor_id INT NOT NULL,
                subject_id INT NOT NULL,

                FOREIGN KEY (professor_id) REFERENCES professor(id) ON DELETE CASCADE,
                FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
            );",

            "CREATE TABLE IF NOT EXISTS school_year_semester (
                id INT AUTO_INCREMENT PRIMARY KEY,
                school_year VARCHAR(20) NOT NULL, 
                semester ENUM('1st', '2nd', 'Summer') NOT NULL,
                status VARCHAR(6) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY unique_school_year_semester (school_year, semester)
            );",

            "CREATE TABLE IF NOT EXISTS professor_school_year_semester (
                id INT AUTO_INCREMENT PRIMARY KEY,
                professor_id INT NOT NULL,
                school_year_semester_id INT NOT NULL,
                assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                FOREIGN KEY (professor_id) REFERENCES professor(id) ON DELETE CASCADE,
                FOREIGN KEY (school_year_semester_id) REFERENCES school_year_semester(id) ON DELETE CASCADE,
                UNIQUE KEY unique_professor_semester (professor_id, school_year_semester_id)
            );",

            "CREATE TABLE IF NOT EXISTS questionnaire (
                id INT AUTO_INCREMENT PRIMARY KEY,
                question_text VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );",

            "CREATE TABLE IF NOT EXISTS grade (
                id INT AUTO_INCREMENT PRIMARY KEY,
                professor_id INT NOT NULL,
                evaluator_id INT NOT NULL, 
                subject_id INT NOT NULL,
                questionnaire_id INT NOT NULL,
                school_year_semester_id INT NOT NULL,
                score TINYINT NOT NULL,  
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                FOREIGN KEY (professor_id) REFERENCES professor(id) ON DELETE CASCADE,
                FOREIGN KEY (evaluator_id) REFERENCES professor(id), 
                FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
                FOREIGN KEY (questionnaire_id) REFERENCES questionnaire(id) ON DELETE CASCADE,
                FOREIGN KEY (school_year_semester_id) REFERENCES school_year_semester(id) ON DELETE CASCADE
            );",

            "CREATE TABLE IF NOT EXISTS total_grade (
                id INT AUTO_INCREMENT PRIMARY KEY,
                professor_id INT NOT NULL,
                subject_id INT NOT NULL,
                school_year_semester_id INT NOT NULL,
                average_score DECIMAL(4,2) NOT NULL,
                total_score INT NOT NULL,
                evaluation_count INT NOT NULL,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

                FOREIGN KEY (professor_id) REFERENCES professor(id) ON DELETE CASCADE,
                FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
                FOREIGN KEY (school_year_semester_id) REFERENCES school_year_semester(id) ON DELETE CASCADE
            );",

            "CREATE TABLE IF NOT EXISTS year_and_section (
                id INT AUTO_INCREMENT PRIMARY KEY,
                year_level ENUM('1st Year', '2nd Year', '3rd Year', '4th Year') NOT NULL,
                section VARCHAR(10) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );",

            "CREATE TABLE IF NOT EXISTS professor_subject_year_section (
                id INT AUTO_INCREMENT PRIMARY KEY,
                professor_subject_id INT NOT NULL,
                year_section_id INT NOT NULL,

                FOREIGN KEY (professor_subject_id) REFERENCES professor_subject(id) ON DELETE CASCADE,
                FOREIGN KEY (year_section_id) REFERENCES year_and_section(id) ON DELETE CASCADE
            );",

            "CREATE TABLE IF NOT EXISTS students (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL UNIQUE,
                year_section_id INT DEFAULT NULL,
                department_id INT DEFAULT NULL,
                SchoolID VARCHAR(10) NOT NULL,
                fname VARCHAR(100) NOT NULL,
                mname VARCHAR(100) DEFAULT NULL,
                lname VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (year_section_id) REFERENCES year_and_section(id) ON DELETE SET NULL,
                FOREIGN KEY (department_id) REFERENCES department(id) ON DELETE SET NULL
            );",


            ];

foreach ($tableQueries as $query) {
    $pdo->exec($query);
}

// Insert evaluation questions separately
$questionInsertQuery = "
    INSERT INTO questionnaire (question_text) VALUES
    ('Demonstrates mastery of the subject matter.'),
    ('Explains the subject matter clearly and effectively.'),
    ('Stimulates students’ interest in the subject.'),
    ('Encourages student participation and critical thinking.'),
    ('Treats students with respect and fairness.'),
    ('Provides timely and constructive feedback.'),
    ('Is punctual and uses class time effectively.'),
    ('Is available for consultation and academic assistance.'),
    ('Relates subject matter to real-life situations.'),
    ('Uses appropriate and effective teaching strategies.'),
    ('Makes effective use of learning materials and resources.'),
    ('Maintains a classroom environment conducive to learning.'),
    ('Demonstrates enthusiasm and passion for teaching.'),
    ('Responds effectively to student questions and concerns.'),
    ('Encourages academic integrity and honesty.'),
    ('Demonstrates professional behavior and appearance.'),
    ('Updates content and teaching strategies regularly.'),
    ('Encourages independent learning and research.'),
    ('Clearly communicates course objectives and expectations.'),
    ('Evaluates students fairly based on clearly defined criteria.');
";

$check = $pdo->query("SELECT COUNT(*) FROM questionnaire")->fetchColumn();
if ($check == 0) {
    $pdo->exec($questionInsertQuery);
}

        return $pdo;

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }


}


$pdo = db_connect();

$pdo = null;
