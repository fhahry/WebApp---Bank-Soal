<?php require_once "valid.php"; ?>

<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    $stmt = $pdo->prepare("
        SELECT 
            m.id, m.kode_mapel, m.nama_mapel, m.kode_kelas, m.nama_kelas, m.id_guru, u.nama_lengkap AS nama_guru
        FROM 
            tabel_mapel m
        LEFT JOIN 
            tbl_user u ON m.id_guru = u.id
        WHERE 
            m.id = ?
    ");
    $stmt->execute([$id]);
    $mapel = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$mapel) {
        die("Data mata pelajaran tidak ditemukan");
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Cek apakah mapel ini sudah dipakai di bank_soal
$kode_mapel_locked = false;
try {
    $cek = $pdo->prepare("SELECT COUNT(*) FROM bank_soal WHERE kode_mapel = ?");
    $cek->execute([$mapel['kode_mapel']]);
    $kode_mapel_locked = $cek->fetchColumn() > 0;
} catch (PDOException $e) {
    die("Error saat cek bank soal: " . $e->getMessage());
}
?>


<header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
    <div class="container-xl px-4">
        <div class="page-header-content pt-4">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mt-4">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="activity"></i></div>
                        Edit Mata Pelajaran
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main page content-->
<div class="container-xl px-4 mt-n10">
    <div class="card mb-4">
        <div class="card-header">Edit Mata Pelajaran</div>
        <div class="card-body">
		
			<?php if ($kode_mapel_locked): ?>
				<div class="alert alert-warning">
					Mata pelajaran ini sudah memiliki soal di Bank Soal. Data tidak dapat diedit.
				</div>
			<?php endif; ?>		
		
            <form method="POST" action="actions/update-mapel.php">
                <input type="hidden" name="id" value="<?= htmlspecialchars($mapel['id']) ?>">
                
                <div class="mb-3">
                    <label for="ns_NamaMapel" class="form-label">Nama Mapel</label>
					<input name="ns_NamaMapel" type="text" class="form-control" id="ns_NamaMapel"
						value="<?= htmlspecialchars($mapel['nama_mapel']) ?>" pattern="[A-Za-z\s]+"
						<?= $kode_mapel_locked ? 'disabled' : '' ?> required>
                </div>
				
                
				<?php
				$kelasList = [];
				try {
					$stmt = $pdo->query("SELECT kode_kelas, nama_kelas FROM tabel_kelas ORDER BY tingkat_kelas ASC, nama_kelas ASC");
					$kelasList = $stmt->fetchAll(PDO::FETCH_ASSOC);
				} catch (PDOException $e) {
					echo "Gagal mengambil data kelas: " . $e->getMessage();
				}
				?>

				<div class="mb-3">
					<label for="ns_Kelas" class="form-label">Kelas</label>
					<select name="ns_Kelas" id="ns_Kelas" class="form-select" <?= $kode_mapel_locked ? 'disabled' : '' ?> required>
						<option value="">-- Pilih Kelas --</option>
						<?php foreach ($kelasList as $kelas): ?>
							<option value="<?= htmlspecialchars($kelas['kode_kelas']) ?>"
								<?= ($mapel['kode_kelas'] == $kelas['kode_kelas']) ? 'selected' : '' ?>>
								<?= htmlspecialchars($kelas['nama_kelas']) ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

                <div class="mb-3">
                    <label for="ns_KodeMapel" class="form-label">Kode Mapel</label>
                    <input name="ns_KodeMapel" type="text" class="form-control" id="ns_KodeMapel" 
                           value="<?= htmlspecialchars($mapel['kode_mapel']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="ns_IdGuru" class="form-label">Pilih Guru Mapel</label>
                    <select name="ns_IdGuru" id="ns_IdGuru" class="form-select" required>
                        <option value="">-- Pilih Guru --</option>
                        <?php
                        // Ambil data guru
                        $stmt_guru = $pdo->query("SELECT id, nama_lengkap FROM tbl_user WHERE level_user = '0'");
                        $gurus = $stmt_guru->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach ($gurus as $guru) {
                            $selected = ($guru['id'] == $mapel['id_guru']) ? 'selected' : '';
                            echo "<option value='".htmlspecialchars($guru['id'])."' $selected>".htmlspecialchars($guru['nama_lengkap'])."</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="dashboard.php?page=mapel" class="btn btn-secondary">Kembali</a>
                    <?php if (!$kode_mapel_locked): ?>
						<button type="submit" class="btn btn-primary">Simpan Perubahan</button>
					<?php endif; ?>
                </div>
            </form>
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

if (<?= json_encode($kode_mapel_locked) ?>) {
    document.getElementById('ns_NamaMapel').disabled = true;
    document.getElementById('ns_Kelas').disabled = true;
    document.getElementById('ns_KodeMapel').disabled = true;
    document.getElementById('ns_IdGuru').disabled = true;
}
</script>
