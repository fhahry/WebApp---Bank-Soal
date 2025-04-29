<div id="layoutSidenav_nav">

    <nav class="sidenav shadow-right sidenav-light">
	
        <div class="sidenav-menu">
		
            <div class="nav accordion" id="accordionSidenav">

                <div class="sidenav-menu-heading">Menu</div>
                
                <a class="nav-link" href="?page=dashboard">
                    <div class="nav-link-icon">
                        <i data-feather="activity"></i>
                    </div> Beranda
                </a>
                
				<?php if( $_SESSION['level_user'] === "0" ){ ?>
				
					<div class="sidenav-menu-heading">MATA PELAJARAN</div>
					
					<?php
					try {
						// Ambil ID user dari session
						$user_id = $_SESSION['user_id'];

						// Ambil semua mapel yang diampu guru ini
						$stmt = $pdo->prepare("
							SELECT kode_mapel
							FROM tabel_mapel
							WHERE id_guru = :user_id
						");
						$stmt->execute(['user_id' => $user_id]);

						$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

						foreach ($result as $row) {
							echo "<a class='nav-link' href='?page=data-bank-soal&mapel=" . htmlspecialchars($row['kode_mapel']) . "'>
									<div class='nav-link-icon'><i data-feather='book'></i></div> " . 
									htmlspecialchars($row['kode_mapel']) . 
								  "</a>";
						}
					} catch (PDOException $e) {
						echo "Error: " . $e->getMessage();
					}
					?>
					
					
					
					<hr>
				<?php  } ?>
				
				

				
				<?php if( $_SESSION['level_user'] === "1" ){ ?>
					<a class="nav-link" href="?page=users">
						<div class="nav-link-icon">
							<i data-feather="user"></i>
						</div> Users
					</a>
					
					<a class="nav-link" href="?page=kelas">
						<div class="nav-link-icon">
							<i data-feather="home"></i>
						</div> Kelas
					</a>
					
					<a class="nav-link" href="?page=mapel">
						<div class="nav-link-icon">
							<i data-feather="book"></i>
						</div> Mata Pelajaran
					</a>
				<?php  } ?>

				
            </div>
			
        </div>
        
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">Login:</div>
                <div class="sidenav-footer-title"><?php echo $_SESSION['nama_lengkap']; ?></div>
            </div>
        </div>
		
    </nav>
</div>