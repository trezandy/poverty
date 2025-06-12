<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');
ob_clean();

if (!isset($_GET['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID tidak valid!'
    ]);
    exit();
}

try {
    $nik = $_GET['id'];
    // Cek apakah data ada
    $cek = $pdo->prepare("SELECT nik FROM data_testing WHERE nik = ?");
    $cek->execute([$nik]);
    if (!$cek->fetch()) {
        echo json_encode([
            'success' => false,
            'message' => 'Data tidak ditemukan! (NIK: ' . htmlspecialchars($nik) . ')'
        ]);
        exit();
    }
    // Hapus data dari database
    $query = "DELETE FROM data_testing WHERE nik = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$nik]);
    if ($stmt->rowCount() == 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Data gagal dihapus! (NIK: ' . htmlspecialchars($nik) . ') Kemungkinan data sudah tidak ada atau ada constraint di database.'
        ]);
        exit();
    }
    echo json_encode([
        'success' => true,
        'message' => 'Data berhasil dihapus!'
    ]);
    exit();
} catch (PDOException $e) {
    $msg = $e->getCode() == '23000' ? 'Data tidak dapat dihapus karena masih digunakan di tabel lain (constraint).' : 'Terjadi kesalahan: ' . $e->getMessage();
    echo json_encode([
        'success' => false,
        'message' => $msg
    ]);
    exit();
}
?> 