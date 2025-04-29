<?php
session_start();
require 'core.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM tbl_user WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Simpan data penting ke sesi
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['level_user'] = $user['level_user'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];

        // Simpan waktu login terakhir (opsional)
        $update = $pdo->prepare("UPDATE tbl_user SET login_terakhir = NOW() WHERE id = ?");
        $update->execute([$user['id']]);
		
		$activity = '</div><div class="timeline-item-marker-indicator bg-cyan" bis_skin_checked="1"></div></div><div class="timeline-item-content" bis_skin_checked="1"><a class="fw-bold text-dark" href="#!">'.$_SESSION['nama_lengkap'].'</a> telah login ke dalam aplikasi.</div></div>';
		
		$sql = "INSERT INTO activity_log (activity) VALUES (:activity)";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':activity', $activity, PDO::PARAM_STR); 
		$stmt->execute();		

        header("Location: dashboard.php");
        exit;
    } else {
        $_SESSION['error'] = "Username atau password salah.";
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
