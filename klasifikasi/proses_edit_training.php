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
        empty($_POST['keterangan']) || empty($_POST['nik_lama'])) {
        throw new Exception("Semua field harus diisi!");
    }

    // Validasi NIK
    if (!preg_match('/^[0-9]{16}$/', $_POST['nik'])) {
        throw new Exception("NIK harus berupa 16 digit angka!");
    }

    // Validasi RT/RW
    if (!preg_match('/^[0-9]{1,3}$/', $_POST['rt']) || !preg_match('/^[0-9]{1,3}$/', $_POST['rw'])) {
        throw new Exception("RT dan RW harus berupa angka maksimal 3 digit!");
    }

    // Cek duplikasi NIK (kecuali NIK yang sedang diedit)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM data_training WHERE nik = ? AND nik != ?");
    $stmt->execute([$_POST['nik'], $_POST['nik_lama']]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception("NIK sudah terdaftar!");
    }

    // Update data
    $stmt = $pdo->prepare("UPDATE data_training SET 
        nik = ?,
        nama = ?,
        jenis_kelamin = ?,
        pendidikan_terakhir = ?,
        pekerjaan = ?,
        aset_bergerak = ?,
        rt = ?,
        rw = ?,
        tempat_tinggal = ?,
        dinding = ?,
        lantai = ?,
        atap = ?,
        sumber_air_minum = ?,
        sumber_air = ?,
        sumber_penerangan = ?,
        bahan_bakar_masak = ?,
        tempat_bab = ?,
        jenis_kloset = ?,
        keterangan = ?
        WHERE nik = ?");

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
        $_POST['keterangan'],
        $_POST['nik_lama']
    ]);

    if ($stmt->rowCount() == 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Tidak ada perubahan data.'
        ]);
        exit();
    }

    echo json_encode([
        'success' => true,
        'message' => 'Data berhasil diperbarui!'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 