<?php
require_once '../config/database.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {
    // Ambil data dari database
    $query = "SELECT 
        nik,
        nama,
        rt,
        rw,
        jenis_kelamin,
        pendidikan_terakhir,
        pekerjaan,
        tempat_tinggal,
        lantai,
        dinding,
        atap,
        sumber_air_minum,
        sumber_air,
        sumber_penerangan,
        bahan_bakar_masak,
        tempat_bab,
        jenis_kloset,
        aset_bergerak,
        keterangan
    FROM data_testing";
    
    $stmt = $pdo->query($query);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($data)) {
        // Mengembalikan response JSON untuk SweetAlert jika tidak ada data
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Tidak ada data testing untuk diekspor']);
        exit;
    }

    // Buat spreadsheet baru
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set judul kolom
    $columns = [
        'A' => 'NIK',
        'B' => 'Nama',
        'C' => 'RT',
        'D' => 'RW',
        'E' => 'Jenis Kelamin',
        'F' => 'Pendidikan Terakhir',
        'G' => 'Pekerjaan',
        'H' => 'Tempat Tinggal',
        'J' => 'Lantai',
        'I' => 'Dinding',
        'K' => 'Atap',
        'L' => 'Sumber Air Minum',
        'M' => 'Sumber Air',
        'N' => 'Sumber Penerangan',
        'O' => 'Bahan Bakar Masak',
        'P' => 'Tempat BAB',
        'Q' => 'Jenis Kloset',
        'R' => 'Aset Bergerak',
        'S' => 'Keterangan'
    ];

    // Tulis header
    foreach ($columns as $column => $header) {
        $sheet->setCellValue($column . '1', $header);
        // Style header
        $sheet->getStyle($column . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2C3E50']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ]
        ]);
    }

    // Tulis data
    $row = 2;
    foreach ($data as $item) {
        $sheet->setCellValue('A' . $row, $item['nik']);
        $sheet->setCellValue('B' . $row, $item['nama']);
        $sheet->setCellValue('C' . $row, $item['rt']);
        $sheet->setCellValue('D' . $row, $item['rw']);
        $sheet->setCellValue('E' . $row, ucwords(strtolower($item['jenis_kelamin'])));
        $sheet->setCellValue('F' . $row, ucwords(strtolower($item['pendidikan_terakhir'])));
        $sheet->setCellValue('G' . $row, ucwords(strtolower($item['pekerjaan'])));
        $sheet->setCellValue('H' . $row, ucwords(strtolower($item['tempat_tinggal'])));
        $sheet->setCellValue('J' . $row, ucwords(strtolower($item['lantai'])));
        $sheet->setCellValue('I' . $row, ucwords(strtolower($item['dinding'])));
        $sheet->setCellValue('K' . $row, ucwords(strtolower($item['atap'])));
        $sheet->setCellValue('L' . $row, ucwords(strtolower($item['sumber_air_minum'])));
        $sheet->setCellValue('M' . $row, ucwords(strtolower($item['sumber_air'])));
        $sheet->setCellValue('N' . $row, ucwords(strtolower($item['sumber_penerangan'])));
        $sheet->setCellValue('O' . $row, ucwords(strtolower($item['bahan_bakar_masak'])));
        $sheet->setCellValue('P' . $row, ucwords(strtolower($item['tempat_bab'])));
        $sheet->setCellValue('Q' . $row, ucwords(strtolower($item['jenis_kloset'])));
        $sheet->setCellValue('R' . $row, ucwords(strtolower($item['aset_bergerak'])));
        $sheet->setCellValue('S' . $row, ucwords(strtolower($item['keterangan'])));
        $row++; 
    }

    // Auto-size columns
    foreach (range('A', 'S') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }

    // Style untuk seluruh data
    $dataRange = 'A2:S' . ($row - 1);
    $sheet->getStyle($dataRange)->applyFromArray([
        'alignment' => [
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            ]
        ]
    ]);

    // Alternate row colors
    for ($i = 2; $i < $row; $i++) {
        if ($i % 2 == 0) {
            $sheet->getStyle('A' . $i . ':S' . $i)->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F8F9FA']
                ]
            ]);
        }
    }

    // Clean output buffer
    ob_clean();
    
    // Set headers
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Data_Testing.xlsx"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');

    // Create temporary file
    $tempFile = tempnam(sys_get_temp_dir(), 'excel');
    $writer = new Xlsx($spreadsheet);
    $writer->save($tempFile);

    // Send file to browser
    readfile($tempFile);
    unlink($tempFile); // Delete temporary file
    exit;

} catch (Exception $e) {
    // Mengembalikan response JSON untuk SweetAlert jika ada error
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error saat ekspor data testing: ' . $e->getMessage()]);
    exit;
}
?> 