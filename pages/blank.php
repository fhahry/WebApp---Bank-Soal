<header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
	<div class="container-xl px-4">
		<div class="page-header-content pt-4">
			<div class="row align-items-center justify-content-between">
				<div class="col-auto mt-4">
					<h1 class="page-header-title">
						<div class="page-header-icon"><i data-feather="activity"></i></div>
						BLANK
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
		<div class="card-header">Daftar User</div>
		<div class="card-body">
			<table class="table">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Nama Lengkap</th>
						<th scope="col">Username</th>
						<th scope="col">Mata Pelajaran</th>
						<th scope="col">Login Terakhir</th>
					</tr>
				</thead>
				<tbody>
					<?php
					try {
						$stmt = $pdo->prepare("SELECT * FROM tbl_user");
						$stmt->execute();

						$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

						foreach ($result as $row) {
							echo "<tr>";
							echo "<td>" . htmlspecialchars($row['id']) . "</td>";
							echo "<td>" . htmlspecialchars($row['nama_lengkap']) . "</td>";
							echo "<td>" . htmlspecialchars($row['username']) . "</td>";
							echo "<td></td>";
							echo "<td>" . htmlspecialchars($row['login_terakhir']) . "</td>";
							echo "</tr>";
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
	<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"> Tambah User </button>
	
	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method="POST" action="actions/tambah-user.php">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="exampleModalLabel">Tambah User</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						
						<div class="mb-3">
							<label for="on_NamaLengkap" class="form-label">Nama Lengkap</label>
							<input name="on_NamaLengkap" type="text" class="form-control" id="on_NamaLengkap" value="" >
						</div>

						<div class="mb-3">
							<label for="on_Username" class="form-label">Username</label>
							<input name="on_Username" type="text" class="form-control" id="on_Username" value="" >
						</div>
						
						<div class="mb-3">
							<label for="on_Password" class="form-label">Password</label>
							<input name="on_Password" type="text" class="form-control" id="on_Password" value="" >
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
