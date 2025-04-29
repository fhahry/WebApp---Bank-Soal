<?php
require '../valid.php';
require '../core.php';
cek_level('1');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);

    try {
        // Pastikan data ada
        $stmt = $pdo->prepare("SELECT * FROM tabel_mapel WHERE id = ?");
        $stmt->execute([$id]);
        $mapel = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$mapel) {
            $_SESSION['error_message'] = "Data mapel tidak ditemukan.";
            header('Location: ../dashboard.php?page=mapel');
            exit();
        }

        // Cek lagi apakah sudah ada di bank soal
        $cekBankSoal = $pdo->prepare("SELECT COUNT(*) FROM bank_soal WHERE kode_mapel = ?");
        $cekBankSoal->execute([$mapel['kode_mapel']]);
        $jumlah_soal = $cekBankSoal->fetchColumn();

        if ($jumlah_soal > 0) {
            $_SESSION['error_message'] = "Mapel tidak dapat dihapus karena sudah memiliki soal.";
            header('Location: ../dashboard.php?page=mapel');
            exit();
        }

        // Hapus mapel
        $delete = $pdo->prepare("DELETE FROM tabel_mapel WHERE id = ?");
        $delete->execute([$id]);

        $_SESSION['success_message'] = "Mata pelajaran berhasil dihapus.";
        header('Location: ../dashboard.php?page=mapel');
        exit();

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        header('Location: ../dashboard.php?page=mapel');
        exit();
    }

} else {
    header('Location: ../dashboard.php?page=mapel');
    exit();
}
?>
