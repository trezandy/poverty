<?php
function validateNIK($nik) {
    // NIK harus 16 digit angka
    return preg_match('/^\d{16}$/', $nik);
}

function validateName($nama) {
    // Nama tidak boleh kosong dan minimal 2 karakter
    return !empty($nama) && strlen($nama) >= 2;
}

function validateRT($rt) {
    // RT harus 1-3 digit angka
    return preg_match('/^\d{1,3}$/', $rt);
}

function validateRW($rw) {
    // RW harus 1-3 digit angka
    return preg_match('/^\d{1,3}$/', $rw);
}

function validasiInput($data) {
    $errors = [];
    
    // Validasi NIK
    if (!validateNIK($data['nik'])) {
        $errors[] = "NIK harus 16 digit angka";
    }
    
    // Validasi nama
    if (!validateName($data['nama'])) {
        $errors[] = "Nama tidak boleh kosong dan minimal 2 karakter";
    }
    
    // Validasi RT
    if (!validateRT($data['rt'])) {
        $errors[] = "RT harus 1-3 digit angka";
    }
    
    // Validasi RW
    if (!validateRW($data['rw'])) {
        $errors[] = "RW harus 1-3 digit angka";
    }
    
    // Validasi field required
    $required_fields = [
        'tempat_tinggal' => 'Tempat Tinggal',
        'dinding' => 'Dinding',
        'lantai' => 'Lantai',
        'atap' => 'Atap',
        'sumber_air_minum' => 'Sumber Air Minum',
        'sumber_penerangan' => 'Sumber Penerangan',
        'bahan_bakar_masak' => 'Bahan Bakar Masak',
        'tempat_bab' => 'Tempat BAB'
    ];
    
    foreach ($required_fields as $field => $label) {
        if (empty($data[$field])) {
            $errors[] = "$label harus diisi";
        }
    }
    
    return $errors;
}

function formatTitleCase($text) {
    // Konversi null menjadi string kosong
    $text = $text ?? '';
    $words = explode(' ', strtolower($text));
    $formatted = array_map(function($word) {
        return ucfirst($word);
    }, $words);
    return implode(' ', $formatted);
}
?> 