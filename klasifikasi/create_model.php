<?php
// Set header ke JSON
header('Content-Type: application/json');

// Set batas waktu menjadi 5 menit untuk proses training yang mungkin lama
set_time_limit(300);

// Memanggil koneksi database
require_once '../config/database.php';

// Cek koneksi
if (!isset($pdo)) {
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal.']);
    exit;
}

// Tentukan path untuk file sementara. Pastikan folder ini bisa ditulisi oleh server.
$temp_dir = __DIR__; // Menyimpan di folder yang sama dengan skrip ini

$train_file = tempnam($temp_dir, 'train_');
$test_file = tempnam($temp_dir, 'test_');

try {
    // 1. Ambil data training dari database
    $stmt_train = $pdo->query("SELECT * FROM data_training");
    $data_training = $stmt_train->fetchAll(PDO::FETCH_ASSOC);

    // 2. Ambil data testing dari database
    $stmt_test = $pdo->query("SELECT * FROM data_testing");
    $data_testing = $stmt_test->fetchAll(PDO::FETCH_ASSOC);

    if (empty($data_training) || empty($data_testing)) {
        throw new Exception('Data training atau data testing kosong. Proses tidak dapat dilanjutkan.');
    }

    // 3. Tulis data ke file JSON sementara
    file_put_contents($train_file, json_encode($data_training));
    file_put_contents($test_file, json_encode($data_testing));

    // 4. Jalankan skrip Python dengan path file sebagai argumen
    $command = "python svm_classifier.py " . escapeshellarg($train_file) . " " . escapeshellarg($test_file);

    $output = shell_exec($command);

    if ($output === null) {
        throw new Exception("Gagal mengeksekusi skrip Python. Cek PHP error log untuk detail.");
    }

    // 5. Decode hasil JSON dari Python
    $result = json_decode($output, true);

    if (isset($result['success']) && $result['success']) {
        // 6. Jika sukses, simpan hasil ke tabel confusion_matrix
        $matrix = $result['matrix'];

        $pdo->exec("TRUNCATE TABLE confusion_matrix");
        $insert_stmt = $pdo->prepare("INSERT INTO confusion_matrix (actual_class, predicted_class) VALUES (?, ?)");

        for ($i = 0; $i < $matrix['TP']; $i++) {
            $insert_stmt->execute(['Miskin', 'Miskin']);
        }
        for ($i = 0; $i < $matrix['TN']; $i++) {
            $insert_stmt->execute(['Tidak Miskin', 'Tidak Miskin']);
        }
        for ($i = 0; $i < $matrix['FP']; $i++) {
            $insert_stmt->execute(['Tidak Miskin', 'Miskin']);
        }
        for ($i = 0; $i < $matrix['FN']; $i++) {
            $insert_stmt->execute(['Miskin', 'Tidak Miskin']);
        }

        echo json_encode($result);
    } else {
        $errorMessage = isset($result['message']) ? $result['message'] : 'Terjadi kesalahan tidak diketahui di skrip Python.';
        throw new Exception($errorMessage);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    // 7. Selalu hapus file sementara setelah selesai
    if (file_exists($train_file)) {
        unlink($train_file);
    }
    if (file_exists($test_file)) {
        unlink($test_file);
    }
}
