<?php
session_start();
require_once '../config/database.php';
require_once '../validasi.php';

header('Content-Type: application/json');

try {
    // Debug: Cek koneksi database
    if (!isset($pdo)) {
        throw new Exception("Koneksi database tidak tersedia");
    }

    $query = "SELECT nik, nama, keterangan FROM data_training ORDER BY nama ASC";
    $stmt = $pdo->query($query);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Debug: Cek jumlah data
    if (empty($data)) {
        echo json_encode([
            'draw' => 1,
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => []
        ]);
        exit;
    }

    // Format data sesuai DataTables
    echo json_encode([
        'draw' => 1,
        'recordsTotal' => count($data),
        'recordsFiltered' => count($data),
        'data' => $data
    ]);
} catch (Exception $e) {
    echo json_encode([
        'draw' => 1,
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => [],
        'error' => $e->getMessage()
    ]);
}
