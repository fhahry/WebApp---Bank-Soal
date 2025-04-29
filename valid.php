<?php
@session_start();

if ( !isset($_SESSION['user_id']) && !isset($_SESSION['username']) && !isset($_SESSION['level_user']) && !isset($_SESSION['nama_lengkap']) ) {
	header("Location: index.php");
	exit;
}

function cek_level($levelWajib) {
    if (!isset($_SESSION['level_user']) || $_SESSION['level_user'] !== $levelWajib) {
        echo "Akses ditolak. Halaman ini hanya untuk level tertentu.";
        exit;
    }
}
