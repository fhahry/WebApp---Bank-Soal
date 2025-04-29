<?php
require_once "valid.php";
require_once "core.php";
?>


<!DOCTYPE html>
<html lang="en">

	<?php include_once "elements/head.php"; ?>

    <body class="nav-fixed">
        
		<?php include_once "elements/nav.php"; ?>
		
		<div id="layoutSidenav">

			<?php include_once "elements/side-nav.php"; ?>
			
			<div id="layoutSidenav_content">
                <main>
				
					<?php 
					@$page = $_GET['page'];
					switch($page){
						default: include_once "pages/dashboard.php"; break;
						
						case "users": include_once "pages/data-users.php"; break; 
						
						case "kelas": include_once "pages/data-kelas.php"; break; 
						case "edit-kelas": include_once "pages/edit-kelas.php"; break; 
						case "hapus-kelas": include_once "pages/hapus-kelas.php"; break; 
						
						case "mapel": include_once "pages/data-mapel.php"; break; 
						case "edit-mapel": include_once "pages/edit-mapel.php"; break; 
						case "hapus-mapel": include_once "pages/hapus-mapel.php"; break; 
						
						case "data-bank-soal": include_once "pages/data-bank-soal.php"; break; 
						case "tambah-bank-soal": include_once "pages/tambah-bank-soal.php"; break; 
						case "edit-bank-soal": include_once "pages/edit-bank-soal.php"; break; 
						case "hapus-bank-soal": include_once "pages/hapus-bank-soal.php"; break; 
					};
					?>
				
                </main>
				
				<?php include_once "elements/footer.php"; ?>
				
            </div>
			
        </div>

		<?php include_once "elements/script-bottom.php"; ?>

    </body>
	
</html>
