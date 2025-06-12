<?php
require_once '../config/database.php';
require_once '../validasi.php';

// Pastikan koneksi database tersedia
if (!isset($pdo) || $pdo === null) {
    die("Error: Koneksi database tidak tersedia");
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klasifikasi Data - Sistem Klasifikasi SVM</title>
    <link rel="icon" href="data:,">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="../assets/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .table-responsive {
            margin-top: 1rem;
            width: 100%;
            overflow-x: auto;
        }

        table.dataTable {
            width: 100% !important;
            margin: 0 !important;
        }

        .dataTables_wrapper {
            padding: 1rem 0;
            width: 100%;
        }

        .dataTables_length select {
            min-width: 65px;
            margin: 0 0.5rem;
            padding: 0.375rem 1.75rem 0.375rem 0.75rem;
            border-radius: 0.375rem;
            border: 1px solid #dee2e6;
            background-color: #fff;
        }

        .dataTables_filter input {
            margin-left: 0.5rem;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            border: 1px solid #dee2e6;
            background-color: #fff;
        }

        .dataTables_info {
            padding-top: 1rem !important;
            font-size: 0.875rem;
            color: #6c757d;
        }

        .btn-group .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .badge {
            font-size: 0.875rem;
            padding: 0.5em 0.75em;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 1rem;
        }

        .card-title {
            margin: 0;
            color: #0d6efd;
            font-weight: 600;
        }

        .card-body {
            padding-bottom: 2rem;
        }

        .confusion-matrix-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 1rem 2rem 2rem 2rem;
            margin-bottom: 2rem;
        }

        .matrix-title {
            color: #2c3e50;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #ecf0f1;
        }

        .matrix-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        .matrix-table th,
        .matrix-table td {
            border: 1px solid #dee2e6;
            padding: 1rem;
            text-align: center;
        }

        .matrix-table th {
            background: #34495e;
            color: white;
        }

        .matrix-table td {
            font-weight: 500;
        }

        .true-positive {
            background-color: rgba(39, 174, 96, 0.1);
            color: #27ae60;
        }

        .true-negative {
            background-color: rgba(52, 152, 219, 0.1);
            color: #3498db;
        }

        .false-positive {
            background-color: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }

        .false-negative {
            background-color: rgba(243, 156, 18, 0.1);
            color: #f39c12;
        }

        .metrics-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .metric-card {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .metric-value {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0.5rem 0;
        }

        .metric-label {
            color: #34495e;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .matrix-info,
        .metrics-info {
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .matrix-info ul {
            padding-left: 1.2rem;
            margin-top: 0.5rem;
        }

        .matrix-info li {
            margin-bottom: 0.3rem;
        }

        .matrix-info li:last-child {
            margin-bottom: 0;
        }

        .matrix-table td {
            cursor: help;
            transition: all 0.2s ease;
        }

        .matrix-table td:hover {
            transform: scale(1.05);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .alert-heading {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: inherit;
        }

        .alert-heading i {
            opacity: 0.8;
        }

        .calculation-info {
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .calculation-info h6 {
            font-weight: 600;
            color: #2c3e50;
        }

        .upload-section {
            background: #ecf0f1;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .btn-upload {
            background: #2c3e50;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-upload:hover {
            background: #34495e;
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
    <?php include '../includes/sidebar.php'; ?>
    <div class="main-content">
        <div class="container-fluid mt-4">
            <div class="row">
                <!-- Data Training -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Data Training</h5>
                            <div>
                                <button type="button" class="btn btn-success btn-sm me-2" id="btnBuatModel">
                                    <i class="fas fa-cogs"></i> Proses
                                </button>
                                <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#addTrainingModal">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                                <a href="export_training.php" class="btn btn-info btn-sm">
                                    <i class="fas fa-file-excel"></i> Export
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="trainingTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $query = "SELECT nik, nama, keterangan FROM data_training ORDER BY nama ASC";
                                        $stmt = $pdo->query($query);
                                        while ($row = $stmt->fetch()) {
                                        ?>
                                            <tr>
                                                <td><?php echo $no++; ?></td>
                                                <td><?php echo $row['nik']; ?></td>
                                                <td><?php echo ucwords(strtolower($row['nama'])); ?></td>
                                                <td>
                                                    <span class="badge <?php echo ($row['keterangan'] == 'Miskin') ? 'bg-danger' : 'bg-success'; ?>">
                                                        <?php echo $row['keterangan']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewTrainingModal<?php echo $row['nik']; ?>">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editTrainingModal<?php echo $row['nik']; ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm delete-training" data-id="<?php echo $row['nik']; ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>

                                            <?php
                                            // Ambil detail data untuk modal view
                                            $detail_query = "SELECT * FROM data_training WHERE nik = ?";
                                            $detail_stmt = $pdo->prepare($detail_query);
                                            $detail_stmt->execute([$row['nik']]);
                                            $detail = $detail_stmt->fetch();
                                            // Include file modal view
                                            include 'view_training_modal.php';
                                            ?>

                                            <!-- Modal Edit Training -->
                                            <?php include 'edit_training_modal.php'; ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Testing -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Data Testing</h5>
                            <div>
                                <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#addTestingModal">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                                <!-- <button type="button" class="btn btn-warning btn-sm me-2" id="btnReprocessTesting" style="display: none;">
                                    <i class="fas fa-sync-alt"></i> Proses Ulang Data
                                </button> -->
                                <a href="export_testing.php" class="btn btn-info btn-sm">
                                    <i class="fas fa-file-excel"></i> Export
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="testingTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $query = "SELECT nik, nama, keterangan FROM data_testing ORDER BY nama ASC";
                                        $stmt = $pdo->query($query);
                                        while ($row = $stmt->fetch()) {
                                        ?>
                                            <tr>
                                                <td><?php echo $no++; ?></td>
                                                <td><?php echo $row['nik']; ?></td>
                                                <td><?php echo ucwords(strtolower($row['nama'])); ?></td>
                                                <td>
                                                    <span class="badge <?php echo ($row['keterangan'] == 'Miskin') ? 'bg-danger' : 'bg-success'; ?>">
                                                        <?php echo $row['keterangan']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewTestingModal<?php echo $row['nik']; ?>">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm delete-testing" data-id="<?php echo $row['nik']; ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>

                                            <?php
                                            // Ambil detail data untuk modal view testing
                                            $detail_testing_query = "SELECT * FROM data_testing WHERE nik = ?";
                                            $detail_testing_stmt = $pdo->prepare($detail_testing_query);
                                            $detail_testing_stmt->execute([$row['nik']]);
                                            $detail_testing = $detail_testing_stmt->fetch();
                                            // Include file modal view testing
                                            include 'view_testing_modal.php';
                                            ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Confusion Matrix Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="fas fa-table me-2"></i>Confusion Matrix</h5>
                        </div>
                        <div class="card-body">
                            <!-- <div class="upload-section mb-4">
                                <h6 class="matrix-title">Upload Data Excel Asli</h6>
                                <form id="uploadExcelForm" enctype="multipart/form-data">
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="excelFile" name="excel_file" accept=".xlsx, .xls" required>
                                        <button class="btn btn-upload" type="submit" id="btnUploadExcel">
                                            <i class="fas fa-upload"></i> Upload dan Analisis
                                        </button>
                                    </div>
                                    <small class="form-text text-muted mt-2 d-block"><i class="fas fa-info-circle me-1"></i> Format yang didukung: .xlsx, .xls</small>
                                </form>
                            </div> -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="confusion-matrix-container">
                                        <!-- <h6 class="matrix-title">Matriks Konfusi</h6> -->
                                        <div class="table-responsive">
                                            <table class="table table-bordered matrix-table">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2" rowspan="2"></th>
                                                        <th colspan="2" class="text-center">Prediksi</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Miskin</th>
                                                        <th>Tidak Miskin</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th rowspan="2" class="align-middle">Aktual</th>
                                                        <th>Miskin</th>
                                                        <td class="true-positive">TP</td>
                                                        <td class="false-negative">FN</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tidak Miskin</th>
                                                        <td class="false-positive">FP</td>
                                                        <td class="true-negative">TN</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="matrix-info alert alert-info">
                                            <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Keterangan:</h6>
                                            <ul>
                                                <li><strong>True Positive (TP):</strong> Data yang diprediksi "Miskin" dan benar-benar "Miskin"</li>
                                                <li><strong>True Negative (TN):</strong> Data yang diprediksi "Tidak Miskin" dan benar-benar "Tidak Miskin"</li>
                                                <li><strong>False Positive (FP):</strong> Data yang diprediksi "Miskin" tapi sebenarnya "Tidak Miskin"</li>
                                                <li><strong>False Negative (FN):</strong> Data yang diprediksi "Tidak Miskin" tapi sebenarnya "Miskin"</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="metrics-container">
                                        <div class="metric-card">
                                            <div class="metric-label">Akurasi</div>
                                            <div class="metric-value">0%</div>
                                            <div class="calculation-info alert alert-light">
                                                <h6 class="alert-heading"><i class="fas fa-calculator me-2"></i>Perhitungan:</h6>
                                                <p class="mb-0">(TP + TN) / (TP + TN + FP + FN)</p>
                                            </div>
                                        </div>
                                        <div class="metric-card">
                                            <div class="metric-label">Presisi</div>
                                            <div class="metric-value">0%</div>
                                            <div class="calculation-info alert alert-light">
                                                <h6 class="alert-heading"><i class="fas fa-calculator me-2"></i>Perhitungan:</h6>
                                                <p class="mb-0">TP / (TP + FP)</p>
                                            </div>
                                        </div>
                                        <div class="metric-card">
                                            <div class="metric-label">Recall</div>
                                            <div class="metric-value">0%</div>
                                            <div class="calculation-info alert alert-light">
                                                <h6 class="alert-heading"><i class="fas fa-calculator me-2"></i>Perhitungan:</h6>
                                                <p class="mb-0">TP / (TP + FN)</p>
                                            </div>
                                        </div>
                                        <div class="metric-card">
                                            <div class="metric-label">F1-Score</div>
                                            <div class="metric-value">0%</div>
                                            <div class="calculation-info alert alert-light">
                                                <h6 class="alert-heading"><i class="fas fa-calculator me-2"></i>Perhitungan:</h6>
                                                <p class="mb-0">2 * (Presisi * Recall) / (Presisi + Recall)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="fas fa-vial me-2"></i>Coba Prediksi</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Pilih semua fitur di bawah ini untuk melihat hasil prediksinya menggunakan model SVM yang sudah ada.</p>

                            <form id="formUjiTunggal">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="card h-100">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">Personal & Ekonomi</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Jenis Kelamin</label>
                                                    <select class="form-select" name="jenis_kelamin" required>
                                                        <option value="laki-laki">Laki-laki</option>
                                                        <option value="perempuan">Perempuan</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Pendidikan Terakhir</label>
                                                    <select class="form-select" name="pendidikan_terakhir" required>
                                                        <option value="SD">SD</option>
                                                        <option value="SLTP">SLTP</option>
                                                        <option value="SLTA">SLTA</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Pekerjaan</label>
                                                    <select class="form-select" name="pekerjaan" required>
                                                        <option value="Tani">Tani</option>
                                                        <option value="Pedagang">Pedagang</option>
                                                        <option value="Buruh">Buruh</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Aset Bergerak</label>
                                                    <select class="form-select" name="aset_bergerak" required>
                                                        <option value="Ada">Ada</option>
                                                        <option value="Tidak ada">Tidak ada</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card h-100">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">Kondisi Rumah</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Tempat Tinggal</label>
                                                    <select class="form-select" name="tempat_tinggal" required>
                                                        <option value="milik sendiri">Milik Sendiri</option>
                                                        <option value="bebas sewa">Bebas Sewa</option>
                                                        <option value="kontrakan">Kontrakan</option>
                                                        <option value="lainnya">Lainnya</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Dinding</label>
                                                    <select class="form-select" name="dinding" required>
                                                        <option value="tembok">Tembok</option>
                                                        <option value="kayu">Kayu</option>
                                                        <option value="anyaman bambu">Anyaman Bambu</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Lantai</label>
                                                    <select class="form-select" name="lantai" required>
                                                        <option value="keramik">Keramik</option>
                                                        <option value="ubin">Ubin</option>
                                                        <option value="semen/bata merah">Semen/Bata Merah</option>
                                                        <option value="kayu">Kayu</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Atap</label>
                                                    <select class="form-select" name="atap" required>
                                                        <option value="seng">Seng</option>
                                                        <option value="anyaman bambu">Anyaman Bambu</option>
                                                        <option value="anyaman">Anyaman</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card h-100">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">Fasilitas & Sanitasi</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Sumber Air Minum</label>
                                                    <select class="form-select" name="sumber_air_minum" required>
                                                        <option value="Air Isi Ulang">Air Isi Ulang</option>
                                                        <option value="Sumur Terlindung">Sumur Terlindung</option>
                                                        <option value="Sumur Bor/Pompa">Sumur Bor/Pompa</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Sumber Air</label>
                                                    <select class="form-select" name="sumber_air" required>
                                                        <option value="PAM">PAM</option>
                                                        <option value="Air Sungai">Air Sungai</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Sumber Penerangan</label>
                                                    <select class="form-select" name="sumber_penerangan" required>
                                                        <option value="Listrik PLN (900 Watt)">Listrik PLN (900 Watt)</option>
                                                        <option value="Listrik PLN (450 Watt)">Listrik PLN (450 Watt)</option>
                                                        <option value="Listrik Non PLN (450 Watt)">Listrik Non PLN (450 Watt)</option>
                                                        <option value="Listrik PLN (Tanpa Meteran)">Listrik PLN (Tanpa Meteran)</option>
                                                        <option value="Bukan Listrik (Tanpa Meteran)">Bukan Listrik (Tanpa Meteran)</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Bahan Bakar Masak</label>
                                                    <select class="form-select" name="bahan_bakar_masak" required>
                                                        <option value="Gas 3Kg">Gas 3Kg</option>
                                                        <option value="Listrik">Listrik</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Tempat BAB</label>
                                                    <select class="form-select" name="tempat_bab" required>
                                                        <option value="Sendiri">Sendiri</option>
                                                        <option value="Umum">Umum</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Jenis Kloset</label>
                                                    <select class="form-select" name="jenis_kloset" required>
                                                        <option value="Leher Angsa">Leher Angsa</option>
                                                        <option value="Cemplung/Cubluk">Cemplung/Cubluk</option>
                                                        <option value="Tidak Pakai">Tidak Pakai</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-8">
                                        <div id="hasilPrediksi" class="h-100" style="display: none;">
                                            <div class="alert alert-success d-flex flex-column justify-content-center h-100 text-center">
                                                <h5 class="alert-heading">Hasil Prediksi:</h5>
                                                <p class="display-6 fw-bold mb-1" id="prediksiKelas"></p>
                                                <p class="mb-0" id="prediksiConfidance"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary w-100 h-100 fs-5">
                                            <i class="fas fa-search me-2"></i>Prediksi Sekarang
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include modal form -->
    <?php include 'add_training_modal.php'; ?>

    <!-- Include modal form for testing -->
    <?php include 'add_testing_modal.php'; ?>
    <?php include 'view_testing_modal.php'; ?>

    <script src="../assets/js/jquery-3.7.0.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/dataTables.bootstrap5.min.js"></script>
    <script src="../assets/js/dataTables.responsive.min.js"></script>
    <script src="../assets/js/responsive.bootstrap5.min.js"></script>
    <script src="../assets/js/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle tombol Buat Model
            $('#btnBuatModel').on('click', function() {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Anda yakin ingin memproses data untuk membuat model baru dan menghitung Confusion Matrix?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Proses!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Mohon tunggu, model sedang dibuat dan diuji.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: 'create_model.php', // Pastikan path ini benar
                            type: 'POST',
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                    });

                                    // --- BAGIAN BARU: UPDATE UI SECARA DINAMIS ---
                                    const matrix = response.matrix;
                                    const metrics = response.metrics;

                                    // Update tabel Confusion Matrix
                                    $('.true-positive').text(matrix.TP);
                                    $('.false-negative').text(matrix.FN);
                                    $('.false-positive').text(matrix.FP);
                                    $('.true-negative').text(matrix.TN);

                                    // Update kartu metrik
                                    $('.metric-card:contains("Akurasi") .metric-value').text(metrics.accuracy + '%');
                                    $('.metric-card:contains("Presisi") .metric-value').text(metrics.precision + '%');
                                    $('.metric-card:contains("Recall") .metric-value').text(metrics.recall + '%');
                                    $('.metric-card:contains("F1-Score") .metric-value').text(metrics.f1_score + '%');

                                    // Tampilkan tombol proses ulang data testing
                                    $('#btnReprocessTesting').show();

                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        html: response.message // Gunakan html agar bisa menampilkan baris baru jika ada
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                let errorMessage = 'Terjadi kesalahan saat berkomunikasi dengan server.';
                                try {
                                    // Coba parse response text untuk pesan error yang lebih detail
                                    const errorResponse = JSON.parse(xhr.responseText);
                                    if (errorResponse && errorResponse.message) {
                                        errorMessage = errorResponse.message;
                                    }
                                } catch (e) {
                                    // Abaikan jika tidak bisa di-parse
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: errorMessage
                                });
                            }
                        });
                    }
                });
            });

            // Initialize DataTables
            // Inisialisasi tabel pertama (Training)
            // Initialize DataTables
            var trainingTable = $('#trainingTable').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                },
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Semua"]
                ],
                pageLength: 10,
                order: [
                    [2, 'asc']
                ],
                columnDefs: [{
                    targets: -1,
                    orderable: false,
                    searchable: false
                }],
                initComplete: function() {
                    $('.dataTables_length select').addClass('form-select form-select-sm');
                    $('.dataTables_filter input').addClass('form-control form-control-sm');
                },
                ajax: {
                    url: 'get_training.php',
                    dataSrc: 'data',
                    error: function(xhr, error, thrown) {
                        console.error('Error loading data:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal memuat data training'
                        });
                    }
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'nik'
                    },
                    {
                        data: 'nama',
                        render: function(data) {
                            return data.charAt(0).toUpperCase() + data.slice(1).toLowerCase();
                        }
                    },
                    {
                        data: 'keterangan',
                        render: function(data) {
                            return '<span class="badge ' + (data === 'Miskin' ? 'bg-danger' : 'bg-success') + '">' + data + '</span>';
                        }
                    },
                    {
                        data: 'nik',
                        render: function(data) {
                            return '<div class="btn-group" role="group">' +
                                '<button type="button" class="btn btn-info btn-sm view-training" data-id="' + data + '">' +
                                '<i class="fas fa-eye"></i></button>' +
                                '<button type="button" class="btn btn-warning btn-sm edit-training" data-id="' + data + '">' +
                                '<i class="fas fa-edit"></i></button>' +
                                '<button type="button" class="btn btn-danger btn-sm delete-training" data-id="' + data + '">' +
                                '<i class="fas fa-trash"></i></button>' +
                                '</div>';
                        }
                    }
                ],
                drawCallback: function() {
                    // Handle view button click
                    $('.view-training').off('click').on('click', function() {
                        const nik = $(this).data('id');
                        $('#viewTrainingModal' + nik).modal('show');
                    });

                    // Handle edit button click
                    $('.edit-training').off('click').on('click', function() {
                        const nik = $(this).data('id');
                        $('#editTrainingModal' + nik).modal('show');
                    });

                    // Handle delete button click
                    $('.delete-training').off('click').on('click', function() {
                        const nik = $(this).data('id');
                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Data training yang dihapus tidak dapat dikembalikan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal',
                            showLoaderOnConfirm: true,
                            preConfirm: () => {
                                return $.ajax({
                                    url: 'hapus_training.php',
                                    type: 'GET',
                                    data: {
                                        id: nik
                                    },
                                    dataType: 'json',
                                    success: function(response) {
                                        if (!response.success) {
                                            throw new Error(response.message || 'Gagal menghapus data');
                                        }
                                        return response;
                                    },
                                    error: function(xhr, status, error) {
                                        throw new Error('Terjadi kesalahan saat menghapus data');
                                    }
                                }).catch(error => {
                                    Swal.showValidationMessage(
                                        `Request failed: ${error}`
                                    )
                                })
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                        }).then((result) => {
                            if (result.isConfirmed) {
                                if (result.value.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: result.value.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: result.value.message || 'Gagal menghapus data'
                                    });
                                }
                            }
                        });
                    });
                }
            });

            var testingTable = $('#testingTable').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                },
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Semua"]
                ],
                pageLength: 10,
                order: [
                    [2, 'asc']
                ],
                columnDefs: [{
                    targets: -1,
                    orderable: false,
                    searchable: false
                }],
                initComplete: function() {
                    $('.dataTables_length select').addClass('form-select form-select-sm');
                    $('.dataTables_filter input').addClass('form-control form-control-sm');
                },
                ajax: {
                    url: 'get_testing.php',
                    dataSrc: 'data',
                    error: function(xhr, error, thrown) {
                        console.error('Error loading data:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal memuat data testing'
                        });
                    }
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'nik'
                    },
                    {
                        data: 'nama',
                        render: function(data) {
                            return data.charAt(0).toUpperCase() + data.slice(1).toLowerCase();
                        }
                    },
                    {
                        data: 'keterangan',
                        render: function(data) {
                            return '<span class="badge ' + (data === 'Miskin' ? 'bg-danger' : 'bg-success') + '">' + data + '</span>';
                        }
                    },
                    {
                        data: 'nik',
                        render: function(data) {
                            return '<div class="btn-group" role="group">' +
                                '<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewTestingModal' + data + '">' +
                                '<i class="fas fa-eye"></i></button>' +
                                '<button type="button" class="btn btn-danger btn-sm delete-testing" data-id="' + data + '">' +
                                '<i class="fas fa-trash"></i></button>' +
                                '</div>';
                        }
                    }
                ],
                drawCallback: function() {
                    // Handle view button click
                    $('.view-testing').off('click').on('click', function() {
                        const nik = $(this).data('id');
                        $('#viewTestingModal' + nik).modal('show');
                    });

                    // Handle delete button click
                    $('.delete-testing').off('click').on('click', function() {
                        const nik = $(this).data('id');
                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Data testing yang dihapus tidak dapat dikembalikan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal',
                            showLoaderOnConfirm: true,
                            preConfirm: () => {
                                return $.ajax({
                                    url: 'hapus_testing.php',
                                    type: 'GET',
                                    data: {
                                        id: nik
                                    },
                                    dataType: 'json'
                                }).catch(error => {
                                    Swal.showValidationMessage(
                                        `Request failed: ${error}`
                                    )
                                })
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                        }).then((result) => {
                            if (result.isConfirmed) {
                                if (result.value.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: result.value.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: result.value.message
                                    });
                                }
                            }
                        });
                    });
                }
            });

            // Handle form edit training submission
            $('form[id^="formEditTraining"]').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: 'proses_edit_training.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#editTrainingModal' + formData.get('nik_lama')).modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat mengirim data'
                        });
                    }
                });
            });

            // Handle form add training submission
            $('#formAddTraining').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: 'proses_training.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#addTrainingModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat mengirim data'
                        });
                    }
                });
            });

            // Handle form add testing submission
            $('#formAddTesting').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: 'proses_testing.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#addTestingModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat mengirim data'
                        });
                    }
                });
            });

            // Handle export training button click
            $('a[href="export_training.php"]').on('click', function(e) {
                e.preventDefault(); // Mencegah aksi default link
                const exportUrl = $(this).attr('href');

                // Lakukan pengecekan data menggunakan AJAX
                $.ajax({
                    url: exportUrl,
                    type: 'GET',
                    dataType: 'json', // Tetapkan dataType json untuk respon error/warning
                    success: function(response) {
                        if (response.success === false) {
                            // Menampilkan pesan warning jika tidak ada data atau error dari server
                            Swal.fire({
                                icon: 'warning',
                                title: 'Peringatan!',
                                text: response.message
                            });
                        } else {
                            // Jika sukses (artinya ada data), arahkan langsung ke URL untuk download file
                            // Di sini kita berasumsi export_training.php akan mengirim file jika tidak mengembalikan JSON success: false
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'File Excel data training berhasil dibuat. Unduhan akan segera dimulai.',
                                icon: 'success',
                                timer: 2000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            }).then(() => {
                                window.location.href = exportUrl; // Arahkan ke URL export untuk download
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Tangani error AJAX jika koneksi atau respon non-JSON
                        // Cek jika respon adalah data biner (kemungkinan file excel), abaikan error parsing JSON
                        if (xhr.getResponseHeader('Content-Type') && xhr.getResponseHeader('Content-Type').includes('spreadsheetml.sheet')) {
                            // Jika respon adalah file Excel, ini sukses dari sisi server, arahkan untuk download
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'File Excel data training berhasil dibuat. Unduhan akan segera dimulai.',
                                icon: 'success',
                                timer: 2000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            }).then(() => {
                                window.location.href = exportUrl; // Arahkan ke URL export untuk download
                            });
                        } else {
                            // Jika error lain atau respon bukan file Excel
                            let errorMessage = 'Terjadi kesalahan saat meminta data export training.';
                            try {
                                const errorResponse = JSON.parse(xhr.responseText);
                                if (errorResponse && errorResponse.message) {
                                    errorMessage = errorResponse.message;
                                }
                            } catch (e) {
                                // Abaikan error parsing jika respon bukan JSON
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: errorMessage
                            });
                        }
                    }
                });
            });

            // Handle export testing button click
            $('a[href="export_testing.php"]').on('click', function(e) {
                e.preventDefault(); // Mencegah aksi default link
                const exportUrl = $(this).attr('href');

                // Lakukan pengecekan data menggunakan AJAX
                $.ajax({
                    url: exportUrl,
                    type: 'GET',
                    dataType: 'json', // Tetapkan dataType json untuk respon error/warning
                    success: function(response) {
                        if (response.success === false) {
                            // Menampilkan pesan warning jika tidak ada data atau error dari server
                            Swal.fire({
                                icon: 'warning',
                                title: 'Peringatan!',
                                text: response.message
                            });
                        } else {
                            // Jika sukses (artinya ada data), arahkan langsung ke URL untuk download file
                            // Di sini kita berasumsi export_testing.php akan mengirim file jika tidak mengembalikan JSON success: false
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'File Excel data testing berhasil dibuat. Unduhan akan segera dimulai.',
                                icon: 'success',
                                timer: 2000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            }).then(() => {
                                window.location.href = exportUrl; // Arahkan ke URL export untuk download
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Tangani error AJAX jika koneksi atau respon non-JSON
                        // Cek jika respon adalah data biner (kemungkinan file excel), abaikan error parsing JSON
                        if (xhr.getResponseHeader('Content-Type') && xhr.getResponseHeader('Content-Type').includes('spreadsheetml.sheet')) {
                            // Jika respon adalah file Excel, ini sukses dari sisi server, arahkan untuk download
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'File Excel data testing berhasil dibuat. Unduhan akan segera dimulai.',
                                icon: 'success',
                                timer: 2000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            }).then(() => {
                                window.location.href = exportUrl; // Arahkan ke URL export untuk download
                            });
                        } else {
                            // Jika error lain atau respon bukan file Excel
                            let errorMessage = 'Terjadi kesalahan saat meminta data export testing.';
                            try {
                                const errorResponse = JSON.parse(xhr.responseText);
                                if (errorResponse && errorResponse.message) {
                                    errorMessage = errorResponse.message;
                                }
                            } catch (e) {
                                // Abaikan error parsing jika respon bukan JSON
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: errorMessage
                            });
                        }
                    }
                });
            });

            // Handle form upload Excel for Confusion Matrix
            $('#uploadExcelForm').on('submit', function(e) {
                e.preventDefault(); // Mencegah submit form default
                var formData = new FormData(this);

                // Tampilkan loading
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mengunggah dan menganalisis file Excel. Mohon tunggu sebentar.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Kirim file ke server menggunakan AJAX
                $.ajax({
                    url: 'process_confusion_matrix.php',
                    type: 'POST',
                    data: formData,
                    processData: false, // Penting: Jangan memproses data menjadi query string
                    contentType: false, // Penting: Biarkan browser mengatur Content-Type untuk FormData
                    dataType: 'json', // Mengharapkan respon JSON
                    success: function(response) {
                        Swal.close(); // Tutup loading
                        if (response.success) {
                            // Update tampilan confusion matrix dengan hasil dari server
                            $('.true-positive').text(response.matrix.TP);
                            $('.true-negative').text(response.matrix.TN);
                            $('.false-positive').text(response.matrix.FP);
                            $('.false-negative').text(response.matrix.FN);

                            $('.metric-value').each(function() {
                                const label = $(this).prev('.metric-label').text().toLowerCase();
                                if (label.includes('akurasi')) {
                                    $(this).text(response.metrics.accuracy + '% ');
                                } else if (label.includes('presisi')) {
                                    $(this).text(response.metrics.precision + '% ');
                                } else if (label.includes('recall')) {
                                    $(this).text(response.metrics.recall + '% ');
                                } else if (label.includes('f1-score')) {
                                    $(this).text(response.metrics.f1_score + '% ');
                                }
                            });

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Analisis confusion matrix selesai.'
                            });
                        } else {
                            // Menampilkan pesan error dari server
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Analisis!',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.close(); // Tutup loading
                        let errorMessage = 'Terjadi kesalahan saat komunikasi dengan server.';
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            if (errorResponse && errorResponse.message) {
                                errorMessage = errorResponse.message;
                            } else if (xhr.responseText) {
                                // Jika bukan JSON tapi ada respon teks, tampilkan sebagai error detail (misal error PHP)
                                errorMessage = 'Server Error: ' + xhr.responseText.substring(0, 200) + '...'; // Ambil sebagian
                            }
                        } catch (e) {
                            // Abaikan error parsing jika respon bukan JSON
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage
                        });
                    }
                });
            });

            // Handle tombol Proses Ulang Data Testing
            $('#btnReprocessTesting').on('click', function() {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah Anda yakin ingin memproses ulang seluruh data testing? Proses ini akan memperbarui kolom Keterangan berdasarkan model PKL saat ini.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Proses Ulang',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan loading
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Memproses ulang data testing. Mohon tunggu.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Kirim request ke script pemroses ulang
                        $.ajax({
                            url: 'reprocess_testing_data.php',
                            type: 'POST',
                            dataType: 'json',
                            success: function(response) {
                                Swal.close(); // Tutup loading
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                    }).then(() => {
                                        // Refresh tabel data testing setelah sukses
                                        testingTable.ajax.reload();
                                    });
                                } else {
                                    let errorMessage = response.message;
                                    if (response.errors && response.errors.length > 0) {
                                        // Tampilkan error detail jika ada
                                        errorMessage += '<br><br>Detail Error:\n' + response.errors.join('<br>');
                                    }
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        html: errorMessage // Gunakan html untuk menampilkan detail error
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.close(); // Tutup loading
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan saat meminta pemrosesan ulang data.'
                                });
                            }
                        });
                    }
                });
            });

            // Handle form uji coba data tunggal
            $('#formUjiTunggal').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit cara biasa

                var formData = $(this).serialize(); // Ambil semua data form
                var submitButton = $(this).find('button[type="submit"]');

                // Tampilkan status loading di tombol
                submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memprediksi...').prop('disabled', true);
                $('#hasilPrediksi').slideUp(); // Sembunyikan hasil sebelumnya

                $.ajax({
                    url: 'predict_single.php', // File PHP baru untuk memproses ini
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Tampilkan hasil
                            $('#prediksiKelas').text(response.prediction.class);
                            $('#prediksiConfidance').text('Tingkat Keyakinan: ' + response.prediction.confidence + '%');

                            // Ubah warna background berdasarkan hasil
                            const resultDiv = $('#hasilPrediksi').find('.alert');
                            resultDiv.removeClass('alert-success alert-danger');
                            if (response.prediction.class === 'Miskin') {
                                resultDiv.addClass('alert-danger');
                            } else {
                                resultDiv.addClass('alert-success');
                            }

                            $('#hasilPrediksi').slideDown(); // Tampilkan blok hasil
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat berkomunikasi dengan server.'
                        });
                    },
                    complete: function() {
                        // Kembalikan tombol ke keadaan semula
                        submitButton.html('<i class="fas fa-search me-2"></i>Prediksi Sekarang').prop('disabled', false);
                    }
                });
            });
        });
    </script>
</body>

</html>