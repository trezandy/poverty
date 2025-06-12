<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID tidak valid!'
    ]);
    exit();
}

try {
    $id = $_GET['id'];
    
    // Cek apakah data exists
    $check_query = "SELECT id FROM data_penduduk WHERE id = ?";
    $check_stmt = $pdo->prepare($check_query);
    $check_stmt->execute([$id]);
    
    if ($check_stmt->rowCount() === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Data tidak ditemukan!'
        ]);
        exit();
    }
    
    // Hapus data dari database
    $query = "DELETE FROM data_penduduk WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);

    echo json_encode([
        'success' => true,
        'message' => 'Data berhasil dihapus!'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}
?> 