<?php
require_once "valid.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    $stmt = $pdo->prepare("SELECT * FROM tabel_mapel WHERE id = ?");
    $stmt->execute([$id]);
    $mapel = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$mapel) {
        $_SESSION['error_message'] = "Data mapel tidak ditemukan.";
        header('Location: dashboard.php?page=mapel');
        exit();
    }

    // Cek apakah mapel ini sudah digunakan di bank soal
    $cekBankSoal = $pdo->prepare("SELECT COUNT(*) FROM bank_soal WHERE kode_mapel = ?");
    $cekBankSoal->execute([$mapel['kode_mapel']]);
    $jumlah_soal = $cekBankSoal->fetchColumn();

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
	<div class="container-xl px-4">
		<div class="page-header-content pt-4">
			<div class="row align-items-center justify-content-between">
				<div class="col-auto mt-4">
					<h1 class="page-header-title">
						<div class="page-header-icon"><i data-feather="activity"></i></div>
						Hapus Mata Pelajaran
					</h1>
				</div>
			</div>
		</div>
	</div>
</header>

<div class="container-xl px-4 mt-n10">
	<div class="card mb-4">
		<div class="card-header">Mata Pelajaran</div>
		<div class="card-body">

            <?php if ($jumlah_soal > 0): ?>
                <div class="alert alert-warning" role="alert">
                    <strong>Perhatian!</strong><br>
                    Mata pelajaran <strong><?= htmlspecialchars($mapel['nama_mapel']) ?></strong> tidak dapat dihapus karena sudah memiliki soal di Bank Soal.
                </div>

                <div class="mt-4">
                    <a href="dashboard.php?page=mapel" class="btn btn-secondary">Kembali</a>
                </div>

            <?php else: ?>
                <div class="alert alert-danger" role="alert">
                    <strong>Apakah Anda yakin ingin menghapus mata pelajaran ini?</strong><br>
                    Nama Mapel: <?= htmlspecialchars($mapel['nama_mapel']) ?><br>
                    Kode Mapel: <?= htmlspecialchars($mapel['kode_mapel']) ?><br>
                </div>

                <form method="POST" action="actions/hapus-mapel.php">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($mapel['id']) ?>">
                    <div class="d-flex justify-content-between">
                        <a href="dashboard.php?page=mapel" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </div>
                </form>
            <?php endif; ?>
		
		</div>
	</div>
</div>
