<?php
session_start();
include __DIR__ . '/helper/conn.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// DEBUG (sementara)
if (!$email || !$password) {
    die("Email atau password kosong!");
}

$stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// CEK LOGIN
if ($user && trim($password) === trim($user['password'])) {
    $_SESSION['username'] = $user['email'];

    // REDIRECT KE ADMIN
    header("Location: admin.php");
    exit;
} else {
    echo "Login gagal!";
}