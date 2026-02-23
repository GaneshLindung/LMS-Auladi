<?php
// Pastikan tidak ada output sebelum header
ob_start();

// Cek apakah ada request download dan hash
if (isset($_GET['download']) && isset($_GET['hash'])) {
    $hash = $_GET['hash']; // Ambil nilai hash dari URL

    // Set header agar file terunduh sebagai CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="hasil_ujian_' . $hash . '.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Buka output ke file CSV
    $output = fopen('php://output', 'w');

    // Tambahkan header kolom CSV
    fputcsv($output, ['Nama Siswa', 'NIS', 'Dikumpulkan', 'Nilai']);

    // Data statis untuk CSV (misalnya, hasil ujian)
    $data = [
        ['John Doe', '12345', '22 Feb 2025 - 10:00:00', 85],
        ['Jane Doe', '67890', '22 Feb 2025 - 10:05:00', 90],
        ['Alice Smith', '54321', '22 Feb 2025 - 10:10:00', 88],
    ];

    // Loop untuk menuliskan data ke file CSV
    foreach ($data as $row) {
        fputcsv($output, $row);
    }

    // Tutup output stream
    fclose($output);
    exit();
}

ob_end_flush();
?>
