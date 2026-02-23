<?Php
switch($_POST['activity']) {
    case "add":
        $name = $_POST['name'];
        $addr = $_POST['address'];
        $gndr = $_POST['gender'];
        $bloc = $_POST['birthloc'];
        $bdat = $_POST['birthdate'];
        $phne = $_POST['phone'];
        $mail = $_POST['email'];
        $clss = $_POST['kelas'];

        $nis  = $_POST['nis'];
        $pas  = h4sh($_POST['password']);

        $insert = insert("siswa","nis='$nis', class='$clss', full_name='$name', address='$addr', birthloc='$bloc', birthdate='$bdat', gender='$gndr', email='$mail', phone='$phne'");
        if($insert) {
            $insert = insert("user","username='$nis', password='$pas', role='student', status='1'");
            if($insert) {$r['OK'] = true;}
        }
        break;
    case "update":
        $ID = $_POST['ID'];
        $get = select("siswa","ID='$ID'");
        if(mysqli_num_rows($get) == 1) {
            $data = mysqli_fetch_array($get);
            $name = $_POST['name'] ?? $data['full_name'];
            $addr = $_POST['address'] ?? $data['address'];
            $gndr = $_POST['gender'] ?? $data['gender'];
            $bloc = $_POST['birthloc'] ?? $data['birthloc'];
            $bdat = $_POST['birthdate'] ?? $data['birthdate'];
            $phne = $_POST['phone'] ?? $data['phone'];
            $mail = $_POST['email'] ?? $data['email'];
            $clss = $_POST['kelas'] ?? $data['class'];

            $update = update("siswa","class='$clss', full_name='$name', address='$addr', birthloc='$bloc', birthdate='$bdat', gender='$gndr', email='$mail', phone='$phne'","ID='$ID'");
            if($update) {$r['OK'] = true;}
        }
        break;
    case "upload":
        require 'library/PHPExcel/Classes/PHPExcel.php';
        $excelFilePath = $_FILES['excel_file']['tmp_name'];

        $objPHPExcel    = PHPExcel_IOFactory::load($excelFilePath);
        $worksheet      = $objPHPExcel->getActiveSheet();
        $highestRow     = $worksheet->getHighestDataRow();
        $highestColumn  = $worksheet->getHighestDataColumn();

        for ($row = 2; $row <= $highestRow; $row++) {
            $cellValue = array(
                "nis" => $worksheet->getCell('B' . $row)->getValue(),
                "fullname" => $worksheet->getCell('C' . $row)->getValue(),
                "gender" => $worksheet->getCell('D' . $row)->getValue(),
                "birthloc" => $worksheet->getCell('E' . $row)->getValue(),
                "birthdate" => $worksheet->getCell('F' . $row)->getValue(),
                "phone" => $worksheet->getCell('G' . $row)->getValue(),
                "email" => $worksheet->getCell('H' . $row)->getValue(),
                "address" => $worksheet->getCell('I' . $row)->getValue(),
                "password" => h4sh($worksheet->getCell('J' . $row)->getValue())
            );

            if($cellValue["nis"] != "") {                
                $nis = $cellValue['nis'];
                $fun = $cellValue['fullname'];
                $gdr = $cellValue['gender'];
                $blo = $cellValue['birthloc'];
                $bdt = $cellValue['birthdate'];
                $phn = $cellValue['phone'];
                $mai = $cellValue['email'];
                $adr = $cellValue['address'];
                $pwd = $cellValue['password'];

                $insert = insert("siswa","nis='$nis', class='99999', full_name='$fun', address='$adr', birthloc='$blo', birthdate='$bdt', gender='$gdr', email='$mai', phone='$phn'");
                if($insert) {insert("user","username='$nis', password='$pwd', role='student', status='1'");}
                $r['DATA'][] = $cellValue;
            } else {
                $r['OK'] = true;
                break;
            }
        }

        break;
    case "deleteFromClass":
        $id_siswa = $_POST['siswa'];
        $update = update("siswa","class='99999'","ID='$id_siswa'");
        break;
}
?>