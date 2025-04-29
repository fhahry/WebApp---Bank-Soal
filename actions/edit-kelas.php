<?php
require_once '../valid.php';
require_once '../core.php';
cek_level('1');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id             = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $nama_kelas     = trim($_POST['ns_NamaKelas']);
    $tingkat_kelas  = isset($_POST['ns_TingkatKelas']) ? (int) $_POST['ns_TingkatKelas'] : 0;
    $kode_kelas     = trim($_POST['ns_KodeKelas']);

    if ($id > 0 && $nama_kelas !== '' && $tingkat_kelas > 0 && $kode_kelas !== '') {
        try {
            // Ambil kode_kelas lama sebelum diubah
            $stmtOld = $pdo->prepare("SELECT kode_kelas FROM tabel_kelas WHERE id = :id");
            $stmtOld->execute([':id' => $id]);
            $oldData = $stmtOld->fetch(PDO::FETCH_ASSOC);
            $old_kode_kelas = $oldData['kode_kelas'];

            // Jika kode_kelas diubah, lakukan pengecekan
            if ($kode_kelas !== $old_kode_kelas) {

                // Cek duplikat kode_kelas di kelas lain
                $cek = $pdo->prepare("SELECT COUNT(*) FROM tabel_kelas WHERE kode_kelas = :kode_kelas AND id != :id");
                $cek->execute([
                    ':kode_kelas' => $kode_kelas,
                    ':id' => $id
                ]);
                if ($cek->fetchColumn() > 0) {
                    echo "<script>
                        alert('Kode Kelas \"$kode_kelas\" sudah digunakan oleh kelas lain. Silakan gunakan kode yang berbeda.');
                        window.location.href = '../dashboard.php?page=edit-kelas&id=$id';
                    </script>";
                    exit;
                }

                // Cek apakah kode_kelas sudah digunakan di tabel_mapel
                $cekMapel = $pdo->prepare("SELECT COUNT(*) FROM tabel_mapel WHERE kode_kelas = :kode_kelas");
                $cekMapel->execute([':kode_kelas' => $old_kode_kelas]);
                if ($cekMapel->fetchColumn() > 0) {
                    echo "<script>
                        alert('Kode Kelas \"$old_kode_kelas\" sudah digunakan di tabel mata pelajaran. Kode tidak bisa diubah.');
                        window.location.href = '../dashboard.php?page=edit-kelas&id=$id';
                    </script>";
                    exit;
                }
            }

            // Update jika lolos semua validasi
            $sql = "UPDATE tabel_kelas 
                    SET nama_kelas = :nama_kelas, 
                        tingkat_kelas = :tingkat_kelas, 
                        kode_kelas = :kode_kelas 
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nama_kelas'    => $nama_kelas,
                ':tingkat_kelas' => $tingkat_kelas,
                ':kode_kelas'    => $kode_kelas,
                ':id'            => $id
            ]);

            header("Location: ../dashboard.php?page=kelas");
            exit;

        } catch (PDOException $e) {
            echo "Terjadi kesalahan saat mengupdate data: " . $e->getMessage();
        }
    } else {
        echo "Semua field wajib diisi dan valid.";
    }
} else {
    echo "Akses tidak diizinkan.";
}
?>
