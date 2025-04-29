<?php
session_start();
require '../valid.php';
require '../core.php';

cek_level('0'); // Hanya guru yang boleh akses

// Ambil ID dari URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_soal = $_GET['id'];

    try {
        // Ambil data soal dari database berdasarkan ID
        $stmt = $pdo->prepare("SELECT * FROM bank_soal WHERE id = :id AND id_guru = :id_guru");
        $stmt->bindParam(':id', $id_soal, PDO::PARAM_INT);
        $stmt->bindParam(':id_guru', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Ambil nama file gambar dari data soal
            $gambar_soal = $row['gambar_soal'];
            $gambarjawab1 = $row['gambarjawab1'];
            $gambarjawab2 = $row['gambarjawab2'];
            $gambarjawab3 = $row['gambarjawab3'];
            $gambarjawab4 = $row['gambarjawab4'];

            // Tentukan direktori upload gambar
            $uploadDir = '../uploads/';

            // Hapus gambar soal jika ada
            if ($gambar_soal && file_exists($uploadDir . $gambar_soal)) {
                unlink($uploadDir . $gambar_soal);
            }

            // Hapus gambar jawaban jika ada
            if ($gambarjawab1 && file_exists($uploadDir . $gambarjawab1)) {
                unlink($uploadDir . $gambarjawab1);
            }
            if ($gambarjawab2 && file_exists($uploadDir . $gambarjawab2)) {
                unlink($uploadDir . $gambarjawab2);
            }
            if ($gambarjawab3 && file_exists($uploadDir . $gambarjawab3)) {
                unlink($uploadDir . $gambarjawab3);
            }
            if ($gambarjawab4 && file_exists($uploadDir . $gambarjawab4)) {
                unlink($uploadDir . $gambarjawab4);
            }

            // Hapus data soal dari database
            $stmtDelete = $pdo->prepare("DELETE FROM bank_soal WHERE id = :id");
            $stmtDelete->bindParam(':id', $id_soal, PDO::PARAM_INT);

            if ($stmtDelete->execute()) {
                // Redirect setelah berhasil menghapus soal
                header("Location: ../dashboard.php?page=data-bank-soal&mapel=" . urlencode($row['kode_mapel']));
                exit();
            } else {
                echo "Gagal menghapus soal. Silakan coba lagi.";
            }
        } else {
            echo "Soal tidak ditemukan.";
        }
    } catch (PDOException $e) {
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
} else {
    echo "ID soal tidak valid.";
}
?>
