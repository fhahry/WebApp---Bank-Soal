<?php
if (!file_exists('core.php')) {
    header('Location: setup.php');
    exit();
} else {
    if (file_exists('setup.php')) {
        unlink('setup.php');
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
	<title>Login - Bank Soal</title>
	<link href="css/styles.css" rel="stylesheet" />
	<link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
	<script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-primary">

	<?php
	@session_start();
	if ( isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_SESSION['level_user']) && isset($_SESSION['nama_lengkap']) ) {
		header("Location: dashboard.php");
		exit;
	}
	?>

	<div id="layoutAuthentication">
		<div id="layoutAuthentication_content">
			<main>
				<div class="container-xl px-4">
					<div class="row justify-content-center">
						<div class="col-lg-5">
							<div class="card shadow-lg border-0 rounded-lg mt-5">
								<div class="card-header justify-content-center">
									<h3 class="fw-light my-4 text-center">Aplikasi Bank Soal<br>MTs. Alkhairaat Ampana Kota</h3>
									
									<!-- Carousel -->
									<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
										<div class="carousel-indicators">
											<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
											<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
											<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
											<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 3"></button>
											<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4" aria-label="Slide 4"></button>
										</div>
										<div class="carousel-inner">
											<div class="carousel-item active">
												<img src="assets/img/slider/0001.jpg" class="d-block w-100" alt="...">
											</div>
											<div class="carousel-item">
												<img src="assets/img/slider/0002.jpg" class="d-block w-100" alt="...">
											</div>
											<div class="carousel-item">
												<img src="assets/img/slider/0003.jpg" class="d-block w-100" alt="...">
											</div>
											<div class="carousel-item">
												<img src="assets/img/slider/0004.jpg" class="d-block w-100" alt="...">
											</div>
											<div class="carousel-item">
												<img src="assets/img/slider/0005.jpg" class="d-block w-100" alt="...">
											</div>
										</div>
										<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
											<span class="carousel-control-prev-icon" aria-hidden="true"></span>
											<span class="visually-hidden">Previous</span>
										</button>
										<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
											<span class="carousel-control-next-icon" aria-hidden="true"></span>
											<span class="visually-hidden">Next</span>
										</button>
									</div>
								</div>
								<div class="card-body">
									<form method="POST" action="login.php">
										<h3>Login</h3>
										<div class="mb-3">
											<label class="small mb-1" for="inputUsername">Username</label>
											<input class="form-control" name="username" id="inputUsername" type="text" placeholder="Masukkan Username" value="" />
										</div>
										<div class="mb-3">
											<label class="small mb-1" for="inputPassword">Password</label>
											<input class="form-control" name="password"  id="inputPassword" type="password" placeholder="Masukkan Password" />
										</div>
										<div class="d-flex align-items-center justify-content-between mt-4 mb-0">
											<button class="btn btn-primary" type="submit">Login</button>
										</div>
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
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
	<script src="js/scripts.js"></script>
</body>
</html>
