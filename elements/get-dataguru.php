<?php
require '../valid.php'; // arahkan sesuai struktur foldermu
require '../core.php'; // arahkan sesuai struktur foldermu

// Ambil semua user dengan level_user = 0 (admin)
$stmt = $pdo->prepare("SELECT id, nama_lengkap FROM tbl_user WHERE level_user = '0'");
$stmt->execute();
$guruList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Keluarkan data sebagai JSON
header('Content-Type: application/json');
echo json_encode($guruList);
