<?php
require_once '../config/database.php';
require_once '../validasi.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Method tidak diizinkan'
    ]);
    exit;
}

// Validasi input (sesuai dengan form penduduk)
$nik = $_POST['nik'] ?? '';
$nama = $_POST['nama'] ?? '';
$rt = $_POST['rt'] ?? '';
$rw = $_POST['rw'] ?? '';
$jenis_kelamin_raw = $_POST['jenis_kelamin'] ?? '';
$jenis_kelamin = trim(strtolower($jenis_kelamin_raw)); // Normalize to lowercase and trim
$pendidikan_terakhir = $_POST['pendidikan_terakhir'] ?? ''; // Menambahkan pendidikan_terakhir
$pekerjaan = $_POST['pekerjaan'] ?? ''; // Menambahkan pekerjaan
$tempat_tinggal = $_POST['tempat_tinggal'] ?? '';
$dinding = $_POST['dinding'] ?? '';
$lantai = $_POST['lantai'] ?? '';
$atap = $_POST['atap'] ?? '';
$sumber_air_minum = $_POST['sumber_air_minum'] ?? '';
$sumber_air = $_POST['sumber_air'] ?? ''; // Menambahkan sumber_air
$sumber_penerangan = $_POST['sumber_penerangan'] ?? '';
$bahan_bakar_masak = $_POST['bahan_bakar_masak'] ?? '';
$tempat_bab = $_POST['tempat_bab'] ?? '';
$jenis_kloset = $_POST['jenis_kloset'] ?? ''; // Menambahkan jenis_kloset
$aset_bergerak = $_POST['aset_bergerak'] ?? ''; // Menambahkan aset_bergerak

// Validasi NIK
if (!validateNIK($nik)) {
    echo json_encode([
        'success' => false,
        'message' => 'NIK tidak valid'
    ]);
    exit;
}

// Validasi nama
if (!validateName($nama)) {
    echo json_encode([
        'success' => false,
        'message' => 'Nama tidak valid'
    ]);
    exit;
}

// Validasi RT
if (!validateRT($rt)) {
    echo json_encode([
        'success' => false,
        'message' => 'RT tidak valid'
    ]);
    exit;
}

// Validasi RW
if (!validateRW($rw)) {
    echo json_encode([
        'success' => false,
        'message' => 'RW tidak valid'
    ]);
    exit;
}

// Validasi tempat tinggal
if (!in_array($tempat_tinggal, ['bebas sewa', 'kontrakan', 'milik sendiri'])) {
    echo json_encode(['success' => false, 'message' => 'Nilai tempat tinggal tidak valid']);
    exit;
}

// Validasi atap
if (!in_array($atap, ['anyaman bambu', 'seng'])) {
    echo json_encode(['success' => false, 'message' => 'Nilai atap tidak valid']);
    exit;
}

// Validasi sumber air minum
if (!in_array($sumber_air_minum, ['Sumur Bor/Pompa', 'Sumur Terlindung', 'Air Isi Ulang'])) {
    echo json_encode(['success' => false, 'message' => 'Nilai sumber air minum tidak valid']);
    exit;
}

// Validasi tempat BAB
if (!in_array($tempat_bab, ['Umum', 'Sendiri'])) {
    echo json_encode(['success' => false, 'message' => 'Nilai tempat BAB tidak valid']);
    exit;
}

// Validasi jenis_kelamin (after trimming and lowering case)
if (!in_array($jenis_kelamin, ['laki-laki', 'perempuan'])) {
    echo json_encode(['success' => false, 'message' => 'Nilai jenis kelamin tidak valid']);
    exit;
}

// Validasi pendidikan_terakhir
if (!in_array($pendidikan_terakhir, ['SD', 'SLTP', 'SLTA'])) {
    echo json_encode(['success' => false, 'message' => 'Nilai pendidikan terakhir tidak valid']);
    exit;
}

// Validasi pekerjaan
if (!in_array($pekerjaan, ['Buruh', 'Tani', 'Pedagang'])) {
    echo json_encode(['success' => false, 'message' => 'Nilai pekerjaan tidak valid']);
    exit;
}

// Validasi dinding
if (!in_array($dinding, ['anyaman bambu', 'kayu', 'tembok'])) {
    echo json_encode(['success' => false, 'message' => 'Nilai dinding tidak valid']);
    exit;
}

// Validasi lantai
if (!in_array($lantai, ['kayu', 'semen/bata merah', 'ubin', 'keramik'])) {
    echo json_encode(['success' => false, 'message' => 'Nilai lantai tidak valid']);
    exit;
}

// Validasi sumber air
if (!in_array($sumber_air, ['Air Sungai', 'PAM'])) {
    echo json_encode(['success' => false, 'message' => 'Nilai sumber air tidak valid']);
    exit;
}

// Validasi bahan bakar masak
if (!in_array($bahan_bakar_masak, ['Gas 3Kg', 'Listrik'])) {
    echo json_encode(['success' => false, 'message' => 'Nilai bahan bakar masak tidak valid']);
    exit;
}

// Validasi jenis kloset
if (!in_array($jenis_kloset, ['Tidak Pakai', 'Cemplung/Cubluk', 'Leher Angsa'])) {
    echo json_encode(['success' => false, 'message' => 'Nilai jenis kloset tidak valid']);
    exit;
}

// Validasi aset bergerak
if (!in_array($aset_bergerak, ['Tidak ada', 'Ada'])) {
    echo json_encode(['success' => false, 'message' => 'Nilai aset bergerak tidak valid']);
    exit;
}

try {
    // Cek apakah NIK sudah terdaftar di data_penduduk
    $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM data_penduduk WHERE nik = ?");
    $check_stmt->execute([$nik]);
    if ($check_stmt->fetchColumn() > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'NIK sudah terdaftar'
        ]);
        exit;
    }

    // Simpan data ke database (tabel data_penduduk)
    $stmt = $pdo->prepare("INSERT INTO data_penduduk (
        nik, nama, rt, rw, jenis_kelamin, pendidikan_terakhir, pekerjaan, 
        tempat_tinggal, dinding, lantai, atap, sumber_penerangan, 
        sumber_air_minum, bahan_bakar_masak, sumber_air, tempat_bab, 
        jenis_kloset, aset_bergerak
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
    )");
    
    $stmt->execute([
        $nik, $nama, $rt, $rw, $jenis_kelamin, $pendidikan_terakhir, $pekerjaan, 
        $tempat_tinggal, $dinding, $lantai, $atap, $sumber_penerangan, 
        $sumber_air_minum, $bahan_bakar_masak, $sumber_air, $tempat_bab, 
        $jenis_kloset, $aset_bergerak
    ]);

    // Proses klasifikasi menggunakan SVM
    $python_script = "../svm_process.py";
    $command = "python " . escapeshellarg($python_script) . " " . escapeshellarg($nik);
    $output = shell_exec($command);
    
    if ($output !== null) {
        $result = json_decode($output, true);
        if ($result && isset($result['prediction'])) {
            // Update keterangan di database
            $update_stmt = $pdo->prepare("UPDATE data_penduduk SET keterangan = ? WHERE nik = ?");
            $update_stmt->execute([$result['prediction'], $nik]);
        }
    }

    echo json_encode(['success' => true, 'message' => 'Data penduduk berhasil disimpan!']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?> 