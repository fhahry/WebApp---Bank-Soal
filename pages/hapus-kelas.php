<?php require_once "valid.php"; ?>
<?php
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    echo "ID kelas tidak valid.";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM tabel_kelas WHERE id = :id");
$stmt->execute([':id' => $id]);
$kelas = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$kelas) {
    echo "Data kelas tidak ditemukan.";
    exit;
}

$stmt2 = $pdo->prepare("SELECT COUNT(*) FROM tabel_mapel WHERE kode_mapel = :kode_kelas");
$stmt2->execute([':kode_kelas' => $kelas['kode_kelas']]);
$digunakan = $stmt2->fetchColumn() > 0;
?>

<header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
	<div class="container-xl px-4">
		<div class="page-header-content pt-4">
			<div class="row align-items-center justify-content-between">
				<div class="col-auto mt-4">
					<h1 class="page-header-title">
						<div class="page-header-icon"><i data-feather="activity"></i></div>
						Kelas
					</h1>
				</div>
			</div>
		</div>
	</div>
</header>

<div class="container-xl px-4 mt-n10">
	<div class="card mb-4">
		<div class="card-header">Kelas</div>
		<div class="card-body">

			<table class="table table-bordered">
				<tr>
					<th>Nama Kelas</th>
					<td><?= htmlspecialchars($kelas['nama_kelas']) ?></td>
				</tr>
				<tr>
					<th>Tingkat</th>
					<td><?= htmlspecialchars($kelas['tingkat_kelas']) ?></td>
				</tr>
				<tr>
					<th>Kode Kelas</th>
					<td><?= htmlspecialchars($kelas['kode_kelas']) ?></td>
				</tr>
			</table>
			
			<?php if ($digunakan): ?>
				<div class="alert alert-warning">
					<strong>Perhatian!</strong> Kelas ini tidak bisa dihapus karena masih digunakan pada tabel mata pelajaran.
				</div>
				<a href="dashboard.php?page=daftar-kelas" class="btn btn-secondary">Kembali</a>
			<?php else: ?>
				<div class="alert alert-danger">
					<strong>Apakah Anda yakin?</strong> Kelas ini akan dihapus secara permanen.
				</div>
				<form method="POST" action="actions/hapus-kelas.php">
					<input type="hidden" name="id" value="<?= $kelas['id'] ?>">
					<button type="submit" class="btn btn-danger">Ya, Hapus Kelas</button>
					<a href="dashboard.php?page=daftar-kelas" class="btn btn-secondary">Batal</a>
				</form>
			<?php endif; ?>
		
		</div>
	</div>
</div>
