<?Php
    if($_POST['activity'] == "add" && isset($_POST['class'])) {
        $title = base64_encode($_POST['title']) ?? "";
        $deskr = base64_encode($_POST['deskripsi']) ?? "";
        $mapel = $_POST['subject'];
        $durat = $_POST['duration'];
        $passw = ujian($_POST['password']);
        $pilgn = $_POST['pilgand'];
        $essay = $_POST['essay'];
        $multi = $_POST['multipleChoice'];
        $cocok = $_POST['match'];
        $kelas = $_POST['class'];

        $ppctg = $_POST['ppctg'];
        $epctg = $_POST['epctg'];
        $mpctg = $_POST['mpctg'];
        $cpctg = $_POST['cpctg'];

        $examid = randomtoken(32);
        $insert = insert("examl","subject_id='$mapel', uploader='$unme', title='$title', description='$deskr', duration='$durat', password='$passw', exam_id='$examid', pilgan='$pilgn', essay='$essay', multiple='$multi', cocok='$cocok', percent_p='$ppctg', percent_e='$epctg', percent_m='$mpctg', percent_c='$cpctg'");
        if($insert) {
            for($i=0;$i<=count($kelas)-1;$i++) {
                $cl = $kelas[$i];
                insert("exam_d","exam_id='$examid', class='$cl'");
            }
            $r['OK'] = true;
        }
    } elseif(isset($_POST['activity']) && $_POST['activity'] == "add_soal") {
        $tipe = $_POST['type'];
        $titl = $_POST['title'];
        $mapl = $_POST['mapel'];
        $hash = md5(randomtoken(32));
        
        $inst = insert("exam_p","hash='$hash', uploader='$unme', type='$tipe', subject_id='$mapl', title='$titl'");
        if($inst) {
            $r['OK'] = true;
            $r['hash'] = $hash;
        }
    } elseif($_POST['activity'] == "update" && isset($_POST['hash'])) {
        $hash = $_POST['hash'];
        $GET  = select("examl","exam_id='$hash'");
        if (mysqli_num_rows($GET) == 1) { $data = mysqli_fetch_array($GET);
            $title = base64_encode($_POST['title']) ?? "";
            $deskr = base64_encode($_POST['deskripsi']) ?? "";
            $mapel = $_POST['subject'];
            $durat = $_POST['duration'];
            $kelas = $_POST['class'] ?? [];
    
            $passw = $_POST['password'] != "" ? ujian($_POST['password']) : $data['password'];
            $ppctg = $_POST['ppctg'];
            $epctg = $_POST['epctg'];
            $mpctg = $_POST['mpctg'];
            $cpctg = $_POST['cpctg'];

            $update = update("examl","subject_id='$mapel', uploader='$unme', title='$title', description='$deskr', duration='$durat', password='$passw', percent_p='$ppctg', percent_e='$epctg', percent_m='$mpctg', percent_c='$cpctg'","exam_id='$hash'");
            delete("exam_d","exam_id='$hash'");

            if(isset($_POST['class'])) {
                foreach($_POST['class'] as $clsx) {
                    insert("exam_d","exam_id='$hash', class='$clsx'");
                }
            }
        }
    } elseif(isset($_POST['activity']) && $_POST['activity'] == "uploadExcel") {
        $tipe = $_POST['type'];
        $hash = $_POST['hash'];

        if (isset($soal[$hash]) && $_FILES['excelFile']['size'] > 0) {
            require 'library/PHPExcel/Classes/PHPExcel.php';
            $excelFilePath = $_FILES['excelFile']['tmp_name'];

            $objPHPExcel    = PHPExcel_IOFactory::load($excelFilePath);
            $worksheet      = $objPHPExcel->getActiveSheet();
            $highestRow     = $worksheet->getHighestDataRow();
            $highestColumn  = $worksheet->getHighestDataColumn();
            
            $data = [];
            if ($soal[$hash]['tipe'] == "choice") {
                for ($row = 2; $row <= $highestRow; $row++) {
                    $cellValue = array(
                        "question" => $worksheet->getCell('A' . $row)->getValue(),
                        "choices" => array(
                            "a" => $worksheet->getCell('B' . $row)->getValue(),
                            "b" => $worksheet->getCell('C' . $row)->getValue(),
                            "c" => $worksheet->getCell('D' . $row)->getValue(),
                            "d" => $worksheet->getCell('E' . $row)->getValue(),
                            "e" => $worksheet->getCell('F' . $row)->getValue()
                        ),
                        "answerKey" => $worksheet->getCell('G' . $row)->getValue()
                    );

                    $q = base64_encode($cellValue['question']);
                    $a = base64_encode($cellValue['choices']['a']);
                    $b = base64_encode($cellValue['choices']['b']);
                    $c = base64_encode($cellValue['choices']['c']);
                    $d = base64_encode($cellValue['choices']['d']);
                    $e = base64_encode($cellValue['choices']['e']);
                    $v = strtolower($cellValue['answerKey']);

                    if($q != "") {
                        $uK = md5(randomtoken(512));
                        insert("examqc","package='$hash', uniqueKey='$uK', question='$q', a='$a', b='$b', c='$c', d='$d', e='$e', va='$v'");
                    }
                }

                $r['OK'] = true;
            } elseif($soal[$hash]['tipe'] == "essay") {
                for ($row = 2; $row <= $highestRow; $row++) {
                    $q = $worksheet->getCell('A' . $row)->getValue();
                    $q = base64_encode($q);
                    
                    if($q != "") {
                        $uK = md5(randomtoken(512));
                        insert("examqe","package='$hash', uniqueKey='$uK', question='$q'");
                    }
                }

                $r['OK'] = true;
            }

            $r['data'] = $data;
        }
    }
?>