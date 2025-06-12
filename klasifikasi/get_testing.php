<?php
session_start();
require_once '../config/database.php';
require_once '../validasi.php';

// Pastikan koneksi database tersedia
if (!isset($pdo) || $pdo === null) {
    die(json_encode([
        'success' => false,
        'message' => 'Koneksi database tidak tersedia'
    ]));
}

header('Content-Type: application/json');

try {
    // Jika ada parameter nik, ambil detail data
    if (isset($_GET['nik'])) {
        $nik = $_GET['nik'];
        $query = "SELECT * FROM data_testing WHERE nik = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$nik]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            echo json_encode([
                'success' => true,
                'data' => $data
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    } else {
        // Jika tidak ada parameter nik, ambil semua data untuk tabel
        $query = "SELECT nik, nama, keterangan FROM data_testing ORDER BY nama ASC";
        $stmt = $pdo->query($query);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }
} catch (PDOException $e) {
    // Handle error
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}
