<?php
// predict_single.php (Versi API Client dengan cURL)
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Metode request tidak diizinkan.']);
    exit;
}

try {
    // URL dari API service Python Flask
    $api_url = 'http://127.0.0.1:5000/predict';

    // Ambil data dari form
    $data_to_send = $_POST;
    $json_data = json_encode($data_to_send);

    // Inisialisasi cURL
    $ch = curl_init($api_url);

    // Set opsi cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($json_data)
    ]);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Timeout koneksi 10 detik
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Timeout total 30 detik

    // Eksekusi cURL
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Cek jika ada error cURL
    if (curl_errno($ch)) {
        throw new Exception('Error saat menghubungi API prediksi: ' . curl_error($ch));
    }

    // Tutup cURL
    curl_close($ch);

    // Cek status response dari API
    if ($http_code != 200) {
        $error_data = json_decode($response, true);
        $error_message = $error_data['message'] ?? 'API Python merespon dengan error.';
        throw new Exception("API Error (Code: {$http_code}): " . $error_message);
    }

    // Kirimkan response dari API langsung ke browser
    echo $response;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
