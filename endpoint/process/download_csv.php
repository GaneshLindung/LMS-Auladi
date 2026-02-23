<?php
$cons = mysqli_connect("localhost", "sitaulad_lms", "adminauladi", "sitaulad_lms_semabor");

// Periksa koneksi
if (!$cons) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set header agar file terdownload sebagai CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="hasil_ujian.csv"');

// Buka output sebagai file stream
$output = fopen('php://output', 'w');

// Ambil exam_id dari URL
$exam_id = isset($_GET['hash']) ? $_GET['hash'] : die("Exam ID tidak ditemukan!");

// Query untuk mengambil data siswa berdasarkan exam_id
$query = "SELECT nis, evaluation, value FROM examrec WHERE exam_id = '$exam_id'";
$result = mysqli_query($cons, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Proses data menjadi format yang diinginkan
$groupedData = [];
$maxCount = 0;

foreach ($data as $row) {
    $nis = $row['nis'];
    $evaluation = $row['evaluation'];
    $value = $row['value'];
    
    // Ambil full_name berdasarkan NIS
    $studentQuery = "SELECT full_name FROM siswa WHERE nis = '$nis'";
    $studentResult = mysqli_query($cons, $studentQuery);
    $studentRow = mysqli_fetch_assoc($studentResult);
    $full_name = $studentRow['full_name'] ?? $nis;
    
    if (!isset($groupedData[$nis])) {
        $groupedData[$nis] = ['full_name' => $full_name, 'values' => [], 'total_evaluation' => 0, 'total_value' => 0];
    }
    
    $groupedData[$nis]['values'][] = $value;
    $groupedData[$nis]['total_evaluation'] += $evaluation;
    $groupedData[$nis]['total_value'] += $value;
    $maxCount = max($maxCount, count($groupedData[$nis]['values']));
}

// Menyesuaikan panjang array agar sama
foreach ($groupedData as &$entry) {
    while (count($entry['values']) < $maxCount) {
        $entry['values'][] = "0";
    }
}
unset($entry);

// Menambahkan nilai akhir dari `exam_s`
foreach ($groupedData as $nis => &$entry) {
    $finalScoreQuery = "SELECT nilai FROM exam_s WHERE nis = '$nis' AND exam_id = '$exam_id'";
    $finalScoreResult = mysqli_query($cons, $finalScoreQuery);
    $finalScoreRow = mysqli_fetch_assoc($finalScoreResult);
    $finalScore = $finalScoreRow['nilai'] ?? "0";

    // Tambahkan total evaluation, total value, dan nilai exam_s
    $entry['values'][] = $entry['total_evaluation'];
    $entry['values'][] = $entry['total_value'];
    $entry['values'][] = $finalScore;
}
unset($entry);

// Membuat header untuk nomor soal
$header = ['NO', 'NAMA'];
for ($i = 1; $i <= $maxCount; $i++) {
    $header[] = $i; // Menambahkan nomor soal sesuai jumlah data
}
array_push($header, 'Nilai Maksimal', 'Nilai Didapat', 'Nilai Akhir');

// Tulis header ke file CSV
fputcsv($output, $header, ';');

// Menulis data siswa ke file CSV
$index = 1;
foreach ($groupedData as $entry) {
    $row = array_merge([$index, $entry['full_name']], $entry['values']);
    fputcsv($output, $row, ';');
    $index++;
}

// Tutup output
fclose($output);

// Tutup koneksi database
mysqli_close($cons);
?>
