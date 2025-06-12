<?php
header('Content-Type: application/json');
set_time_limit(300);

require_once '../config/database.php';

// Hanya izinkan metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Metode request tidak diizinkan.']);
    exit;
}

$temp_file = tempnam(sys_get_temp_dir(), 'reprocess_');

try {
    if (!isset($pdo)) throw new Exception('Koneksi database tidak tersedia.');

    $model_pkl_path = __DIR__ . '/svm_model.pkl';
    if (!file_exists($model_pkl_path)) throw new Exception('File model (svm_model.pkl) tidak ditemukan. Silakan jalankan proses training terlebih dahulu.');

    // 1. Ambil SEMUA data testing
    $stmt = $pdo->query("SELECT * FROM data_testing");
    $testingData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($testingData)) throw new Exception('Tidak ada data di tabel data_testing untuk diproses.');

    // 2. Simpan semua data ke satu file JSON sementara
    file_put_contents($temp_file, json_encode($testingData));

    // 3. Jalankan skrip Python HANYA SATU KALI
    $command = "python predict.py " . escapeshellarg($temp_file);
    $output = shell_exec($command);
    if ($output === null) throw new Exception("Gagal mengeksekusi skrip Python (predict.py).");

    $result = json_decode($output, true);
    if (!isset($result['success']) || !$result['success']) {
        $errorMsg = $result['message'] ?? 'Error tidak diketahui dari skrip Python.';
        throw new Exception($errorMsg);
    }

    // 4. Update database berdasarkan hasil prediksi massal
    $predictions = $result['predictions'];
    $updatedCount = 0;
    $pdo->beginTransaction(); // Mulai transaksi untuk kecepatan
    $update_stmt = $pdo->prepare("UPDATE data_testing SET keterangan = ? WHERE nik = ?");

    foreach ($predictions as $nik => $keterangan) {
        $update_stmt->execute([$keterangan, $nik]);
        $updatedCount++;
    }
    $pdo->commit(); // Selesaikan transaksi

    echo json_encode([
        'success' => true,
        'message' => "Pemrosesan ulang selesai. " . $updatedCount . " data berhasil diperbarui."
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    // 5. Selalu hapus file sementara
    if (file_exists($temp_file)) {
        unlink($temp_file);
    }
}
