<?php
// Include error handler
require_once __DIR__ . '/../includes/error_handler.php';

$host = 'localhost';
$dbname = 'data_svmm';
$username = 'root';
$password = '';

try {
    // Buat koneksi PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    error_log("Database connection successful");

    // Buat koneksi mysqli untuk kompatibilitas
    $koneksi = mysqli_connect($host, $username, $password, $dbname);
    if (!$koneksi) {
        throw new Exception("Koneksi mysqli gagal: " . mysqli_connect_error());
    }

    // Skema tabel yang sama untuk data_penduduk, data_training, dan data_testing
    $tableSchema = " (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(255) NOT NULL,
        jenis_kelamin ENUM('laki-laki', 'perempuan'),
        pendidikan_terakhir ENUM('SD', 'SLTP', 'SLTA'),
        pekerjaan ENUM('Tani', 'Pedagang', 'Buruh'),
        aset_bergerak ENUM('Ada', 'Tidak ada'),
        tempat_tinggal ENUM('lainnya', 'bebas sewa', 'milik sendiri', 'kontrakan'),
        atap ENUM('seng', 'anyaman bambu', 'anyaman'),
        sumber_air_minum ENUM('Sumur Bor/Pompa', 'Sumur Terlindung', 'Air Isi Ulang') NOT NULL,
        tempat_bab ENUM('Umum', 'Sendiri'),
        jenis_kloset ENUM('Tidak Pakai', 'Cemplung/Cubluk', 'Leher Angsa') NOT NULL,
        keterangan ENUM('Miskin', 'Tidak Miskin')
    );";

    // --- Membuat semua tabel yang diperlukan JIKA BELUM ADA ---

    // 1. Buat tabel data_penduduk
    $pdo->exec("CREATE TABLE IF NOT EXISTS data_penduduk" . $tableSchema);
    error_log("Table 'data_penduduk' created or already exists.");

    // 2. Buat tabel data_training
    $pdo->exec("CREATE TABLE IF NOT EXISTS data_training" . $tableSchema);
    error_log("Table 'data_training' created or already exists.");

    // 3. Buat tabel data_testing
    $pdo->exec("CREATE TABLE IF NOT EXISTS data_testing" . $tableSchema);
    error_log("Table 'data_testing' created or already exists.");

    // 4. Buat tabel confusion_matrix
    $pdo->exec("CREATE TABLE IF NOT EXISTS confusion_matrix (
        id INT AUTO_INCREMENT PRIMARY KEY,
        actual_class ENUM('Miskin', 'Tidak Miskin') NOT NULL,
        predicted_class ENUM('Miskin', 'Tidak Miskin') NOT NULL
    );");
    error_log("Table 'confusion_matrix' created or already exists.");

    // echo "Semua tabel berhasil disiapkan dan database siap digunakan!";
} catch (PDOException $e) {
    error_log("Database operation failed: " . $e->getMessage());
    die("Operasi database gagal: " . $e->getMessage());
} catch (Exception $e) {
    error_log("A general error occurred: " . $e->getMessage());
    die("Terjadi kesalahan: " . $e->getMessage());
}

global $pdo, $koneksi;
