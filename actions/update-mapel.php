<?php
require '../valid.php';
require '../core.php';
cek_level('1');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nama_mapel = $_POST['ns_NamaMapel']; 
    $kode_kelas = $_POST['ns_Kelas']; // Perubahan: kolom 'kelas' menjadi 'kode_kelas'
    $kode_mapel = $_POST['ns_KodeMapel']; 
    $id_guru = $_POST['ns_IdGuru'];
    
    try {
        // Cek apakah kode_mapel sudah ada di tabel bank_soal
        $cekBankSoal = $pdo->prepare("SELECT COUNT(*) FROM bank_soal WHERE kode_mapel = :kode_mapel");
        $cekBankSoal->execute([ ':kode_mapel' => $kode_mapel ]);

        if ($cekBankSoal->fetchColumn() > 0) {
            $_SESSION['error_message'] = "Kode Mapel \"$kode_mapel\" sudah digunakan di bank soal. Data mata pelajaran tidak dapat diupdate.";
            header('Location: ../dashboard.php?page=edit-mapel&id=' . $id);
            exit();
        }

        // Cek duplikat kode_mapel di mapel lain
        $cek = $pdo->prepare("SELECT COUNT(*) FROM tabel_mapel WHERE kode_mapel = :kode_mapel AND id != :id");
        $cek->execute([
            ':kode_mapel' => $kode_mapel,
            ':id' => $id
        ]);

        if ($cek->fetchColumn() > 0) {
            $_SESSION['error_message'] = "Kode Mapel \"$kode_mapel\" sudah digunakan oleh mata pelajaran lain. Silakan gunakan kode yang berbeda.";
            header('Location: ../dashboard.php?page=edit-mapel&id=' . $id);
            exit();
        }

        // Jika tidak duplikat, lanjut update
        $sql = "UPDATE tabel_mapel SET
                    nama_mapel = :nama_mapel, 
                    kode_kelas = :kode_kelas,   -- Perubahan: kolom 'kelas' menjadi 'kode_kelas'
                    kode_mapel = :kode_mapel, 
                    id_guru = :id_guru
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nama_mapel', $nama_mapel, PDO::PARAM_STR); 
        $stmt->bindParam(':kode_kelas', $kode_kelas, PDO::PARAM_STR); // Perubahan: kolom 'kelas' menjadi 'kode_kelas'
        $stmt->bindParam(':kode_mapel', $kode_mapel, PDO::PARAM_STR); 
        $stmt->bindParam(':id_guru', $id_guru, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        $_SESSION['success_message'] = "Data mata pelajaran berhasil diupdate";
        header('Location: ../dashboard.php?page=mapel');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        header('Location: ../dashboard.php?page=edit-mapel&id=' . $id);
        exit();
    }
} else {
    header('Location: ../dashboard.php?page=mapel');
    exit();
}
?>
