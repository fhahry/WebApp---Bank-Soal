<?php
require '../valid.php';
require '../core.php';
cek_level('1');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_mapel = $_POST['ns_NamaMapel']; 
    $kelas = $_POST['ns_Kelas']; 
    $kode_mapel = $_POST['ns_KodeMapel']; 
    $id_guru = $_POST['ns_IdGuru'];

    try {
        // Cek apakah kode_mapel sudah ada di tabel_mapel
        $cek = $pdo->prepare("SELECT COUNT(*) FROM tabel_mapel WHERE kode_mapel = :kode_mapel");
        $cek->execute([':kode_mapel' => $kode_mapel]);

        if ($cek->fetchColumn() > 0) {
            echo "<script>
                alert('Kode Mapel \"$kode_mapel\" sudah digunakan. Silakan gunakan kode yang berbeda.');
                window.location.href = '../dashboard.php?page=tambah-mapel';
            </script>";
            exit;
        }

        // Ambil nama kelas berdasarkan kode kelas
        $stmt = $pdo->prepare("SELECT nama_kelas FROM tabel_kelas WHERE kode_kelas = :kode_kelas");
        $stmt->execute([':kode_kelas' => $kelas]);
        $nama_kelas = $stmt->fetchColumn();

        if (!$nama_kelas) {
            echo "<script>
                alert('Kelas tidak ditemukan. Silakan pilih kelas yang valid.');
                window.location.href = '../dashboard.php?page=tambah-mapel';
            </script>";
            exit;
        }

        // Insert data mapel
        $sql = "INSERT INTO tabel_mapel (
                    nama_mapel, 
                    kode_kelas, 
                    nama_kelas, 
                    kode_mapel, 
                    id_guru
                ) VALUES (
                    :nama_mapel, 
                    :kode_kelas, 
                    :nama_kelas, 
                    :kode_mapel, 
                    :id_guru
                )";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nama_mapel', $nama_mapel, PDO::PARAM_STR); 
        $stmt->bindParam(':kode_kelas', $kelas, PDO::PARAM_STR); 
        $stmt->bindParam(':nama_kelas', $nama_kelas, PDO::PARAM_STR); 
        $stmt->bindParam(':kode_mapel', $kode_mapel, PDO::PARAM_STR); 
        $stmt->bindParam(':id_guru', $id_guru, PDO::PARAM_STR);
        $stmt->execute();

        // Catat aktivitas
        $activity = '</div><div class="timeline-item-marker-indicator bg-orange"></div></div><div class="timeline-item-content"><a class="fw-bold text-dark" href="#!">ADMIN</a> menambahkan Mata Pelajaran <a class="fw-bold text-dark" href="#!">'.$nama_mapel.'</a></div></div>';

        $sql = "INSERT INTO activity_log (activity) VALUES (:activity)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':activity', $activity, PDO::PARAM_STR); 
        $stmt->execute();        

        header('location: ../dashboard.php?page=mapel');
        exit();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
