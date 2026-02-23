<?Php
if(isset($_POST['activity'])) {
    if($_POST['activity'] == "add" && isset($_POST['hash'])) {
        $hash  = $_POST['hash'];
        $q = base64_encode($_POST['question']);
        $a = base64_encode($_POST['choice_a']);
        $b = base64_encode($_POST['choice_b']);
        $c = base64_encode($_POST['choice_c']);
        $d = base64_encode($_POST['choice_d']);
        $e = base64_encode($_POST['choice_e']);
        $v = $_POST['va'];
        $value = $_POST['value'];

        $uK = md5(randomtoken(512));

        if($_FILES['image']['size'] > 0) {
            $file  = $_FILES['image'];
            $dots = explode(".", $file['name']);
            if(count($dots) == 2) {
                $i  = fjoin(randomtoken(64), $dots[1]);
                $up = upload($file, "../static/image/soal/".$i);
            }
        } $i = $i ?? "";
        
        $insert = insert("examqc","package='$hash', uniqueKey='$uK', question='$q', image='$i', a='$a', b='$b', c='$c', d='$d', e='$e', va='$v', value='$value'");
        if($insert) {$r['OK'] = true;}
    } elseif($_POST['activity'] == "update_title" && isset($_POST['hash'])) {
        $hash = $_POST['hash'];
        $title = $_POST['title'];
        $update = update("exam_p","title='$title'","hash='$hash'");
        if($update) {$r['OK'] = true;}
    } elseif($_POST['activity'] == "update" && isset($_POST['sign'])) {
        $sign = explode(":", $_POST['sign']);
        if (count($sign) == 2) {
            $hash = $sign[0];
            $ID = $sign[1];

            $get = select("examqc","package='$hash' AND ID='$ID'");
            if(mysqli_num_rows($get) == 1) {
                $data = mysqli_fetch_array($get);
                $q = base64_encode($_POST['question']) ?? $data['question'];
                $a = base64_encode($_POST['choice_a']) ?? $data['a'];
                $b = base64_encode($_POST['choice_b']) ?? $data['b'];
                $c = base64_encode($_POST['choice_c']) ?? $data['c'];
                $d = base64_encode($_POST['choice_d']) ?? $data['d'];
                $e = base64_encode($_POST['choice_e']) ?? $data['e'];
                $v = $_POST['va'] ?? $data['va'];
                $value = $_POST['value'];

                if($_FILES['image']['size'] > 0) {
                    $file  = $_FILES['image'];
                    $dots = explode(".", $file['name']);
                    if(count($dots) == 2) {
                        $i  = fjoin(randomtoken(64), $dots[1]);
                        $up = upload($file, "../static/image/soal/".$i);
                    }
                }

                $i = $i ?? $data['image'];
    
                $u = update("examqc","question='$q', image='$i', a='$a', b='$b', c='$c', d='$d', e='$e', va='$v', value='$value'","package='$hash' AND ID='$ID'");
                if($u) {$r['OK'] = true;}
            }
        }
    } elseif($_POST['activity'] == "imgDelete" && isset($_POST['sign'])) {
        $sign = explode(":", $_POST['sign']);
        if (count($sign) == 2) {
            $hash = $sign[0];
            $ID = $sign[1];

            $u  = update("examqc","image=''","package='$hash' AND ID='$ID'");
            if($u) {$r['OK'] = true;}
        }
    } elseif($_POST['activity'] == "delete" && isset($_POST['sign'])) {
        $sign = explode(":", $_POST['sign']);
        if (count($sign) == 2) {
            $hash = $sign[0];
            $ID = $sign[1];

            $d = delete("examqc","package='$hash' AND ID='$ID'");
            if($d) {$r['OK'] = true;}
        }
    }
}
?>