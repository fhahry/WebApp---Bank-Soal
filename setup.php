<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $db_host = $_POST['db_host'];
    $db_name = $_POST['db_name'];
    $db_user = $_POST['db_user'];
    $db_pasw = $_POST['db_pasw'];

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password
    $nama_lengkap = $_POST['nama_lengkap'];
    $level_user = $_POST['level_user'];

    // 1. Buat file core.php
    $core_content = "<?php 
error_reporting(0);
setlocale(LC_ALL, 'en_US.UTF-8');
date_default_timezone_set('America/New_York');

\$db_host = '{$db_host}';
\$db_name = '{$db_name}';
\$db_user = '{$db_user}';
\$db_pasw = '{$db_pasw}';

try {
    \$pdo = new PDO(\"mysql:host={\$db_host};dbname={\$db_name}\", \$db_user, \$db_pasw);
    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException \$e) {
    echo \$e->getMessage();
}
?>";

    file_put_contents('core.php', $core_content);

    // 2. Koneksi ke database
    try {
        $pdo = new PDO("mysql:host={$db_host}", $db_user, $db_pasw);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 3. Buat database jika belum ada
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
        $pdo->exec("USE `$db_name`");

        // 4. Buat tabel-tabel
        $tables_sql = [

            "CREATE TABLE IF NOT EXISTS `activity_log` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                `activity` text NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

            "CREATE TABLE IF NOT EXISTS `bank_soal` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `kode_mapel` varchar(100) DEFAULT NULL,
                `id_guru` int(2) NOT NULL,
                `soal` text DEFAULT NULL,
                `gambar_soal` text DEFAULT NULL,
                `jawab1` text DEFAULT NULL,
                `jawab2` text DEFAULT NULL,
                `jawab3` text DEFAULT NULL,
                `jawab4` text DEFAULT NULL,
                `gambarjawab1` text DEFAULT NULL,
                `gambarjawab2` text DEFAULT NULL,
                `gambarjawab3` text DEFAULT NULL,
                `gambarjawab4` text DEFAULT NULL,
                `jawaban` text DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

            "CREATE TABLE IF NOT EXISTS `tabel_kelas` (
                `id` int(5) NOT NULL AUTO_INCREMENT,
                `kode_kelas` text NOT NULL,
				`nama_kelas` text NOT NULL,
				`tingkat_kelas` text NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci",

            "CREATE TABLE IF NOT EXISTS `tabel_mapel` (
                `id` int(5) NOT NULL AUTO_INCREMENT,
                `nama_mapel` text DEFAULT NULL,
				`kode_kelas` text NOT NULL,
				`nama_kelas` text NOT NULL,
                `kode_mapel` text DEFAULT NULL,
                `id_guru` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci",

            "CREATE TABLE IF NOT EXISTS `tbl_user` (
                `id` int(2) NOT NULL AUTO_INCREMENT,
                `username` varchar(50) DEFAULT NULL,
                `password` varchar(100) DEFAULT NULL,
                `level_user` varchar(2) NOT NULL,
                `nama_lengkap` text DEFAULT NULL,
                `login_terakhir` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci"
        ];

        foreach ($tables_sql as $sql) {
            $pdo->exec($sql);
        }

        // 5. Tambahkan user admin pertama
        $stmt = $pdo->prepare("INSERT INTO tbl_user (username, password, level_user, nama_lengkap) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $password, $level_user, $nama_lengkap]);

		header("Location: index.php"); // Redirect jika bukan POST
		exit;

    } catch (PDOException $e) {
        die("Terjadi kesalahan: " . $e->getMessage());
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Setup - Bank Soal</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
</head>
<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container-xl px-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header justify-content-center">
                                    <h3 class="fw-light my-4 text-center">Setup Aplikasi Bank Soal<br>MTs. Alkhairaat Ampana Kota</h3>
                                </div>
                                <div class="card-body">
                                    <form action="" method="POST" id="setupForm">
                                        <!-- Form Pengaturan Database -->
                                        <div class="mb-5">
                                            <h3 class="mb-3">Pengaturan Database</h3>
                                            <div class="form-group">
                                                <label for="db_host">Database Host</label>
                                                <input type="text" class="form-control" id="db_host" name="db_host" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="db_name">Database Name</label>
                                                <input type="text" class="form-control" id="db_name" name="db_name" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="db_user">Database User</label>
                                                <input type="text" class="form-control" id="db_user" name="db_user" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="db_pasw">Database Password</label>
                                                <input type="password" class="form-control" id="db_pasw" name="db_pasw">
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        
                                        <!-- Form Pengaturan Administrator -->
                                        <div class="mt-5 mb-5">
                                            <h3 class="mb-3">Pengaturan Administrator</h3>
                                            <div class='form-group'>
                                                <label for='username'>Username</label>
                                                <input type='text' class='form-control' id='username' name='username' required>
                                            </div>
                                            <div class='form-group'>
                                                <label for='password'>Password</label>
                                                <input type='password' class='form-control' id='password' name='password' required>
                                            </div>
                                            <div class='form-group'>
                                                <label for='confirm_password'>Confirm Password</label>
                                                <input type='password' class='form-control' id='confirm_password' name='confirm_password' required>
                                            </div>
                                            <div class='form-group'>
                                                <label for='nama_lengkap'>Nama Lengkap</label>
                                                <input type='text' class='form-control' id='nama_lengkap' name='nama_lengkap' required>
                                            </div>
                                            <div class='form-group'>
                                                <label for='level_user'>Level User</label>
                                                <select class='form-control' id='level_user' name='level_user' required>
                                                    <option value='1'>Admin</option>
                                                </select>
                                            </div>
                                        </div>

                                        <center>
                                            <button type="submit" class="btn btn-lg btn-primary mt-3">Mulai Instalasi</button>
                                        </center>
                                    </form>
                                </div>
                            </div>
                            <div class="text-center text-light small pt-3">Copyright &copy; MTs. Alkhairaat Ampana Kota 2025</div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- JavaScript untuk Validasi Password -->
    <script>
        document.getElementById("setupForm").addEventListener("submit", function(event) {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;

            if (password !== confirmPassword) {
                alert("Password dan Confirm Password tidak cocok!");
                event.preventDefault();  // Mencegah pengiriman form
            }
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
