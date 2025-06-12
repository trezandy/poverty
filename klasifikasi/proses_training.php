<?php
session_start();
require_once '../config/database.php';
require_once '../validasi.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Method tidak diizinkan'
    ]);
    exit();
}

try {
    // Validasi input
    if (empty($_POST['nik']) || empty($_POST['nama']) || empty($_POST['jenis_kelamin']) || 
        empty($_POST['pendidikan_terakhir']) || empty($_POST['pekerjaan']) || empty($_POST['aset_bergerak']) || 
        empty($_POST['rt']) || empty($_POST['rw']) || empty($_POST['tempat_tinggal']) || 
        empty($_POST['dinding']) || empty($_POST['lantai']) || empty($_POST['atap']) || 
        empty($_POST['sumber_air_minum']) || empty($_POST['sumber_air']) || empty($_POST['sumber_penerangan']) || 
        empty($_POST['bahan_bakar_masak']) || empty($_POST['tempat_bab']) || empty($_POST['jenis_kloset']) || 
        empty($_POST['keterangan'])) {
        throw new Exception("Semua field harus diisi!");
    }

    // Validasi NIK
    if (!preg_match('/^[0-9]{16}$/', $_POST['nik'])) {
        $_POST['nik'] = '';
    }

    // Validasi RT/RW
    if (!preg_match('/^[0-9]{1,3}$/', $_POST['rt'])) {
        $_POST['rt'] = '';
    }
    if (!preg_match('/^[0-9]{1,3}$/', $_POST['rw'])) {
        $_POST['rw'] = '';
    }

    // Validasi Nama
    if (preg_match('/[0-9]/', $_POST['nama'])) {
        $_POST['nama'] = '';
    }

    // Cek apakah NIK sudah terdaftar di data_training
    $check_training = $pdo->prepare("SELECT COUNT(*) FROM data_training WHERE nik = ?");
    $check_training->execute([$_POST['nik']]);
    if ($check_training->fetchColumn() > 0) {
        throw new Exception('NIK sudah terdaftar di data training');
    }

    // Cek apakah NIK sudah terdaftar di data_testing
    $check_testing = $pdo->prepare("SELECT COUNT(*) FROM data_testing WHERE nik = ?");
    $check_testing->execute([$_POST['nik']]);
    if ($check_testing->fetchColumn() > 0) {
        throw new Exception('NIK sudah terdaftar di data testing');
    }

    // Insert data
    $stmt = $pdo->prepare("INSERT INTO data_training (
        nik, nama, jenis_kelamin, pendidikan_terakhir, pekerjaan, aset_bergerak,
        rt, rw, tempat_tinggal, dinding, lantai, atap, sumber_air_minum,
        sumber_air, sumber_penerangan, bahan_bakar_masak, tempat_bab,
        jenis_kloset, keterangan
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
    )");

    $stmt->execute([
        $_POST['nik'],
        $_POST['nama'],
        $_POST['jenis_kelamin'],
        $_POST['pendidikan_terakhir'],
        $_POST['pekerjaan'],
        $_POST['aset_bergerak'],
        $_POST['rt'],
        $_POST['rw'],
        $_POST['tempat_tinggal'],
        $_POST['dinding'],
        $_POST['lantai'],
        $_POST['atap'],
        $_POST['sumber_air_minum'],
        $_POST['sumber_air'],
        $_POST['sumber_penerangan'],
        $_POST['bahan_bakar_masak'],
        $_POST['tempat_bab'],
        $_POST['jenis_kloset'],
        $_POST['keterangan']
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Data berhasil disimpan!'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 