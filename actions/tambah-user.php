<?php
session_start();
require '../valid.php';
require '../core.php';
cek_level('1');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
    $username = trim($_POST['on_Username']);
    $password = password_hash($_POST['on_Password'], PASSWORD_DEFAULT); // Hash password
    $nama_lengkap = $_POST['on_NamaLengkap'];
    $level_user = '0';

    // Cek apakah username sudah ada
    $cek = $pdo->prepare("SELECT id FROM tbl_user WHERE username = ?");
    $cek->execute([$username]);

    if ($cek->rowCount() > 0) {
        $_SESSION['pesan'] = "Username sudah terdaftar.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO tbl_user (username, password, level_user, nama_lengkap) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $password, $level_user, $nama_lengkap]);
        $_SESSION['pesan'] = "User berhasil ditambahkan.";
    }
    header("Location: ../dashboard.php?page=users");
    exit;
} else {
    header("Location: ../dashboard.php?page=users");
    exit;
}
