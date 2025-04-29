<?php require_once "valid.php"; ?>

<header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
	<div class="container-xl px-4">
		<div class="page-header-content pt-4">
			<div class="row align-items-center justify-content-between">
				<div class="col-auto mt-4">
					<h1 class="page-header-title">
						<div class="page-header-icon"><i data-feather="activity"></i></div>
						<?php 
						$kodemapel = $_GET['mapel'];

						try {
							$stmt = $pdo->prepare("SELECT * FROM tabel_mapel WHERE kode_mapel=:kodemapel AND id_guru=:id_guru LIMIT 1");
							$stmt->bindParam(':kodemapel', $kodemapel, PDO::PARAM_STR); 
							$stmt->bindParam(':id_guru', $_SESSION['user_id'], PDO::PARAM_INT); 
							$stmt->execute();

							$r = $stmt->fetch(PDO::FETCH_ASSOC);

							if ($r) {
								$id_mapel = $r['id'];
								$nama_mapel = $r['nama_mapel'];
								$kelas = $r['nama_kelas'];
								$kode_mapel = $r['kode_mapel'];

								echo $nama_mapel . " - Kelas " . $kelas;
								
							} else {
								echo "Data tidak ditemukan.";
							}

						} catch (PDOException $e) {
							echo "Error: " . $e->getMessage();
						}
						?>
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
		<div class="card-body">
		
			<div class="float-start">
				<h3>
					<?php
					try {
						$stmt = $pdo->prepare("SELECT COUNT(*) as jumlah_soal FROM bank_soal WHERE kode_mapel = :kode_mapel");
						$stmt->bindParam(':kode_mapel', $kodemapel, PDO::PARAM_STR);
						$stmt->execute();
						$result = $stmt->fetch(PDO::FETCH_ASSOC);
						$jumlah_soal = $result['jumlah_soal'];
						echo "Jumlah soal: " . $jumlah_soal;
					} catch (PDOException $e) {
						echo "Error: " . $e->getMessage();
					}
					
					?>
				</h3>
			</div>
			
			<a href="?page=tambah-bank-soal&mapel=<?php echo $kodemapel; ?>" class="btn btn-primary float-end" > Tambah Soal </a>
		
			<table class="table">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Soal</th>
						<th scope="col">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php
					try {
						$stmt = $pdo->prepare("SELECT * FROM bank_soal WHERE kode_mapel=:kode_mapel");
						$stmt->bindParam(':kode_mapel', $kodemapel, PDO::PARAM_STR);
						$stmt->execute();

						$no = 1;
						$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

						foreach ($result as $row) {
							echo "<tr>";
							echo "<td>" . $no . "</td>";

							echo "<td>";
								echo "<img src='" . $row['gambar_soal'] . "' width='100vw'/><br>";
								echo $row['soal'];
								echo "<table class='no-border'>";
									// Pilihan A
									echo "<tr><td class='p-2'";
									if ($row['jawaban'] == "A") {
										echo "style='background-color:#e30059; color: #ffffff;'";
									}
									echo "><strong>A.&nbsp;&nbsp;</strong></td><td><img src='" . $row['gambarjawab1'] . "' width='80vw'/><br>" . $row['jawab1'] . "</td></tr>";
									
									// Pilihan B
									echo "<tr><td class='p-2' ";
									if ($row['jawaban'] == "B") {
										echo "style='background-color:#e30059; color: #ffffff;'";
									}
									echo "><strong>B.&nbsp;&nbsp;</strong></td><td><img src='" . $row['gambarjawab2'] . "' width='80vw'/><br>" . $row['jawab2'] . "</td></tr>";
									
									// Pilihan C
									echo "<tr><td class='p-2' ";
									if ($row['jawaban'] == "C") {
										echo "style='background-color:#e30059; color: #ffffff;'";
									}
									echo "><strong>C.&nbsp;&nbsp;</strong></td><td><img src='" . $row['gambarjawab3'] . "' width='80vw'/><br>" . $row['jawab3'] . "</td></tr>";
									
									// Pilihan D
									echo "<tr><td class='p-2' ";
									if ($row['jawaban'] == "D") {
										echo "style='background-color:#e30059; color: #ffffff;'";
									}
									echo "><strong>D.&nbsp;&nbsp;</strong></td><td><img src='" . $row['gambarjawab4'] . "' width='80vw'/><br>" . $row['jawab4'] . "</td></tr>";
								echo "</table>";
							echo "</td>";

							echo "	<td>
										<div class='btn-group float-end' role='group'>
											<a href='javascript:void(0);' class='btn btn-danger' onclick='confirmDelete(".$row['id'].")'>Hapus</a>
											<a href='?page=edit-bank-soal&mapel=".$kodemapel."&id=".$row['id']."' class='btn btn-warning'>Edit</a>
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
	
</div>

<script>
function confirmDelete(id) {
  const confirmation = confirm("Apakah Anda yakin ingin menghapus soal ini?");
  if (confirmation) {
    // Jika pengguna memilih "Yes", lanjutkan penghapusan
    window.location.href = 'actions/hapus-bank-soal.php?id=' + id;
  }
  // Jika pengguna memilih "No", tidak melakukan apa-apa
}
</script>
