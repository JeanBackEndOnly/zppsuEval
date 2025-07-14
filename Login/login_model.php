<?php

declare(strict_types=1);

function get_username_Student($pdo, $username) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_username_Admin($pdo, $username) {
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = :username");
    $stmt->execute(['username' => $username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
