<?Php
switch($_POST['activity']) {
    case "upload":
        require 'library/PHPExcel/Classes/PHPExcel.php';
        $excelFilePath = $_FILES['excel_file']['tmp_name'];

        $objPHPExcel    = PHPExcel_IOFactory::load($excelFilePath);
        $worksheet      = $objPHPExcel->getActiveSheet();
        $highestRow     = $worksheet->getHighestDataRow();
        $highestColumn  = $worksheet->getHighestDataColumn();

        for ($row = 2; $row <= $highestRow; $row++) {
            $cellValue = array(
                "nip" => $worksheet->getCell('B' . $row)->getValue(),
                "fullname" => $worksheet->getCell('C' . $row)->getValue(),
                "gender" => $worksheet->getCell('D' . $row)->getValue(),
                "birthloc" => $worksheet->getCell('E' . $row)->getValue(),
                "birthdate" => $worksheet->getCell('F' . $row)->getValue(),
                "phone" => $worksheet->getCell('G' . $row)->getValue(),
                "email" => $worksheet->getCell('H' . $row)->getValue(),
                "address" => $worksheet->getCell('I' . $row)->getValue(),
                "password" => h4sh($worksheet->getCell('J' . $row)->getValue())
            );

            if($cellValue["nip"] != "") {                
                $nip = $cellValue['nip'];
                $fun = $cellValue['fullname'];
                $gdr = $cellValue['gender'];
                $blo = $cellValue['birthloc'];
                $bdt = $cellValue['birthdate'];
                $phn = $cellValue['phone'];
                $mai = $cellValue['email'];
                $adr = $cellValue['address'];
                $pwd = $cellValue['password'];

                $insert = insert("teacher","nip='$nip', full_name='$fun', address='$adr', birthloc='$blo', birthdate='$bdt', gender='$gdr', email='$mai', phone='$phn'");
                if($insert) {insert("user","username='$nip', password='$pwd', role='teacher', status='1'");}
                $r['DATA'][] = $cellValue;
            } else {
                $r['OK'] = true;
                break;
            }
        }

        break;
    case "add":
        $nip  = $_POST['nip'];
        $name = $_POST['name'] ?? "";
        $addr = $_POST['address'] ?? "";
        $gndr = $_POST['gender'] ?? "";
        $bloc = $_POST['birthloc'] ?? "";
        $bdat = $_POST['birthdate'] ?? "";
        $phne = $_POST['phone'] ?? "";
        $mail = $_POST['email'] ?? "";

        $pssw = h4sh($_POST['password'] ?? "teacher");

        $get = select("teacher","nip='$nip'");
        if(mysqli_num_rows($get) == 0) {
            $insert = insert("teacher","nip='$nip', full_name='$name', address='$addr', birthloc='$bloc', birthdate='$bdat', gender='$gndr', email='$mail', phone='$phne'");
            if($insert) { $insert = insert("user","username='$nip', password='$pssw', role='teacher', status='1'");
                if($insert) { $r['OK'] = true;
                    $g = select("teacher","nip='$nip'");
                    if(mysqli_num_rows($g) == 1) {
                        $d = mysqli_fetch_array($g);
                        $r['result_id'] = $d['ID'];
                    }
                }
            }
        } else {$r['msg'] = "Guru dengan id yang sama telah ada.";}
        break;
    case "update":
        $ID = $_POST['ID'];
        $get = select("teacher","ID='$ID'");
        if(mysqli_num_rows($get) == 1) {
            $data = mysqli_fetch_array($get);
            $name = $_POST['name'] ?? $data['full_name'];
            $addr = $_POST['address'] ?? $data['address'];
            $gndr = $_POST['gender'] ?? $data['gender'];
            $bloc = $_POST['birthloc'] ?? $data['birthloc'];
            $bdat = $_POST['birthdate'] ?? $data['birthdate'];
            $phne = $_POST['phone'] ?? $data['phone'];
            $mail = $_POST['email'] ?? $data['email'];
            $sign = $_POST['sign'] ?? $data['sign'];

            $update = update("teacher","full_name='$name', sign='$sign', address='$addr', birthloc='$bloc', birthdate='$bdat', gender='$gndr', email='$mail', phone='$phne'","ID='$ID'");
            if($update) {$r['OK'] = true;}
        }
        break;
    case "mapel_add":
        $nip = $_POST['nip'];
        $class = $_POST['tipe'] == "ekskul" ? "0" : $_POST['class'];
        $mapel = $_POST['mapel'];

        $get = select("subjetc","class='$class' AND subject_id='$mapel'");
        if(mysqli_num_rows($get) == 0) {
            $insert = insert("subjetc","class='$class', subject_id='$mapel', nip='$nip'");
            if($insert) {$r['OK'] = true;}
        }
        
        break;
    case "mapel_delete":
        $ID = $_POST['ID'];
        $delete = delete("subjetc","ID='$ID'");
        if($delete) {$r['OK'] = true;}
        break;
}
?>