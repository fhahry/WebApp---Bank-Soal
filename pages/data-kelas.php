<?php require_once "valid.php"; ?>

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


<!-- Main page content-->
<div class="container-xl px-4 mt-n10">

	<!-- Example DataTable for Dashboard Demo-->
	<div class="card mb-4">
		<div class="card-header">Mata Pelajaran</div>
		<div class="card-body">
			<table class="table">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Kode Kelas</th>
						<th scope="col">Nama Kelas</th>
						<th scope="col">Tingkat</th>
						<th scope="col">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php
					try {
						$stmt = $pdo->prepare("SELECT * FROM tabel_kelas");
						$stmt->execute();
						$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
						$no = 1;
						foreach ($result as $row) {
							echo "<tr>";
							echo "<td>" . $no . "</td>";
							echo "<td>" . htmlspecialchars($row['kode_kelas']) . "</td>";
							echo "<td>" . htmlspecialchars($row['nama_kelas']) . "</td>";
							echo "<td>" . htmlspecialchars($row['tingkat_kelas']) . "</td>";
							echo "<td>
									<div class='float-end'>
										<a class='btn btn-danger btn-sm' href='dashboard.php?page=hapus-kelas&id=" . htmlspecialchars($row['id']) . "'><i data-feather='trash'></i>&nbsp;Hapus</a>&nbsp;|&nbsp;
										<a class='btn btn-warning btn-sm' href='dashboard.php?page=edit-kelas&id=" . htmlspecialchars($row['id']) . "'><i data-feather='edit'></i>&nbsp;Ubah</a>
									</div>
	   							  </td>";
							echo "</tr>";
							$no++;
						}
					} catch (PDOException $e) {
						echo "Error: " . $e->getMessage();
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
	

	<!-- Button trigger modal -->
	<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"> Tambah Kelas </button>
	
	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method="POST" action="actions/tambah-kelas.php">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Kelas</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						
						<div class="mb-3">
							<label for="ns_NamaKelas" class="form-label">Nama Kelas</label>
							<input name="ns_NamaKelas" type="text" class="form-control" id="ns_NamaKelas" value="" required>
						</div>
						
						<div class="mb-3">
							<label for="ns_TingkatKelas" class="form-label">Tingkat</label>
							<select name="ns_TingkatKelas" id="ns_TingkatKelas" class="form-select"  required>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
							</select>
						</div>
						
						<div class="mb-3">
							<label for="ns_KodeKelas" class="form-label">Kode Kelas</label>
							<input name="ns_KodeKelas" type="text" class="form-control" id="ns_KodeKelas" value="" required>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Simpan</button>
					</div>
				</form>
			</div>
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
