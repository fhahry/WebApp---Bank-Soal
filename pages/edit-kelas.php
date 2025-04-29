<?php require_once "valid.php"; ?>

<?php
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Ambil data kelas
$stmt = $pdo->prepare("SELECT * FROM tabel_kelas WHERE id = ?");
$stmt->execute([$id]);
$kelas = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$kelas) {
    echo "<h4>Data tidak ditemukan.</h4>";
    exit;
}
?>

<header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
    <div class="container-xl px-4">
        <div class="page-header-content pt-4">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mt-4">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="activity"></i></div>
                        Edit Kelas
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main page content-->
<div class="container-xl px-4 mt-n10">
    <div class="card mb-4">
        <div class="card-header">Edit Kelas</div>
        <div class="card-body">
			<form method="POST" action="actions/edit-kelas.php">
				<input type="hidden" name="id" value="<?= htmlspecialchars($kelas['id']) ?>">

				<div class="mb-3">
					<label for="ns_NamaKelas" class="form-label">Nama Kelas</label>
					<input name="ns_NamaKelas" type="text" class="form-control" id="ns_NamaKelas" value="<?= htmlspecialchars($kelas['nama_kelas']) ?>" required>
				</div>

				<div class="mb-3">
					<label for="ns_TingkatKelas" class="form-label">Tingkat</label>
					<select name="ns_TingkatKelas" id="ns_TingkatKelas" class="form-select" required>
						<?php
						for ($i = 1; $i <= 12; $i++) {
							$selected = $kelas['tingkat_kelas'] == $i ? 'selected' : '';
							echo "<option value='$i' $selected>$i</option>";
						}
						?>
					</select>
				</div>

				<div class="mb-3">
					<label for="ns_KodeKelas" class="form-label">Kode Kelas</label>
					<input name="ns_KodeKelas" type="text" class="form-control" id="ns_KodeKelas" value="<?= htmlspecialchars($kelas['kode_kelas']) ?>" required>
				</div>

				<button type="submit" class="btn btn-primary">Simpan Perubahan</button>
				<a href="dashboard.php?page=kelas" class="btn btn-secondary">Batal</a>
			</form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
	const namaKelasInput = document.getElementById('ns_NamaKelas');
	const tingkatSelect = document.getElementById('ns_TingkatKelas');
	const kodeKelasInput = document.getElementById('ns_KodeKelas');

	function generateKodeKelas() {
		let namaKelas = namaKelasInput.value.trim();
		let tingkat = tingkatSelect.value;

		// Hanya huruf dan spasi
		namaKelas = namaKelas.replace(/[^a-zA-Z\s]/g, '');

		// Format ke UPPERCASE dan spasi diganti jadi '-'
		let kode = namaKelas.toUpperCase().replace(/\s+/g, '-');

		// Gabungkan dengan tingkat
		if (kode && tingkat) {
			kodeKelasInput.value = `${tingkat}-${kode}`;
		} else {
			kodeKelasInput.value = '';
		}
	}

	function sanitizeKodeKelas() {
		let kode = kodeKelasInput.value;

		// Ubah spasi jadi "-"
		kode = kode.replace(/\s+/g, '-');

		// Ganti karakter selain huruf, angka, dan "-" menjadi "-"
		kode = kode.replace(/[^a-zA-Z0-9\-]/g, '-');

		// Gabungkan "-" yang berulang jadi satu
		kode = kode.replace(/-+/g, '-');

		// Hapus "-" di awal/akhir
		kode = kode.replace(/^-+|-+$/g, '');

		kodeKelasInput.value = kode;
	}

	// Update kode otomatis saat nama kelas / tingkat berubah
	namaKelasInput.addEventListener('input', generateKodeKelas);
	tingkatSelect.addEventListener('change', generateKodeKelas);

	// Validasi/sanitasi manual jika user edit field kode kelas secara langsung
	kodeKelasInput.addEventListener('input', sanitizeKodeKelas);
});
</script>