<?php
require_once '../config/database.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Log untuk debugging
        error_log('Starting confusion matrix processing');
        
        // Debug POST data
        error_log('POST data: ' . print_r($_POST, true));
        error_log('FILES data: ' . print_r($_FILES, true));
        
        // Validasi koneksi database
        if (!isset($pdo)) {
            throw new Exception('Koneksi database tidak tersedia');
        }
        
        // Validasi file upload
        if (!isset($_FILES['excel_file'])) {
            throw new Exception('Tidak ada file yang diupload');
        }

        if ($_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'File terlalu besar (melebihi upload_max_filesize)',
                UPLOAD_ERR_FORM_SIZE => 'File terlalu besar (melebihi MAX_FILE_SIZE)',
                UPLOAD_ERR_PARTIAL => 'File hanya terupload sebagian',
                UPLOAD_ERR_NO_FILE => 'Tidak ada file yang diupload',
                UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ditemukan',
                UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
                UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh ekstensi PHP'
            ];
            $errorMessage = isset($errorMessages[$_FILES['excel_file']['error']]) 
                ? $errorMessages[$_FILES['excel_file']['error']] 
                : 'Unknown upload error';
            error_log('File upload error: ' . $errorMessage);
            throw new Exception($errorMessage);
        }

        // Validasi tipe file
        $allowedTypes = [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/octet-stream' // Untuk beberapa sistem yang mengirim dengan tipe ini
        ];
        $fileType = $_FILES['excel_file']['type'];
        
        // Validasi ekstensi file
        $fileExtension = strtolower(pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['xls', 'xlsx'];
        
        if (!in_array($fileType, $allowedTypes) && !in_array($fileExtension, $allowedExtensions)) {
            error_log('Invalid file type: ' . $fileType . ', extension: ' . $fileExtension);
            throw new Exception('Format file tidak valid. Gunakan file Excel (.xls atau .xlsx)');
        }

        $inputFileName = $_FILES['excel_file']['tmp_name'];
        
        // Validasi file exists
        if (!file_exists($inputFileName)) {
            error_log('Temporary file not found: ' . $inputFileName);
            throw new Exception('File temporary tidak ditemukan');
        }

        try {
            error_log('Attempting to load Excel file: ' . $inputFileName);
            $spreadsheet = IOFactory::load($inputFileName);
            error_log('Excel file loaded successfully');
        } catch (Exception $e) {
            error_log('Error loading Excel file: ' . $e->getMessage());
            throw new Exception('Error membaca file Excel: ' . $e->getMessage());
        }

        $worksheet = $spreadsheet->getActiveSheet();
        $excelData = $worksheet->toArray();

        // Validasi data Excel
        if (empty($excelData)) {
            throw new Exception('File Excel kosong');
        }

        // Ambil data testing dari database
        try {
            error_log('Fetching testing data from database');
            $stmt = $pdo->query("SELECT nik, keterangan FROM data_testing ORDER BY id ASC");
            $dbData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log('Successfully fetched ' . count($dbData) . ' records from database');
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            throw new Exception('Error database: ' . $e->getMessage());
        }

        if (empty($dbData)) {
            error_log('No testing data found in database');
            throw new Exception('Tidak ada data testing di database');
        }

        // Buat array NIK => keterangan untuk mempermudah pencarian
        $dbKeterangan = [];
        foreach ($dbData as $row) {
            $dbKeterangan[$row['nik']] = strtolower(trim($row['keterangan']));
        }

        // Inisialisasi confusion matrix
        $confusionMatrix = [
            'TP' => 0, // True Positive (Aktual: Tidak Miskin, Prediksi: Tidak Miskin)
            'TN' => 0, // True Negative (Aktual: Miskin, Prediksi: Miskin)
            'FP' => 0, // False Positive (Aktual: Miskin, Prediksi: Tidak Miskin)
            'FN' => 0  // False Negative (Aktual: Tidak Miskin, Prediksi: Miskin)
        ];

        // Skip header row dari Excel
        array_shift($excelData);

        // Debug information
        $debugInfo = [
            'excel_rows' => count($excelData),
            'db_rows' => count($dbData),
            'processed_rows' => 0,
            'invalid_rows' => 0,
            'not_found_nik' => 0
        ];

        // Hitung confusion matrix
        foreach ($excelData as $row) {
            // Pastikan NIK ada di Excel (Kolom A, indeks 0)
            if (!isset($row[0]) || empty($row[0])) {
                $debugInfo['invalid_rows']++;
                continue;
            }

            $nik = trim($row[0]);
            
            // Cek apakah NIK ada di database
            if (!isset($dbKeterangan[$nik])) {
                $debugInfo['not_found_nik']++;
                continue;
            }

            // Ambil nilai keterangan AKTUAL dari Excel (Kolom S, indeks 18)
            // Pastikan indeks 18 ada sebelum diakses
            $actualClass = isset($row[18]) ? trim($row[18]) : null; // Jangan langsung lowercase di sini
            
            // Ambil nilai keterangan PREDIKSI dari database (sudah di-lowercase saat dibuat $dbKeterangan)
            $predictedClass = $dbKeterangan[$nik];

            // Hitung hanya jika NIK ada di database dan nilai kelas aktual tidak null
            if ($actualClass !== null) {
                $debugInfo['processed_rows']++;

                // Validasi dan samakan kapitalisasi untuk perbandingan
                $actualClassLower = strtolower($actualClass);

                // Validasi nilai kelas (miskin/tidak miskin, case-insensitive)
                if (!in_array($actualClassLower, ['miskin', 'tidak miskin']) || 
                    !in_array($predictedClass, ['miskin', 'tidak miskin'])) {
                    $debugInfo['invalid_rows']++;
                    continue;
                }

                // Update confusion matrix
                if ($actualClassLower === 'tidak miskin' && $predictedClass === 'tidak miskin') {
                    $confusionMatrix['TP']++;
                } elseif ($actualClassLower === 'miskin' && $predictedClass === 'miskin') {
                    $confusionMatrix['TN']++;
                } elseif ($actualClassLower === 'miskin' && $predictedClass === 'tidak miskin') {
                    $confusionMatrix['FP']++;
                } elseif ($actualClassLower === 'tidak miskin' && $predictedClass === 'miskin') {
                    $confusionMatrix['FN']++;
                }
            }
        }

        $total = array_sum($confusionMatrix);
        if ($total == 0) {
            // Periksa apakah ada baris yang diproses sama sekali
            if ($debugInfo['processed_rows'] == 0 && $debugInfo['excel_rows'] > 0) {
                 $messageDetail = [];
                 if ($debugInfo['not_found_nik'] > 0) {
                     $messageDetail[] = $debugInfo['not_found_nik'] . ' NIK di Excel tidak ditemukan di Data Testing Database.';
                 }
                 if ($debugInfo['invalid_rows'] > 0) {
                      $messageDetail[] = $debugInfo['invalid_rows'] . ' baris di Excel memiliki nilai kelas yang tidak valid atau format NIK salah.';
                 }
                 $finalMessage = 'Tidak ada data yang valid untuk dihitung.';
                 if (!empty($messageDetail)) {
                     $finalMessage .= ' Kemungkinan karena: ' . implode(' ', $messageDetail);
                 }
                 throw new Exception($finalMessage);
            } else if ($debugInfo['excel_rows'] == 0 && $debugInfo['db_rows'] > 0) {
                 throw new Exception('File Excel kosong atau hanya berisi header.');
            } else if ($debugInfo['excel_rows'] > 0 && $debugInfo['db_rows'] == 0) {
                 throw new Exception('Data Testing di database kosong.');
            }
             throw new Exception('Tidak ada data yang valid untuk dihitung (kondisi tidak terduga).');
        }

        // Hitung metrics
        $accuracy = ($confusionMatrix['TP'] + $confusionMatrix['TN']) / $total * 100;
        
        $precision = $confusionMatrix['TP'] > 0 ? 
            ($confusionMatrix['TP'] / ($confusionMatrix['TP'] + $confusionMatrix['FP'])) * 100 : 0;
        
        $recall = $confusionMatrix['TP'] > 0 ? 
            ($confusionMatrix['TP'] / ($confusionMatrix['TP'] + $confusionMatrix['FN'])) * 100 : 0;
        
        $f1Score = ($precision + $recall) > 0 ? 
            2 * ($precision * $recall) / ($precision + $recall) : 0;

        // Return results as JSON
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'matrix' => $confusionMatrix,
            'metrics' => [
                'accuracy' => round($accuracy, 2),
                'precision' => round($precision, 2),
                'recall' => round($recall, 2),
                'f1_score' => round($f1Score, 2)
            ],
            'debug' => $debugInfo // Sertakan debug info untuk troubleshooting
        ]);

    } catch (Exception $e) {
        error_log('Confusion Matrix Error: ' . $e->getMessage());
        header('Content-Type: application/json');
        // Sertakan debug info dalam respon error jika tersedia
        $errorResponse = [
            'success' => false,
            'message' => $e->getMessage(),
            'detail' => 'Lihat error log untuk informasi lebih lanjut'
        ];
         if (isset($debugInfo)) {
             $errorResponse['debug'] = $debugInfo;
         }
        echo json_encode($errorResponse);
    }
}
?> 