<?php
require '../valid.php';
require '../core.php';
cek_level('1');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    $stmt = $pdo->prepare("SELECT kode_kelas FROM tabel_kelas WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $kelas = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$kelas) {
        die("Kelas tidak ditemukan.");
    }

    $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM tabel_mapel WHERE kode_mapel = :kode");
    $stmt2->execute([':kode' => $kelas['kode_kelas']]);
    if ($stmt2->fetchColumn() > 0) {
        die("Kelas tidak bisa dihapus karena masih digunakan.");
    }

    $stmt = $pdo->prepare("DELETE FROM tabel_kelas WHERE id = :id");
    $stmt->execute([':id' => $id]);

    header("Location: ../dashboard.php?page=kelas");
    exit;
	
} else {
	
    echo "Akses tidak valid.";
}
?>