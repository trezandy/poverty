<?php
require_once '../config/database.php';

if (!isset($_GET['id'])) {
    header("Location: training.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM data_training WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    header("Location: training.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Training - Sistem Klasifikasi SVM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar { margin-bottom: 2rem; }
        .card { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
        .form-label { font-weight: 500; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Sistem Klasifikasi SVM</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="training.php">Data Training</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="testing.php">Data Testing</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Edit Data Training</h2>
        <div class="card mb-4">
            <div class="card-body">
                <form action="proses_edit_training.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" required maxlength="16" value="<?php echo $data['nik']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required value="<?php echo $data['nama']; ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rt" class="form-label">RT</label>
                                <input type="text" class="form-control" id="rt" name="rt" required maxlength="3" value="<?php echo $data['rt']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rw" class="form-label">RW</label>
                                <input type="text" class="form-control" id="rw" name="rw" required maxlength="3" value="<?php echo $data['rw']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="tempat_tinggal" class="form-label">Tempat Tinggal</label>
                        <select class="form-select" id="tempat_tinggal" name="tempat_tinggal" required>
                            <option value="">Pilih Tempat Tinggal</option>
                            <option value="bebas sewa" <?php echo $data['tempat_tinggal'] == 'bebas sewa' ? 'selected' : ''; ?>>Bebas Sewa</option>
                            <option value="milik sendiri" <?php echo $data['tempat_tinggal'] == 'milik sendiri' ? 'selected' : ''; ?>>Milik Sendiri</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Dinding</label>
                                <select name="dinding" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="anyaman bambu" <?php echo $data['dinding'] == 'anyaman bambu' ? 'selected' : ''; ?>>Anyaman Bambu</option>
                                    <option value="kayu" <?php echo $data['dinding'] == 'kayu' ? 'selected' : ''; ?>>Kayu</option>
                                    <option value="tembok" <?php echo $data['dinding'] == 'tembok' ? 'selected' : ''; ?>>Tembok</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Lantai</label>
                                <select name="lantai" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="kayu" <?php echo $data['lantai'] == 'kayu' ? 'selected' : ''; ?>>Kayu</option>
                                    <option value="semen/bata merah" <?php echo $data['lantai'] == 'semen/bata merah' ? 'selected' : ''; ?>>Semen/Bata Merah</option>
                                    <option value="ubin" <?php echo $data['lantai'] == 'ubin' ? 'selected' : ''; ?>>Ubin</option>
                                    <option value="keramik" <?php echo $data['lantai'] == 'keramik' ? 'selected' : ''; ?>>Keramik</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Atap</label>
                                <select name="atap" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="seng" <?php echo $data['atap'] == 'seng' ? 'selected' : ''; ?>>Seng</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Sumber Air</label>
                                <select name="sumber_air" class="form-select" required>
                                    <option value="">Pilih Sumber Air</option>
                                    <option value="Air Sungai" <?php echo $data['sumber_air'] == 'Air Sungai' ? 'selected' : ''; ?>>Air Sungai</option>
                                    <option value="PAM" <?php echo $data['sumber_air'] == 'PAM' ? 'selected' : ''; ?>>PAM</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Sumber Penerangan</label>
                                <select name="sumber_penerangan" class="form-select" required>
                                    <option value="">Pilih Sumber Penerangan</option>
                                    <option value="Bukan Listrik (Tanpa Meteran)" <?php echo $data['sumber_penerangan'] == 'Bukan Listrik (Tanpa Meteran)' ? 'selected' : ''; ?>>Bukan Listrik (Tanpa Meteran)</option>
                                    <option value="Listrik PLN (Tanpa Meteran)" <?php echo $data['sumber_penerangan'] == 'Listrik PLN (Tanpa Meteran)' ? 'selected' : ''; ?>>Listrik PLN (Tanpa Meteran)</option>
                                    <option value="Listrik Non PLN (450 Watt)" <?php echo $data['sumber_penerangan'] == 'Listrik Non PLN (450 Watt)' ? 'selected' : ''; ?>>Listrik Non PLN (450 Watt)</option>
                                    <option value="Listrik PLN (450 Watt)" <?php echo $data['sumber_penerangan'] == 'Listrik PLN (450 Watt)' ? 'selected' : ''; ?>>Listrik PLN (450 Watt)</option>
                                    <option value="Listrik PLN (900 Watt)" <?php echo $data['sumber_penerangan'] == 'Listrik PLN (900 Watt)' ? 'selected' : ''; ?>>Listrik PLN (900 Watt)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Bahan Bakar Masak</label>
                                <select name="bahan_bakar_masak" class="form-select" required>
                                    <option value="">Pilih Bahan Bakar Masak</option>
                                    <option value="Gas 3Kg" <?php echo $data['bahan_bakar_masak'] == 'Gas 3Kg' ? 'selected' : ''; ?>>Gas 3Kg</option>
                                    <option value="Listrik" <?php echo $data['bahan_bakar_masak'] == 'Listrik' ? 'selected' : ''; ?>>Listrik</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Tempat BAB</label>
                                <select name="tempat_bab" class="form-select" required>
                                    <option value="">Pilih Tempat BAB</option>
                                    <option value="Tidak Pakai" <?php echo $data['tempat_bab'] == 'Tidak Pakai' ? 'selected' : ''; ?>>Tidak Pakai</option>
                                    <option value="Cemplung/Cubluk" <?php echo $data['tempat_bab'] == 'Cemplung/Cubluk' ? 'selected' : ''; ?>>Cemplung/Cubluk</option>
                                    <option value="Leher Angsa" <?php echo $data['tempat_bab'] == 'Leher Angsa' ? 'selected' : ''; ?>>Leher Angsa</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <select name="keterangan" class="form-select" required>
                                    <option value="">Pilih Keterangan</option>
                                    <option value="mampu" <?php echo $data['keterangan'] == 'mampu' ? 'selected' : ''; ?>>mampu</option>
                                    <option value="kurang mampu" <?php echo $data['keterangan'] == 'kurang mampu' ? 'selected' : ''; ?>>kurang mampu</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="training.php" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 