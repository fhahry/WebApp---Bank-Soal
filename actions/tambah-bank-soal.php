<?php
session_start();
require_once '../valid.php';
require_once '../core.php';
cek_level('0'); // Hanya guru yang boleh akses

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_mapel = $_POST['kode_mapel'];
    $soal = $_POST['soal'];
    $jawab1 = $_POST['jawab1'];
    $jawab2 = $_POST['jawab2'];
    $jawab3 = $_POST['jawab3'];
    $jawab4 = $_POST['jawab4'];
    $jawaban = $_POST['jawaban'];
}

// Fungsi untuk menangani upload gambar
function uploadImage($inputName, $kode_mapel) {
    if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES[$inputName]['tmp_name'];
        $fileName = $_FILES[$inputName]['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $uploadDir = '../uploads/';
        $randomString = substr(sha1(mt_rand()), 0, 5);
        $newFileName = $kode_mapel . '-' . $randomString . '.' . $fileExtension;
        $destPath = $uploadDir . $newFileName;

        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedExts)) {
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                return $destPath; // Berhasil upload
            }
        }
        return false; // Ekstensi tidak valid atau gagal upload
    }
    return null; // Tidak ada file diupload
}

// Upload gambar soal dan jawaban
$gambar_soal = uploadImage('gambar_soal', $kode_mapel);
$gambarjawab1 = uploadImage('gambarjawab1', $kode_mapel);
$gambarjawab2 = uploadImage('gambarjawab2', $kode_mapel);
$gambarjawab3 = uploadImage('gambarjawab3', $kode_mapel);
$gambarjawab4 = uploadImage('gambarjawab4', $kode_mapel);

// Insert data ke database
try {
    $stmt = $pdo->prepare("
        INSERT INTO bank_soal (
            kode_mapel, soal, gambar_soal, 
            jawab1, gambarjawab1, 
            jawab2, gambarjawab2, 
            jawab3, gambarjawab3, 
            jawab4, gambarjawab4, 
            jawaban, id_guru
        ) VALUES (
            :kode_mapel, :soal, :gambar_soal, 
            :jawab1, :gambarjawab1, 
            :jawab2, :gambarjawab2, 
            :jawab3, :gambarjawab3, 
            :jawab4, :gambarjawab4, 
            :jawaban, :id_guru
        )
    ");

    $stmt->bindParam(':kode_mapel', $kode_mapel);
    $stmt->bindParam(':soal', $soal);
    $stmt->bindParam(':gambar_soal', $gambar_soal);
    $stmt->bindParam(':jawab1', $jawab1);
    $stmt->bindParam(':gambarjawab1', $gambarjawab1);
    $stmt->bindParam(':jawab2', $jawab2);
    $stmt->bindParam(':gambarjawab2', $gambarjawab2);
    $stmt->bindParam(':jawab3', $jawab3);
    $stmt->bindParam(':gambarjawab3', $gambarjawab3);
    $stmt->bindParam(':jawab4', $jawab4);
    $stmt->bindParam(':gambarjawab4', $gambarjawab4);
    $stmt->bindParam(':jawaban', $jawaban);
    $stmt->bindParam(':id_guru', $_SESSION['user_id']);

    if ($stmt->execute()) {
		
		$activity = '</div><div class="timeline-item-marker-indicator bg-green" bis_skin_checked="1"></div></div><div class="timeline-item-content" bis_skin_checked="1"><a class="fw-bold text-dark" href="#!">'.$_SESSION['nama_lengkap'].'</a> menambahkan soal pada Mapel <a class="fw-bold text-dark" href="#!">'.$kode_mapel.'</a></div></div>';
		
		$sql = "INSERT INTO activity_log (activity) VALUES (:activity)";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':activity', $activity, PDO::PARAM_STR); 
		$stmt->execute();		
		
        header("Location: ../dashboard.php?page=data-bank-soal&mapel=" . urlencode($kode_mapel));
        exit();
    } else {
        echo "Gagal menyimpan soal. Silakan coba lagi.";
    }
} catch (PDOException $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
}
?>
