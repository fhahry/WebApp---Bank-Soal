<?php require_once "valid.php"; ?>

<header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
	<div class="container-xl px-4">
		<div class="page-header-content pt-4">
			<div class="row align-items-center justify-content-between">
				<div class="col-auto mt-4">
					<h1 class="page-header-title">
						<div class="page-header-icon"><i data-feather="activity"></i></div>
						Mata Pelajaran
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
						<th scope="col">Kode</th>
						<th scope="col">Mata Pelajaran</th>
						<th scope="col">Kelas</th>
						<th scope="col">Guru Mapel</th>
						<th scope="col">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php
					try {
						// Query untuk mengambil data mapel dengan nama kelas dan nama guru
						$stmt = $pdo->prepare("
							SELECT 
								m.id, m.kode_mapel, m.nama_mapel, k.nama_kelas, m.id_guru, u.nama_lengkap AS nama_guru
							FROM 
								tabel_mapel m
							LEFT JOIN 
								tabel_kelas k ON m.kode_kelas = k.kode_kelas
							LEFT JOIN 
								tbl_user u ON m.id_guru = u.id
						");
						$stmt->execute();

						// Ambil data hasil query
						$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
						
						$no = 1;
						// Menampilkan data
						foreach ($result as $row) {
							echo "<tr>";
							echo "<td>" . $no . "</td>";
							echo "<td>" . htmlspecialchars($row['kode_mapel']) . "</td>";
							echo "<td>" . htmlspecialchars($row['nama_mapel']) . "</td>";
							echo "<td>" . htmlspecialchars($row['nama_kelas']) . "</td>";
							echo "<td>" . htmlspecialchars($row['nama_guru'] ?? 'Tidak ditemukan') . "</td>";
							echo "<td>
										<a class='btn btn-danger btn-sm' href='dashboard.php?page=hapus-mapel&id=" . htmlspecialchars($row['id']) . "'><i data-feather='trash'></i></a>&nbsp;|&nbsp;
										<a class='btn btn-warning btn-sm' href='dashboard.php?page=edit-mapel&id=" . htmlspecialchars($row['id']) . "'><i data-feather='edit'></i></a>
								  </td>";
							echo "</tr>";
							$no++;
						}
					} catch (PDOException $e) {
						// Menampilkan pesan error jika ada masalah saat mengambil data
						echo "<tr><td colspan='6' class='text-center text-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
	

	<!-- Button trigger modal -->
	<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"> Tambah Mata Pelajaran </button>
	
	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method="POST" action="actions/tambah-mapel.php">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Mata Pelajaran</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						
						<div class="mb-3">
							<label for="ns_NamaMapel" class="form-label">Nama Mapel</label>
							<input name="ns_NamaMapel" type="text" class="form-control" id="ns_NamaMapel" value="" pattern="[A-Za-z\s]+" required>
						</div>

						<?php
						$kelasList = [];
						try {
							// Ambil data kode_kelas dan nama_kelas dari tabel_kelas
							$stmt = $pdo->query("SELECT kode_kelas, nama_kelas FROM tabel_kelas ORDER BY tingkat_kelas ASC, nama_kelas ASC");
							$kelasList = $stmt->fetchAll(PDO::FETCH_ASSOC);
						} catch (PDOException $e) {
							echo "Gagal mengambil data kelas: " . $e->getMessage();
						}
						?>

						<div class="mb-3">
							<label for="ns_Kelas" class="form-label">Kelas</label>
							<select name="ns_Kelas" id="ns_Kelas" class="form-select" required>
								<option value="">-- Pilih Kelas --</option>
								<?php foreach ($kelasList as $kelas): ?>
									<option value="<?= htmlspecialchars($kelas['kode_kelas']) ?>">
										<?= htmlspecialchars($kelas['nama_kelas']) ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
						
						<div class="mb-3">
							<label for="ns_KodeMapel" class="form-label">Kode Mapel</label>
							<input name="ns_KodeMapel" type="text" class="form-control" id="ns_KodeMapel" value="" required>
						</div>
						
						<div class="mb-3">
							<label for="ns_IdGuru" class="form-label">Pilih Guru Mapel</label>
							<select name="ns_IdGuru" id="ns_IdGuru" class="form-select" required>
								<!-- Guru options will be dynamically populated -->
								<?php
								$guruList = [];
								try {
									$stmt = $pdo->query("SELECT id, nama FROM tabel_guru ORDER BY nama ASC");
									$guruList = $stmt->fetchAll(PDO::FETCH_ASSOC);
								} catch (PDOException $e) {
									echo "Gagal mengambil data guru: " . $e->getMessage();
								}

								foreach ($guruList as $guru) {
									echo '<option value="' . $guru['id'] . '">' . htmlspecialchars($guru['nama']) . '</option>';
								}
								?>
							</select>
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
	const namaMapelInput = document.getElementById('ns_NamaMapel');
	const kelasSelect = document.getElementById('ns_Kelas');
	const kodeMapelInput = document.getElementById('ns_KodeMapel');

	function generateKodeMapel() {
		let namaMapel = namaMapelInput.value.trim();
		let kelas = kelasSelect.value;

		// Hanya huruf dan spasi
		namaMapel = namaMapel.replace(/[^a-zA-Z\s]/g, '');

		// Format ke UPPERCASE, spasi jadi '-'
		let kode = namaMapel.toUpperCase().replace(/\s+/g, '-');

		// Gabungkan dengan kelas
		if (kode && kelas) {
			kodeMapelInput.value = `${kode}-${kelas}`;
		} else {
			kodeMapelInput.value = '';
		}
	}

	// Update setiap kali input berubah
	namaMapelInput.addEventListener('input', generateKodeMapel);
	kelasSelect.addEventListener('change', generateKodeMapel);
});


document.addEventListener('DOMContentLoaded', function () {
	const selectGuru = document.getElementById('ns_IdGuru');

	// Ambil data guru dari server
	fetch('elements/get-dataguru.php')
		.then(response => response.json())
		.then(data => {
			// Kosongkan dulu opsi
			selectGuru.innerHTML = '<option value="">-- Pilih Guru --</option>';

			// Tambahkan opsi berdasarkan hasil fetch
			data.forEach(guru => {
				const option = document.createElement('option');
				option.value = guru.id;
				option.textContent = guru.nama_lengkap;
				selectGuru.appendChild(option);
			});
		})
		.catch(error => {
			console.error('Gagal memuat data guru:', error);
			selectGuru.innerHTML = '<option value="">Gagal memuat data</option>';
		});
});
</script>

