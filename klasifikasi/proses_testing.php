<?php
require_once '../config/database.php';
require_once '../validasi.php';

// Proses data testing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    try {
        // Ambil semua input dari POST
        $input = $_POST;

        // Daftar field yang harus diisi (sesuai dengan form input, tidak termasuk 'keterangan')
        $required_fields = [
            'nik', 'nama', 'rt', 'rw', 'jenis_kelamin', 'pendidikan_terakhir', 'pekerjaan', 
            'tempat_tinggal', 'dinding', 'lantai', 'atap', 'sumber_penerangan', 
            'sumber_air_minum', 'bahan_bakar_masak', 'sumber_air', 'tempat_bab', 
            'jenis_kloset', 'aset_bergerak'
        ];

        // Validasi semua field yang diperlukan terisi
        foreach ($required_fields as $field) {
            if (!isset($input[$field]) || $input[$field] === '') {
                throw new Exception('Semua field harus diisi: ' . $field . ' kosong.');
            }
        }

        // Validasi format NIK, RT, RW, dll. (jika diperlukan validasi tambahan selain empty)
        if (!preg_match('/^[0-9]{16}$/', $input['nik'])) {
            $input['nik'] = '';
        }
        if (!preg_match('/^[0-9]{1,3}$/', $input['rt'])) {
            $input['rt'] = '';
        }
        if (!preg_match('/^[0-9]{1,3}$/', $input['rw'])) {
            $input['rw'] = '';
        }
        if (preg_match('/[0-9]/', $input['nama'])) {
            $input['nama'] = '';
        }

        // Validasi NIK
        if (!isset($_POST['nik']) || empty($_POST['nik'])) {
            throw new Exception('NIK tidak boleh kosong');
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

        // Cek apakah model.pkl ada
        $model_file = __DIR__ . '/model.pkl';
        if (!file_exists($model_file)) {
            throw new Exception('Model belum dibuat. Silakan buat model terlebih dahulu dengan mengklik tombol "Buat Model PKL".');
        }

        // Siapkan data untuk SVM
        $test_data_for_svm = [
            'pendidikan_terakhir' => $input['pendidikan_terakhir'],
            'pekerjaan' => $input['pekerjaan'],
            'tempat_tinggal' => $input['tempat_tinggal'],
            'dinding' => $input['dinding'],
            'lantai' => $input['lantai'],
            'atap' => $input['atap'],
            'sumber_air_minum' => $input['sumber_air_minum'],
            'sumber_air' => $input['sumber_air'],
            'sumber_penerangan' => $input['sumber_penerangan'],
            'bahan_bakar_masak' => $input['bahan_bakar_masak'],
            'tempat_bab' => $input['tempat_bab'],
            'jenis_kloset' => $input['jenis_kloset'],
            'aset_bergerak' => $input['aset_bergerak']
        ];

        // Konversi data ke JSON string dengan JSON_UNESCAPED_UNICODE untuk mendukung karakter non-ASCII
        $json_data = json_encode($test_data_for_svm, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // Validasi JSON sebelum dikirim
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Error encoding JSON: ' . json_last_error_msg());
        }

        // Debug: Log data yang akan dikirim
        error_log("Data yang akan dikirim ke SVM: " . $json_data);

        // Jalankan skrip Python dengan data JSON sebagai argumen
        // Gunakan base64 untuk menghindari masalah dengan karakter khusus
        $base64_json = base64_encode($json_data);
        $command = "python " . escapeshellarg(__DIR__ . "/../svm_process.py") . " " . escapeshellarg($base64_json);
        error_log("Command yang dijalankan: " . $command);
        $output = shell_exec($command);

        // Debug: Log output dari Python
        error_log("Output dari Python: " . $output);

        // Parse output JSON
        $result = json_decode($output, true);

        if ($result === null) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: Gagal memproses output dari SVM'
            ]);
            exit;
        }

        if (!$result['success']) {
            echo json_encode([
                'success' => false,
                'message' => 'Error SVM: ' . $result['message']
            ]);
            exit;
        }
        
        $hasil_klasifikasi = $result['keterangan'];

        // Simpan data testing ke database, termasuk hasil klasifikasi
        $stmt = $pdo->prepare("INSERT INTO data_testing (
            nik, nama, rt, rw, jenis_kelamin, pendidikan_terakhir, pekerjaan, 
            tempat_tinggal, dinding, lantai, atap, sumber_penerangan, 
            sumber_air_minum, bahan_bakar_masak, sumber_air, tempat_bab, 
            jenis_kloset, aset_bergerak, keterangan
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )");
        
        $stmt->execute([
            $input['nik'], $input['nama'], $input['rt'], $input['rw'], $input['jenis_kelamin'], 
            $input['pendidikan_terakhir'], $input['pekerjaan'], $input['tempat_tinggal'], 
            $input['dinding'], $input['lantai'], $input['atap'], $input['sumber_penerangan'], 
            $input['sumber_air_minum'], $input['bahan_bakar_masak'], $input['sumber_air'], 
            $input['tempat_bab'], $input['jenis_kloset'], $input['aset_bergerak'], 
            $hasil_klasifikasi
        ]);

        echo json_encode(['success' => true, 'message' => 'Data berhasil disimpan dan diklasifikasi!']);

    } catch (Exception $e) {
        // Tangkap error dan kirim response JSON error
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    // Jika bukan metode POST
    echo json_encode(['success' => false, 'message' => 'Metode request tidak diizinkan.']);
}

?> 