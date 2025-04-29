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
              $stmt = $pdo->prepare("SELECT * FROM tabel_mapel WHERE kode_mapel = :kodemapel AND id_guru = :id_guru LIMIT 1");
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

<!-- Main page content -->
<div class="container-xl px-4 mt-n10">
  <div class="card mb-4">
    <div class="card-body">
	
		<?php
		$kode_mapel = $_GET['mapel'];
		$id_soal = $_GET['id'];

		// Ambil data soal dari database berdasarkan kode_mapel dan id_soal
		try {
			$stmt = $pdo->prepare("SELECT * FROM bank_soal WHERE id = :id_soal AND kode_mapel = :kode_mapel AND id_guru = :id_guru LIMIT 1");
			$stmt->bindParam(':id_soal', $id_soal, PDO::PARAM_INT);
			$stmt->bindParam(':kode_mapel', $kode_mapel, PDO::PARAM_STR);
			$stmt->bindParam(':id_guru', $_SESSION['user_id'], PDO::PARAM_INT);
			$stmt->execute();
			$soal = $stmt->fetch(PDO::FETCH_ASSOC);
			
			$soal_teks = $soal['soal']; // Simpan soal dalam variabel terpisah
			$gambar_soal = $soal['gambar_soal'];
			$jawab1 = $soal['jawab1'];
			$jawab2 = $soal['jawab2'];
			$jawab3 = $soal['jawab3'];
			$jawab4 = $soal['jawab4'];
			$gambarjawab1 = $soal['gambarjawab1'];
			$gambarjawab2 = $soal['gambarjawab2'];
			$gambarjawab3 = $soal['gambarjawab3'];
			$gambarjawab4 = $soal['gambarjawab4'];
			$jawaban = $soal['jawaban'];

			
			if (!$soal) {
				die("Soal tidak ditemukan atau Anda tidak memiliki akses.");
			}
		} catch (PDOException $e) {
			die("Error: " . htmlspecialchars($e->getMessage()));
		}
		?>
	

      <!-- Quill CSS -->
      <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

      <form action="actions/edit-bank-soal.php" method="POST" enctype="multipart/form-data" onsubmit="return prepareSubmit()">
        <input type="hidden" name="kode_mapel" value="<?php echo $kode_mapel; ?>">
        <input type="hidden" name="id_soal" value="<?php echo $id_soal; ?>">
		<input type="hidden" name="action" value="add_question">
		<input type="hidden" name="subject" value="<?php echo $nama_mapel; ?>">
		

        <!-- Soal -->
        <div class="mb-4">
          <label for="soal" class="form-label">Soal</label>
          <div class="form-control p-0" style="min-height:200px;">
            <div id="editor-soal" style="min-height:200px;"><?php echo $soal_teks; ?></div>
          </div>
          <input type="hidden" name="soal" id="input-soal">
        </div>

        <!-- Gambar Soal -->
        <label class="form-label">Gambar Soal</label><br>
		<?php 
		if( $gambar_soal !== "" ){?>
			<img id="preview_gambar_soal" src="uploads/<?php echo $gambar_soal; ?>" class="img-fluid mt-2 mb-2" style="max-height: 200px;"><?php
		}else{ ?>
			<img id="preview_gambar_soal" class="img-fluid mt-2" style="max-height: 200px;"><?php
		}
		?>
        <div class="input-group mb-5">
          <input class="form-control" type="file" id="gambar_soal" name="gambar_soal" accept="image/*">
          <button type="button" class="btn btn-info" onclick="pasteImage('gambar_soal', 'preview_gambar_soal')">Paste Gambar</button>
        </div>

        <!-- Jawaban A-D -->
        
          <div class="mb-4">
            <label class="form-label">Jawaban A</label>
            <div class="row">
              <div class="col-md-7">
                <div class="form-control p-0" style="min-height:150px;">
                  <div id="editor-jawab1" style="min-height:150px;"><?php echo $jawab1; ?></div>
                </div>
                <input type="hidden" name="jawab1" id="input-jawab1">
              </div>
              <div class="col-md-5">
                <small class="form-label mt-2">Gambar Jawaban A</small><br>
				<?php
				if( $gambarjawab1 !== "" ){ ?>
					<img id="preview_gambarjawab1" src="uploads/<?php echo $gambarjawab1; ?>" class="img-fluid mt-2 mb-2" style="max-height: 200px;"><?php
				}else{ ?>
					<img id="preview_gambarjawab1" class="img-fluid mt-2 mb-2" style="max-height: 200px;"><?php
				}
				?>
                <div class="input-group mb-3">
                  <input class="form-control" type="file" id="gambarjawab1" name="gambarjawab1" accept="image/*">
                  <button type="button" class="btn btn-info" onclick="pasteImage('gambarjawab1', 'preview_gambarjawab1')">Paste Gambar</button>
                </div>
              </div>
            </div>
          </div><hr>
          <div class="mb-4">
            <label class="form-label">Jawaban B</label>
            <div class="row">
              <div class="col-md-7">
                <div class="form-control p-0" style="min-height:150px;">
                  <div id="editor-jawab2" style="min-height:150px;"><?php echo $jawab2; ?></div>
                </div>
                <input type="hidden" name="jawab2" id="input-jawab2">
              </div>
              <div class="col-md-5">
                <small class="form-label mt-2">Gambar Jawaban B</small><br>
				<?php
				if( $gambarjawab2 !== "" ){ ?>
					<img id="preview_gambarjawab2" src="uploads/<?php echo $gambarjawab2; ?>" class="img-fluid mt-2 mb-2" style="max-height: 200px;"><?php
				}else{ ?>
					<img id="preview_gambarjawab2" class="img-fluid mt-2 mb-2" style="max-height: 200px;"><?php
				}
				?>
                <div class="input-group mb-3">
                  <input class="form-control" type="file" id="gambarjawab2" name="gambarjawab2" accept="image/*">
                  <button type="button" class="btn btn-info" onclick="pasteImage('gambarjawab2', 'preview_gambarjawab2')">Paste Gambar</button>
                </div>
              </div>
            </div>
          </div><hr>
          <div class="mb-4">
            <label class="form-label">Jawaban C</label>
            <div class="row">
              <div class="col-md-7">
                <div class="form-control p-0" style="min-height:150px;">
                  <div id="editor-jawab3" style="min-height:150px;"><?php echo $jawab3; ?></div>
                </div>
                <input type="hidden" name="jawab3" id="input-jawab3">
              </div>
              <div class="col-md-5">
                <small class="form-label mt-2">Gambar Jawaban C</small><br>
				<?php
				if( $gambarjawab3 !== "" ){ ?>
					<img id="preview_gambarjawab3" src="uploads/<?php echo $gambarjawab3; ?>" class="img-fluid mt-2 mb-2" style="max-height: 200px;"><?php
				}else{ ?>
					<img id="preview_gambarjawab3" class="img-fluid mt-2 mb-2" style="max-height: 200px;"><?php
				}
				?>
                <div class="input-group mb-3">
                  <input class="form-control" type="file" id="gambarjawab3" name="gambarjawab3" accept="image/*">
                  <button type="button" class="btn btn-info" onclick="pasteImage('gambarjawab3', 'preview_gambarjawab3')">Paste Gambar</button>
                </div>
              </div>
            </div>
          </div><hr>
          <div class="mb-4">
            <label class="form-label">Jawaban D</label>
            <div class="row">
              <div class="col-md-7">
                <div class="form-control p-0" style="min-height:150px;">
                  <div id="editor-jawab4" style="min-height:150px;"><?php echo $jawab4; ?></div>
                </div>
                <input type="hidden" name="jawab4" id="input-jawab4">
              </div>
              <div class="col-md-5">
                <small class="form-label mt-2">Gambar Jawaban D</small><br>
				<?php
				if( $gambarjawab4 !== "" ){ ?>
					<img id="preview_gambarjawab4" src="uploads/<?php echo $gambarjawab4; ?>" class="img-fluid mt-2 mb-2" style="max-height: 200px;"><?php
				}else{ ?>
					<img id="preview_gambarjawab4" class="img-fluid mt-2 mb-2" style="max-height: 200px;"><?php
				}
				?>
                <div class="input-group mb-3">
                  <input class="form-control" type="file" id="gambarjawab4" name="gambarjawab4" accept="image/*">
                  <button type="button" class="btn btn-info" onclick="pasteImage('gambarjawab4', 'preview_gambarjawab4')">Paste Gambar</button>
                </div>
              </div>
            </div>
          </div><hr>
        <!-- Kunci Jawaban -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="jawaban" class="form-label">Kunci Jawaban</label>
            <select class="form-select" id="jawaban" name="jawaban" required>
              <option value="">Pilih Jawaban Benar</option>
              <option value="A" <?php echo $jawaban == 'A' ? 'selected' : ''; ?> >A</option>
              <option value="B" <?php echo $jawaban == 'B' ? 'selected' : ''; ?> >B</option>
              <option value="C" <?php echo $jawaban == 'C' ? 'selected' : ''; ?> >C</option>
              <option value="D" <?php echo $jawaban == 'D' ? 'selected' : ''; ?> >D</option>
            </select>
          </div>
          <div class="col-md-6 d-flex align-items-end justify-content-end">
            <button type="submit" class="btn btn-primary">Simpan Soal</button>
          </div>
        </div>

      </form>

      <!-- Quill JS -->
      <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

      <script>
      const editors = {};

      // Inisialisasi Editor
      editors.soal = new Quill('#editor-soal', {
        theme: 'snow',
        modules: {
          toolbar: [
            [{ 'header': [1, 2, false] }],
            ['bold', 'italic', 'underline'],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'align': [] }],
            ['clean']
          ]
        }
      });

      // Inisialisasi Editor Jawaban A-D
      for (let i = 1; i <= 4; i++) {
        editors['jawab' + i] = new Quill('#editor-jawab' + i, {
          theme: 'snow',
          modules: {
            toolbar: [
              ['bold', 'italic', 'underline'],
              [{ 'color': [] }, { 'align': [] }],
              ['clean']
            ]
          }
        });
      }

      function prepareSubmit() {
        document.getElementById('input-soal').value = editors.soal.root.innerHTML.trim();
        for (let i = 1; i <= 4; i++) {
          document.getElementById('input-jawab' + i).value = editors['jawab' + i].root.innerHTML.trim();
        }

        // Validasi: pastikan soal, jawaban, dan kunci jawaban tidak kosong
        if (
          editors.soal.getText().trim() === '' ||
          editors.jawab1.getText().trim() === '' ||
          editors.jawab2.getText().trim() === '' ||
          editors.jawab3.getText().trim() === '' ||
          editors.jawab4.getText().trim() === '' ||
          document.getElementById('jawaban').value === ''
        ) {
          alert('Semua kolom soal, jawaban, dan kunci jawaban wajib diisi!');
          return false; // Batalkan submit
        }
        return true;
      }
      </script>

      <!-- Preview dan Paste Gambar -->
      <script>
      function previewImage(inputId, imgId) {
        const input = document.getElementById(inputId);
        const img = document.getElementById(imgId);

        input.addEventListener('change', function() {
          const file = this.files[0];
          if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
              img.src = e.target.result;
            }
            reader.readAsDataURL(file);
          }
        });
      }

      function pasteImage(inputId, imgId) {
		  // Membaca clipboard
		  navigator.clipboard.read().then(data => {
			// Memeriksa setiap item di clipboard
			for (const item of data) {
			  if (item.types.includes('image/png') || item.types.includes('image/jpeg')) {
				// Jika item berisi gambar, kita dapatkan blob-nya
				item.getType(item.types[0]).then(blob => {
				  const file = new File([blob], "pasted-image.png", { type: blob.type });
				  const dt = new DataTransfer();
				  dt.items.add(file); // Menambahkan gambar ke DataTransfer (simulasi pemilihan file)
				  
				  // Menambahkan gambar ke input file
				  const input = document.getElementById(inputId);
				  input.files = dt.files;

				  // Menampilkan gambar ke preview
				  const reader = new FileReader();
				  reader.onload = function(e) {
					document.getElementById(imgId).src = e.target.result;
				  }
				  reader.readAsDataURL(file);
				});
			  } else {
				alert('Clipboard tidak berisi gambar yang valid.');
			  }
			}
		  }).catch(err => {
			alert('Gagal membaca clipboard: ' + err);
		  });
		}

      // Aktifkan preview otomatis
      ['gambar_soal', 'gambarjawab1', 'gambarjawab2', 'gambarjawab3', 'gambarjawab4'].forEach(id => {
        previewImage(id, 'preview_' + id);
      });
      </script>

    </div>
  </div>
</div>
				
