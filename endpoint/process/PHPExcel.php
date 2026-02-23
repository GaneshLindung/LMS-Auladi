<?php
header('Content-Type: application/json');

require '../library/PHPExcel/Classes/PHPExcel.php';
$excelFilePath = "../../static/files/examfiles/test.xlsx";

$objPHPExcel    = PHPExcel_IOFactory::load($excelFilePath);
$worksheet      = $objPHPExcel->getActiveSheet();
$highestRow     = $worksheet->getHighestDataRow();
$highestColumn  = $worksheet->getHighestDataColumn();

$data = [];
for ($row = 2; $row <= $highestRow; $row++) {
    $cellValue = array(
        "question" => $worksheet->getCell('A' . $row)->getValue(),
        "choices" => array(
            "a" => $worksheet->getCell('B' . $row)->getValue(),
            "b" => $worksheet->getCell('C' . $row)->getValue(),
            "c" => $worksheet->getCell('D' . $row)->getValue(),
            "d" => $worksheet->getCell('E' . $row)->getValue()
        ),
        "answerKey" => $worksheet->getCell('F' . $row)->getValue()
    );
    $data[] = $cellValue;
}

echo json_encode($data, JSON_PRETTY_PRINT);
?>