<?php require_once "valid.php"; ?>

<header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
	<div class="container-xl px-4">
		<div class="page-header-content pt-4">
			<div class="row align-items-center justify-content-between">
				<div class="col-auto mt-4">
					<h1 class="page-header-title">
						<div class="page-header-icon"><i data-feather="activity"></i></div>
						Beranda
					</h1>
					<div class="page-header-subtitle">Bank Soal MTs. Alkhairaat Ampana Kota</div>
				</div>
				<div class="col-12 col-xl-auto mt-4">
					<div class="input-group input-group-joined border-0" style="width: 20rem">
						<span class="input-group-text">
							<i class="text-primary" data-feather="calendar"></i>
						</span>
						<input 
							type="text" 
							class="form-control ps-0 bg-white text-center text-primary" 
							value="<?php 
								setlocale(LC_TIME, 'id_ID.utf8');
								echo strftime('%A, %d %B %Y');
							?>" 
							readonly 
						/>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
<!-- Main page content-->
<div class="container-xl px-4 mt-n10">
	<div class="row">
	
	
		<div class="col-md-6 mb-4">
			<div class="card h-100">
				<div class="card-body h-100 p-5">
					<div class="row align-items-center">
						<div class="col-xl-8 col-xxl-12">
							<div class="text-center text-xl-start text-xxl-center mb-4 mb-xl-0 mb-xxl-4">
								<h1 class="text-primary">Bank Soal<br>MTs Alkhairaat Ampana Kota</h1>
							</div>
						</div>
						<div class="col-xl-4 col-xxl-12 text-center"><img class="img-fluid" src="assets/img/illustrations/at-work.svg" style="max-width: 26rem" /></div>
						
						<div class="col-12 mb-4 mt-5">
							<div class="card bg-primary text-white h-100">
								<div class="card-body">
									<div class="d-flex justify-content-between align-items-center">
										<div class="me-3">
											<div class="text-white-75 small">Total Upload</div>
											<div class="text-lg fw-bold">
												<?php
												$sql = "SELECT COUNT(*) FROM bank_soal";
												$stmt = $pdo->prepare($sql);
												$stmt->execute();
												$total_soal = $stmt->fetchColumn();
												echo $total_soal." Soal";
												?>
											</div>
										</div>
										<i class="feather-xl text-white-50" data-feather="check-square"></i>
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
		
		
		<div class="col-md-6 mb-4">
			<div class="card card-header-actions h-100">
				<div class="card-header">
					Aktivitas Terakhir
				</div>
				<div class="card-body">
					<div class="timeline timeline-xs">
						<?php require_once "elements/recent-activity.php"; ?>
					</div>
				</div>
			</div>
		</div>
		
		
		
		<div class="col-12 mb-4">
			<div class="card card-header-actions h-100">
				<div class="card-header">
					Progress Upload
				</div>
				<div class="card-body">
					
					<?php
					$sql_mapel = "SELECT m.kode_mapel, COUNT(b.kode_mapel) AS total_soal 
								  FROM tabel_mapel m
								  LEFT JOIN bank_soal b ON m.kode_mapel = b.kode_mapel
								  GROUP BY m.kode_mapel";
					$stmt_mapel = $pdo->prepare($sql_mapel);
					$stmt_mapel->execute();

					$total_soal_ditetapkan = 40;
					$bg_classes = ['bg-danger', 'bg-info', 'bg-warning', 'bg-success', 'bg-secondary', 'bg-primary'];

					$mapelChunks = [];
					$tempChunk = [];
					$counter = 0;

					while ($row_mapel = $stmt_mapel->fetch(PDO::FETCH_ASSOC)) {
						ob_start();
						$kode_mapel = $row_mapel['kode_mapel'];
						$jumlah_soal = $row_mapel['total_soal'];
						$persentase = ($jumlah_soal / $total_soal_ditetapkan) * 100;
						$random_bg_class = $bg_classes[array_rand($bg_classes)];
						?>
						<h4 class="small">
							<?= htmlspecialchars($kode_mapel) ?>
							<span class="float-end fw-bold"><?= number_format($persentase, 2) ?>%</span>
						</h4>
						<div class="progress mb-4">
							<div class="progress-bar <?= $random_bg_class ?>" role="progressbar" style="width: <?= number_format($persentase, 2) ?>%" aria-valuenow="<?= $persentase ?>" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<?php
						$tempChunk[] = ob_get_clean();
						$counter++;

						// Setiap 5 item, masukkan ke grup mapelChunks dan reset tempChunk
						if ($counter % 5 === 0) {
							$mapelChunks[] = $tempChunk;
							$tempChunk = [];
						}
					}

					// Tambahkan sisa item jika ada
					if (!empty($tempChunk)) {
						$mapelChunks[] = $tempChunk;
					}

					// Tampilkan dalam grid: setiap 2 chunk = 1 row dengan 2 col-md-6
					for ($i = 0; $i < count($mapelChunks); $i += 2) {
						echo '<div class="row">';

						for ($j = 0; $j < 2; $j++) {
							if (isset($mapelChunks[$i + $j])) {
								echo '<div class="col-md-6">';
								foreach ($mapelChunks[$i + $j] as $output) {
									echo $output;
								}
								echo '</div>';
							}
						}

						echo '</div>';
					}
					?>

				</div>

			</div>
		</div>

	</div>

</div>
