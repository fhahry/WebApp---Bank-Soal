<?php
require '../valid.php';
require '../core.php';
cek_level('1');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$kode_kelas = $_POST['ns_KodeKelas']; 
	$nama_kelas = $_POST['ns_NamaKelas']; 
	$tingkat_kelas = $_POST['ns_TingkatKelas']; 

	try {
		// Cek apakah kode_kelas sudah ada
		$cek = $pdo->prepare("SELECT COUNT(*) FROM tabel_kelas WHERE kode_kelas = :kode_kelas");
		$cek->bindParam(':kode_kelas', $kode_kelas, PDO::PARAM_STR);
		$cek->execute();

		if ($cek->fetchColumn() > 0) {
			// Kode sudah digunakan
			echo "<script>
				alert('Kode Kelas \"$kode_kelas\" sudah terdaftar. Silakan gunakan kode lain.');
				window.location.href = '../dashboard.php?page=kelas';
			</script>";
			exit();
		}

		// Lanjut simpan jika tidak duplikat
		$sql = "INSERT INTO tabel_kelas (
					kode_kelas, 
					nama_kelas, 
					tingkat_kelas
				) VALUES (
					:kode_kelas, 
					:nama_kelas, 
					:tingkat_kelas
				)";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':kode_kelas', $kode_kelas, PDO::PARAM_STR); 
		$stmt->bindParam(':nama_kelas', $nama_kelas, PDO::PARAM_STR); 
		$stmt->bindParam(':tingkat_kelas', $tingkat_kelas, PDO::PARAM_INT); 
		$stmt->execute();

		// Catat aktivitas
		$activity = '</div><div class="timeline-item-marker-indicator bg-orange" bis_skin_checked="1"></div></div><div class="timeline-item-content" bis_skin_checked="1"><a class="fw-bold text-dark" href="#!">ADMIN</a> menambahkan Kelas <a class="fw-bold text-dark" href="#!">'.$nama_kelas.'</a></div></div>';
		$sql = "INSERT INTO activity_log (activity) VALUES (:activity)";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':activity', $activity, PDO::PARAM_STR); 
		$stmt->execute();		

		header('Location: ../dashboard.php?page=kelas');
		exit();
	} catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
}
?>
