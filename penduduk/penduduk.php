<?php
require_once '../config/database.php';
require_once '../validasi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penduduk - Sistem Klasifikasi SVM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
        }

        .container {
            width: 100%;
            max-width: 100%;
            padding-right: 1rem;
            padding-left: 1rem;
            margin-right: auto;
            margin-left: auto;
            transition: all 0.3s ease;
        }

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

        .dataTables_scrollHeadInner {
            width: 100% !important;
        }

        .dataTables_scrollHeadInner table {
            width: 100% !important;
        }

        .navbar { margin-bottom: 2rem; }
        .card { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
        .btn-primary { margin-top: 1rem; }
        .form-label { font-weight: 500; }
        .alert { margin-top: 1rem; }
        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 2px;
        }
        .btn-action i {
            font-size: 14px;
        }
        .btn-view {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }
        .btn-view:hover {
            background-color: #138496;
            border-color: #117a8b;
            color: white;
        }
        .btn-edit {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #000;
        }
        .btn-edit:hover {
            background-color: #e0a800;
            border-color: #d39e00;
            color: #000;
        }
        .btn-delete {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }
        .btn-delete:hover {
            background-color: #c82333;
            border-color: #bd2130;
            color: white;
        }
        /* Modal Styles */
        .modal-dialog {
            max-width: 90%;
            width: 1200px;
        }
        .modal-content {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            padding: 1.25rem 2rem;
        }
        .modal-title {
            color: #0d6efd;
            font-weight: 600;
        }
        .modal-body {
            padding: 1.5rem;
            background: #fff;
        }
        .form-section {
            background-color: #f8f9fa;
            padding: 1.25rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            height: 100%;
        }
        .form-section-title {
            color: #495057;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #dee2e6;
        }
        .form-label {
            color: #495057;
            font-weight: 500;
            margin-bottom: 0.25rem;
            font-size: 0.95rem;
        }
        .form-control, .form-select {
            border-radius: 0.375rem;
            border: 1px solid #ced4da;
            padding: 0.5rem 0.75rem;
            font-size: 0.95rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 1rem;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            padding: 1.25rem 2rem;
            border-radius: 0 0 1rem 1rem;
        }
        .btn-modal {
            min-width: 120px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            font-family: inherit;
            font-weight: 600;
            letter-spacing: 0;
            padding: 0 1.5rem;
            line-height: 1;
            box-sizing: border-box;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
        }
        .btn-modal i {
            margin-right: 0.5rem;
            font-size: 1.2rem;
            vertical-align: middle;
            display: inline-block;
            line-height: 1;
        }
        .btn-modal:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-modal:active {
            transform: translateY(0);
        }
        .btn-modal.btn-light {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #6c757d;
        }
        .btn-modal.btn-light:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
            color: #495057;
        }
        .btn-modal.btn-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            border: none;
            color: white;
        }
        .btn-modal.btn-primary:hover {
            background: linear-gradient(135deg, #0b5ed7 0%, #084298 100%);
        }
        .btn-modal:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        /* Custom scrollbar for modal */
        .modal-body::-webkit-scrollbar {
            width: 8px;
        }
        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .modal-body::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        /* Equal height columns */
        .row-eq-height {
            display: flex;
            flex-wrap: wrap;
        }
        .row-eq-height > [class*='col-'] {
            display: flex;
            flex-direction: column;
        }
        .form-section {
            flex: 1;
        }
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
        .btn-batal-modal {
            min-width: 140px;
            height: 44px;
            background-color: #ffc107;
            border: none;
            color: #000;
            font-weight: 600;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .btn-batal-modal:hover {
            background-color: #e0a800;
            color: #000;
        }
        .btn-simpan-modal {
            min-width: 140px;
            height: 44px;
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .btn-simpan-modal:hover {
            background: linear-gradient(135deg, #0b5ed7 0%, #084298 100%);
            color: #fff;
        }
    </style>
</head>
<body>
    <?php include '../includes/sidebar.php'; ?>
    <div class="main-content">
        <div class="container mt-4">
            <?php 
            $showSuccess = isset($_GET['status']) && $_GET['status'] == 'success';
            $showError = isset($_GET['status']) && $_GET['status'] == 'error';
            $errorMessage = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'Terjadi kesalahan!';
            ?>

            <h2 class="mb-4">Data Penduduk</h2>
            <div class="card mb-4">
                <div class="card-body">
                    <form action="proses_penduduk.php" method="post" id="formTambahPenduduk">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="nik" class="form-label required-field">NIK</label>
                                <input type="text" class="form-control" id="nik" name="nik" required maxlength="16">
                            </div>
                            <div class="col-md-3">
                                <label for="nama" class="form-label required-field">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="col-md-3">
                                <label for="rt" class="form-label required-field">RT</label>
                                <input type="text" class="form-control" id="rt" name="rt" required maxlength="3">
                            </div>
                            <div class="col-md-3">
                                <label for="rw" class="form-label required-field">RW</label>
                                <input type="text" class="form-control" id="rw" name="rw" required maxlength="3">
                            </div>
                            <div class="col-md-3">
                                <label for="jenis_kelamin" class="form-label required-field">Jenis Kelamin</label>
                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                    <option value="laki-laki">Laki-laki</option>
                                    <option value="perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="pendidikan_terakhir" class="form-label required-field">Pendidikan Terakhir</label>
                                <select class="form-select" id="pendidikan_terakhir" name="pendidikan_terakhir" required>
                                    <option value="" disabled selected>Pilih Pendidikan</option>
                                    <option value="SD">SD</option>
                                    <option value="SLTP">SLTP</option>
                                    <option value="SLTA">SLTA</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="pekerjaan" class="form-label required-field">Pekerjaan</label>
                                <select class="form-select" id="pekerjaan" name="pekerjaan" required>
                                    <option value="" disabled selected>Pilih Pekerjaan</option>
                                    <option value="Tani">Tani</option>
                                    <option value="Pedagang">Pedagang</option>
                                    <option value="Buruh">Buruh</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="tempat_tinggal" class="form-label">Tempat Tinggal</label>
                                <select class="form-select" id="tempat_tinggal" name="tempat_tinggal" required>
                                    <option value="">Pilih Tempat Tinggal</option>
                                    <option value="bebas sewa">Bebas Sewa</option>
                                    <option value="kontrakan">Kontrakan</option>
                                    <option value="milik sendiri">Milik Sendiri</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required-field">Lantai</label>
                                <select name="lantai" class="form-select" required>
                                    <option value="" disabled selected>Pilih</option>
                                    <option value="kayu">Kayu</option>
                                    <option value="semen/bata merah">Semen/Bata Merah</option>
                                    <option value="ubin">Ubin</option>
                                    <option value="keramik">Keramik</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required-field">Dinding</label>
                                <select name="dinding" class="form-select" required>
                                    <option value="" disabled selected>Pilih</option>
                                    <option value="anyaman bambu">Anyaman Bambu</option>
                                    <option value="kayu">Kayu</option>
                                    <option value="tembok">Tembok</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required-field">Atap</label>
                                <select name="atap" class="form-select" required>
                                    <option value="" disabled selected>Pilih</option>
                                    <option value="anyaman bambu">Anyaman Bambu</option>
                                    <option value="seng">Seng</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required-field">Sumber Penerangan</label>
                                <select name="sumber_penerangan" class="form-select" required>
                                    <option value="" disabled selected>Pilih Sumber Penerangan</option>
                                    <option value="Bukan Listrik (Tanpa Meteran)">Bukan Listrik (Tanpa Meteran)</option>
                                    <option value="Listrik PLN (Tanpa Meteran)">Listrik PLN (Tanpa Meteran)</option>
                                    <option value="Listrik Non PLN (450 Watt)">Listrik Non PLN (450 Watt)</option>
                                    <option value="Listrik PLN (450 Watt)">Listrik PLN (450 Watt)</option>
                                    <option value="Listrik PLN (900 Watt)">Listrik PLN (900 Watt)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="sumber_air_minum">Sumber Air Minum</label>
                                <select class="form-control" id="sumber_air_minum" name="sumber_air_minum" required>
                                    <option value="">Pilih Sumber Air Minum</option>
                                    <option value="Sumur Bor/Pompa">Sumur Bor/Pompa</option>
                                    <option value="Sumur Terlindung">Sumur Terlindung</option>
                                    <option value="Air Isi Ulang">Air Isi Ulang</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required-field">Bahan Bakar Masak</label>
                                <select name="bahan_bakar_masak" class="form-select" required>
                                    <option value="" disabled selected>Pilih Bahan Bakar Masak</option>
                                    <option value="Gas 3Kg">Gas 3Kg</option>
                                    <option value="Listrik">Listrik</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required-field">Tempat BAB</label>
                                <select name="tempat_bab" class="form-select" required>
                                    <option value="" disabled selected>Pilih Tempat BAB</option>
                                    <option value="Umum">Umum</option>
                                    <option value="Sendiri">Sendiri</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required-field">Jenis Kloset</label>
                                <select name="jenis_kloset" class="form-select" required>
                                    <option value="" disabled selected>Pilih Jenis Kloset</option>
                                    <option value="Tidak Pakai">Tidak Pakai</option>
                                    <option value="Cemplung/Cubluk">Cemplung/Cubluk</option>
                                    <option value="Leher Angsa">Leher Angsa</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="aset_bergerak" class="form-label required-field">Aset Bergerak</label>
                                <select class="form-select" id="aset_bergerak" name="aset_bergerak" required>
                                    <option value="" disabled selected>Pilih Aset Bergerak</option>
                                    <option value="Ada">Ada</option>
                                    <option value="Tidak ada">Tidak ada</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required-field">Sumber Air</label>
                                <select name="sumber_air" class="form-select" required>
                                    <option value="" disabled selected>Pilih Sumber Air</option>
                                    <option value="Air Sungai">Air Sungai</option>
                                    <option value="PAM">PAM</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4 px-4"><i class="fas fa-save me-2"></i>Simpan Data</button>
                    </form>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-bordered table-striped table-hover" id="pendudukTable">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Pendidikan</th>
                            <th>Pekerjaan</th>
                            <th>Aset Bergerak</th>
                            <th>Tempat Tinggal</th>
                            <th>Dinding</th>
                            <th>Lantai</th>
                            <th>Atap</th>
                            <th>Sumber Air Minum</th>
                            <th>Sumber Penerangan</th>
                            <th>Bahan Bakar Masak</th>
                            <th>Tempat BAB</th>
                            <th>Jenis Kloset</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM data_penduduk ORDER BY id DESC";
                        $stmt = $pdo->query($query);
                        $no = 1;
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . formatTitleCase($row['nama']) . "</td>";
                            echo "<td>" . formatTitleCase($row['jenis_kelamin']) . "</td>";
                            echo "<td>" . formatTitleCase($row['pendidikan_terakhir']) . "</td>";
                            echo "<td>" . formatTitleCase($row['pekerjaan']) . "</td>";
                            echo "<td>" . formatTitleCase($row['aset_bergerak']) . "</td>";
                            echo "<td>" . formatTitleCase($row['tempat_tinggal']) . "</td>";
                            echo "<td>" . formatTitleCase($row['dinding']) . "</td>";
                            echo "<td>" . formatTitleCase($row['lantai']) . "</td>";
                            echo "<td>" . formatTitleCase($row['atap']) . "</td>";
                            echo "<td>" . formatTitleCase($row['sumber_air_minum']) . "</td>";
                            echo "<td>" . formatTitleCase($row['sumber_penerangan']) . "</td>";
                            echo "<td>" . formatTitleCase($row['bahan_bakar_masak']) . "</td>";
                            echo "<td>" . formatTitleCase($row['tempat_bab']) . "</td>";
                            echo "<td>" . formatTitleCase($row['jenis_kloset']) . "</td>";
                            echo "<td>" . formatTitleCase($row['keterangan']) . "</td>";
                            echo "<td>
                                    <button type='button' class='btn btn-action btn-view view-btn' data-bs-toggle='modal' data-bs-target='#viewModal' 
                                        data-nik='" . $row['nik'] . "'
                                        data-nama='" . formatTitleCase($row['nama']) . "'
                                        data-jenis-kelamin='" . formatTitleCase($row['jenis_kelamin']) . "'
                                        data-pendidikan-terakhir='" . formatTitleCase($row['pendidikan_terakhir']) . "'
                                        data-pekerjaan='" . formatTitleCase($row['pekerjaan']) . "'
                                        data-aset-bergerak='" . formatTitleCase($row['aset_bergerak']) . "'
                                        data-rt='" . $row['rt'] . "'
                                        data-rw='" . $row['rw'] . "'
                                        data-tempat-tinggal='" . formatTitleCase($row['tempat_tinggal']) . "'
                                        data-dinding='" . formatTitleCase($row['dinding']) . "'
                                        data-lantai='" . formatTitleCase($row['lantai']) . "'
                                        data-atap='" . formatTitleCase($row['atap']) . "'
                                        data-sumber-air='" . formatTitleCase($row['sumber_air_minum']) . "'
                                        data-sumber-penerangan='" . formatTitleCase($row['sumber_penerangan']) . "'
                                        data-bahan-bakar='" . formatTitleCase($row['bahan_bakar_masak']) . "'
                                        data-tempat-bab='" . formatTitleCase($row['tempat_bab']) . "'
                                        data-keterangan='" . formatTitleCase($row['keterangan']) . "'
                                        title='Lihat Detail'>
                                        <i class='fas fa-eye'></i>
                                    </button>
                                    <button type='button' class='btn btn-action btn-edit' data-id='" . $row['id'] . "' title='Edit Data'>
                                        <i class='fas fa-edit'></i>
                                    </button>
                                    <a href='hapus_penduduk.php?id=" . $row['id'] . "' class='btn btn-action btn-delete delete-btn' title='Hapus Data'>
                                        <i class='fas fa-trash'></i>
                                    </a>
                                  </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal View -->
        <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewModalLabel">Detail Data Penduduk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-light rounded-3">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="p-2 bg-white rounded shadow-sm mb-2">
                                    <span class="fw-bold">NIK:</span><br>
                                    <span id="view-nik"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2 bg-white rounded shadow-sm mb-2">
                                    <span class="fw-bold">Nama:</span><br>
                                    <span id="view-nama"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2 bg-white rounded shadow-sm mb-2">
                                    <span class="fw-bold">RT:</span><br>
                                    <span id="view-rt"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2 bg-white rounded shadow-sm mb-2">
                                    <span class="fw-bold">RW:</span><br>
                                    <span id="view-rw"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2 bg-white rounded shadow-sm mb-2">
                                    <span class="fw-bold">Jenis Kelamin:</span><br>
                                    <span id="view-jenis-kelamin"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2 bg-white rounded shadow-sm mb-2">
                                    <span class="fw-bold">Pendidikan Terakhir:</span><br>
                                    <span id="view-pendidikan"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2 bg-white rounded shadow-sm mb-2">
                                    <span class="fw-bold">Pekerjaan:</span><br>
                                    <span id="view-pekerjaan"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2 bg-white rounded shadow-sm mb-2">
                                    <span class="fw-bold">Aset Bergerak:</span><br>
                                    <span id="view-aset"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2 bg-white rounded shadow-sm mb-2">
                                    <span class="fw-bold">Tempat Tinggal:</span><br>
                                    <span id="view-tempat-tinggal"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2 bg-white rounded shadow-sm mb-2">
                                    <span class="fw-bold">Dinding:</span><br>
                                    <span id="view-dinding"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2 bg-white rounded shadow-sm mb-2">
                                    <span class="fw-bold">Lantai:</span><br>
                                    <span id="view-lantai"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2 bg-white rounded shadow-sm mb-2">
                                    <span class="fw-bold">Atap:</span><br>
                                    <span id="view-atap"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2 bg-white rounded shadow-sm mb-2">
                                    <span class="fw-bold">Sumber Air:</span><br>
                                    <span id="view-sumber-air"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2 bg-white rounded shadow-sm mb-2">
                                    <span class="fw-bold">Sumber Penerangan:</span><br>
                                    <span id="view-sumber-penerangan"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2 bg-white rounded shadow-sm mb-2">
                                    <span class="fw-bold">Bahan Bakar Masak:</span><br>
                                    <span id="view-bahan-bakar"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2 bg-white rounded shadow-sm mb-2">
                                    <span class="fw-bold">Tempat BAB:</span><br>
                                    <span id="view-tempat-bab"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2 bg-white rounded shadow-sm mb-2">
                                    <span class="fw-bold">Jenis Kloset:</span><br>
                                    <span id="view-jenis-kloset"></span>
                                </div>
                            </div>
                            <div class="col-md-12 d-flex justify-content-center">
                                <div id="view-keterangan-box" class="p-2 bg-white rounded shadow-sm mb-2 w-50 text-center">
                                    <span class="fw-bold">Keterangan:</span><br>
                                    <span id="view-keterangan"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Data Penduduk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm">
                            <input type="hidden" id="edit_id" name="id">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label required-field">NIK</label>
                                    <input type="text" class="form-control" id="edit_nik" name="nik" required maxlength="16">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required-field">Nama</label>
                                    <input type="text" class="form-control" id="edit_nama" name="nama" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required-field">RT</label>
                                    <input type="text" class="form-control" id="edit_rt" name="rt" required maxlength="3">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required-field">RW</label>
                                    <input type="text" class="form-control" id="edit_rw" name="rw" required maxlength="3">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required-field">Jenis Kelamin</label>
                                    <select class="form-select" id="edit_jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                        <option value="laki-laki">Laki-laki</option>
                                        <option value="perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required-field">Pendidikan Terakhir</label>
                                    <select class="form-select" id="edit_pendidikan_terakhir" name="pendidikan_terakhir" required>
                                        <option value="" disabled selected>Pilih Pendidikan</option>
                                        <option value="SD">SD</option>
                                        <option value="SLTP">SLTP</option>
                                        <option value="SLTA">SLTA</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required-field">Pekerjaan</label>
                                    <select class="form-select" id="edit_pekerjaan" name="pekerjaan" required>
                                        <option value="" disabled selected>Pilih Pekerjaan</option>
                                        <option value="Tani">Tani</option>
                                        <option value="Pedagang">Pedagang</option>
                                        <option value="Buruh">Buruh</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required-field">Aset Bergerak</label>
                                    <select class="form-select" id="edit_aset_bergerak" name="aset_bergerak" required>
                                        <option value="" disabled selected>Pilih Aset Bergerak</option>
                                        <option value="Ada">Ada</option>
                                        <option value="Tidak ada">Tidak ada</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required-field">Tempat Tinggal</label>
                                    <select name="tempat_tinggal" id="edit_tempat_tinggal" class="form-select" required>
                                        <option value="">Pilih Tempat Tinggal</option>
                                        <option value="bebas sewa">Bebas Sewa</option>
                                        <option value="kontrakan">Kontrakan</option>
                                        <option value="milik sendiri">Milik Sendiri</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required-field">Dinding</label>
                                    <select name="dinding" id="edit_dinding" class="form-select" required>
                                        <option value="" disabled selected>Pilih</option>
                                        <option value="anyaman bambu">Anyaman Bambu</option>
                                        <option value="kayu">Kayu</option>
                                        <option value="tembok">Tembok</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required-field">Lantai</label>
                                    <select name="lantai" id="edit_lantai" class="form-select" required>
                                        <option value="" disabled selected>Pilih</option>
                                        <option value="kayu">Kayu</option>
                                        <option value="semen/bata merah">Semen/Bata Merah</option>
                                        <option value="ubin">Ubin</option>
                                        <option value="keramik">Keramik</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required-field">Atap</label>
                                    <select name="atap" id="edit_atap" class="form-select" required>
                                        <option value="" disabled selected>Pilih</option>
                                        <option value="anyaman bambu">Anyaman Bambu</option>
                                        <option value="seng">Seng</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="edit_sumber_air_minum">Sumber Air Minum</label>
                                    <select class="form-control" id="edit_sumber_air_minum" name="sumber_air_minum" required>
                                        <option value="">Pilih Sumber Air Minum</option>
                                        <option value="Sumur Bor/Pompa">Sumur Bor/Pompa</option>
                                        <option value="Sumur Terlindung">Sumur Terlindung</option>
                                        <option value="Air Isi Ulang">Air Isi Ulang</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required-field">Sumber Penerangan</label>
                                    <select name="sumber_penerangan" id="edit_sumber_penerangan" class="form-select" required>
                                        <option value="" disabled selected>Pilih Sumber Penerangan</option>
                                        <option value="Bukan Listrik (Tanpa Meteran)">Bukan Listrik (Tanpa Meteran)</option>
                                        <option value="Listrik PLN (Tanpa Meteran)">Listrik PLN (Tanpa Meteran)</option>
                                        <option value="Listrik Non PLN (450 Watt)">Listrik Non PLN (450 Watt)</option>
                                        <option value="Listrik PLN (450 Watt)">Listrik PLN (450 Watt)</option>
                                        <option value="Listrik PLN (900 Watt)">Listrik PLN (900 Watt)</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required-field">Bahan Bakar Masak</label>
                                    <select name="bahan_bakar_masak" id="edit_bahan_bakar_masak" class="form-select" required>
                                        <option value="" disabled selected>Pilih Bahan Bakar Masak</option>
                                        <option value="Gas 3Kg">Gas 3Kg</option>
                                        <option value="Listrik">Listrik</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required-field">Tempat BAB</label>
                                    <select name="tempat_bab" id="edit_tempat_bab" class="form-select" required>
                                        <option value="" disabled selected>Pilih Tempat BAB</option>
                                        <option value="Umum">Umum</option>
                                        <option value="Sendiri">Sendiri</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required-field">Jenis Kloset</label>
                                    <select name="jenis_kloset" id="edit_jenis_kloset" class="form-select" required>
                                        <option value="" disabled selected>Pilih Jenis Kloset</option>
                                        <option value="Tidak Pakai">Tidak Pakai</option>
                                        <option value="Cemplung/Cubluk">Cemplung/Cubluk</option>
                                        <option value="Leher Angsa">Leher Angsa</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required-field">Sumber Air</label>
                                    <select name="sumber_air" class="form-select" required>
                                        <option value="" disabled selected>Pilih Sumber Air</option>
                                        <option value="Air Sungai">Air Sungai</option>
                                        <option value="PAM">PAM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer d-flex gap-3 justify-content-end align-items-center">
                                <button type="button" class="btn btn-batal-modal d-flex align-items-center justify-content-center px-4" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Batal
                                </button>
                                <button type="submit" class="btn btn-simpan-modal d-flex align-items-center justify-content-center px-4" id="submitEditPenduduk">
                                    <i class="fas fa-save me-2"></i>Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            // Show success/error message if exists
            <?php if ($showSuccess): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data berhasil diproses!',
                    showConfirmButton: false,
                    timer: 1000
                }).then(() => {
                    // Remove status parameter and refresh page
                    window.location.href = window.location.pathname;
                });
            <?php endif; ?>

            <?php if ($showError): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '<?php echo $errorMessage; ?>'
                }).then(() => {
                    // Remove status parameter from URL
                    window.history.replaceState({}, document.title, window.location.pathname);
                });
            <?php endif; ?>

            $('#pendudukTable').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                }
            });

            // Handle form submission
            $('#formTambahPenduduk').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                
                $.ajax({
                    url: 'proses_penduduk.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
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

            // Handle View Button Click
            $('.view-btn').click(function() {
                const data = $(this).data();
                $('#view-nik').text(data.nik);
                $('#view-nama').text(data.nama);
                $('#view-jenis-kelamin').text(data.jenisKelamin);
                $('#view-pendidikan').text(data.pendidikanTerakhir);
                $('#view-pekerjaan').text(data.pekerjaan);
                $('#view-aset').text(data.asetBergerak);
                $('#view-rt').text(data.rt);
                $('#view-rw').text(data.rw);
                $('#view-tempat-tinggal').text(data.tempatTinggal);
                $('#view-dinding').text(data.dinding);
                $('#view-lantai').text(data.lantai);
                $('#view-atap').text(data.atap);
                $('#view-sumber-air').text(data.sumberAir);
                $('#view-sumber-penerangan').text(data.sumberPenerangan);
                $('#view-bahan-bakar').text(data.bahanBakar);
                $('#view-tempat-bab').text(data.tempatBab);
                $('#view-jenis-kloset').text(data.jenisKloset);
                $('#view-keterangan').text(data.keterangan);
                // Tambahkan warna pada box keterangan
                const ketBox = $('#view-keterangan-box');
                ketBox.removeClass('bg-success-subtle text-success bg-danger-subtle text-danger');
                if ((data.keterangan || '').toLowerCase() === 'miskin') {
                    ketBox.addClass('bg-danger-subtle text-danger fw-bold');
                } else if ((data.keterangan || '').toLowerCase() === 'tidak miskin') {
                    ketBox.addClass('bg-success-subtle text-success fw-bold');
                }
            });

            // Handle delete button click
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                const deleteUrl = $(this).attr('href');
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        showConfirmButton: false,
                                        timer: 1000
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan saat menghapus data'
                                });
                            }
                        });
                    }
                });
            });

            // Validasi input NIK hanya angka
            $('#edit_nik').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // Validasi input RT dan RW hanya angka
            $('#edit_rt, #edit_rw').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // Validasi panjang NIK
            $('#edit_nik').on('change', function() {
                if (this.value.length !== 16) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'NIK harus 16 digit'
                    });
                    this.value = '';
                }
            });

            // Validasi panjang RT dan RW
            $('#edit_rt, #edit_rw').on('change', function() {
                if (this.value.length > 3) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'RT/RW maksimal 3 digit'
                    });
                    this.value = this.value.slice(0, 3);
                }
            });

            // Fungsi untuk membuka modal edit
            function openEditModal(id) {
                $.ajax({
                    url: 'get_penduduk.php',
                    type: 'GET',
                    data: { id: id },
                    success: function(response) {
                        if (response.success) {
                            const data = response.data;
                            $('#edit_id').val(data.id);
                            $('#edit_nik').val(data.nik);
                            $('#edit_nama').val(data.nama);
                            $('#edit_rt').val(data.rt);
                            $('#edit_rw').val(data.rw);
                            $('#edit_tempat_tinggal').val(data.tempat_tinggal);
                            $('#edit_dinding').val(data.dinding);
                            $('#edit_lantai').val(data.lantai);
                            $('#edit_atap').val(data.atap);
                            $('#edit_sumber_air_minum').val(data.sumber_air_minum);
                            $('#edit_sumber_penerangan').val(data.sumber_penerangan);
                            $('#edit_bahan_bakar_masak').val(data.bahan_bakar_masak);
                            $('#edit_tempat_bab').val(data.tempat_bab);
                            $('#edit_jenis_kelamin').val(data.jenis_kelamin);
                            $('#edit_pendidikan_terakhir').val(data.pendidikan_terakhir);
                            $('#edit_pekerjaan').val(data.pekerjaan);
                            $('#edit_aset_bergerak').val(data.aset_bergerak);
                            $('#edit_jenis_kloset').val(data.jenis_kloset);
                            $('#editModal').modal('show');
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Terjadi kesalahan saat mengambil data', 'error');
                    }
                });
            }

            // Event handler untuk tombol edit
            $(document).on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                openEditModal(id);
            });

            // Event handler untuk tombol proses klasifikasi di modal
            $('#submitEditPenduduk').click(function() {
                const formData = $('#editForm').serialize();
                
                $.ajax({
                    url: 'proses_penduduk.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#editModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1000
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat memproses data'
                        });
                    }
                });
            });

            // Reset form saat modal ditutup
            $('#editModal').on('hidden.bs.modal', function() {
                $('#editForm')[0].reset();
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Validasi NIK hanya angka
            const nikInput = document.getElementById('nik');
            if (nikInput) {
                nikInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }

            // Validasi Nama hanya huruf dan spasi
            const namaInput = document.getElementById('nama');
            if (namaInput) {
                namaInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
                });
            }

            // Validasi RT dan RW hanya angka
            const rtInput = document.getElementById('rt');
            if (rtInput) {
                rtInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }
            const rwInput = document.getElementById('rw');
            if (rwInput) {
                rwInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }
        });
    </script>
</body>
</html> 