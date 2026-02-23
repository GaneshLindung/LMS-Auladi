<?Php
if(isset($_POST['activity'])) {
    if($_POST['activity'] == "add") {
        $namaKelas = $_POST['title'];
        $insert = insert("class", "class_name='$namaKelas'");
        if($insert) {$r['OK'] = true;}
    } elseif($_POST['activity'] == "edit") {
        $ID = $_POST['ID'];
        $namaKelas = $_POST['title'];
        $update = update("class","class_name='$namaKelas'","ID='$ID'");
        if($update) {$r['OK'] = true;}
    } elseif($_POST['activity'] == "delete") {
        $ID = $_POST['ID'];
        $select = select("siswa","class='$ID'");
        if(mysqli_num_rows($select) == 0) {
            $delete = delete("class","ID='$ID'");
            if($delete) {$r['OK'] = true;}
        } else {
            $r['msg'] = "Untuk menghapus kelas ini, pastikan tidak ada siswa yang belajar di kelas ini.";
        }
    } elseif($_POST['activity'] == "upload") {
        $classid = $_POST['ID'];
        require 'library/PHPExcel/Classes/PHPExcel.php';
        $excelFilePath = $_FILES['excel_file']['tmp_name'];

        $objPHPExcel    = PHPExcel_IOFactory::load($excelFilePath);
        $worksheet      = $objPHPExcel->getActiveSheet();
        $highestRow     = $worksheet->getHighestDataRow();
        $highestColumn  = $worksheet->getHighestDataColumn();

        for ($row = 2; $row <= $highestRow; $row++) {
            $cellValue = array(
                "nis" => $worksheet->getCell('B' . $row)->getValue(),
                "fullname" => $worksheet->getCell('C' . $row)->getValue()
            );

            if($cellValue["nis"] != "") {
                $nis = (string)$cellValue['nis'];
                $fun = $cellValue['fullname'];
                update("siswa","class='$classid'","nis='$nis'");

                $r['DATA'][] = $cellValue;
            } else {
                $r['OK'] = true;
                break;
            }
        }
    } elseif($_POST['activity'] == "deleteAllSiswaFromClass") {
        $ID_KELAS = $_POST['kelas'];
        $update = update("siswa","class='99999'","class='$ID_KELAS'");
        if($update) {$r['OK'] = true;}
    }
}
?>