<?Php
if(isset($_POST['activity'])) {
    if($_POST['activity'] == "add" && isset($_POST['hash'])) {
        $hash = $_POST['hash'];
        $q = base64_encode($_POST['question']);
        $a = base64_encode($_POST['answer']);
        $value = $_POST['value'];

        $uK = md5(randomtoken(512));
        $uI = md5(randomtoken(512));

        $add = insert("examqp","package='$hash', uniqueKey='$uK', question='$q', answer_id='$uI', answer_text='$a', value='$value'");
        if ($add) {$r['OK'] = true;}
    } elseif($_POST['activity'] == "update" && isset($_POST['sign'])) {
        $sign = explode(":", $_POST['sign']);
        if (count($sign) == 2) {
            $hash = $sign[0];
            $ID = $sign[1];

            $get = select("examqp","package='$hash' AND ID='$ID'");
            if(mysqli_num_rows($get) == 1) {
                $data = mysqli_fetch_array($get);
                $q = base64_encode($_POST['question']) ?? $data['question'];
                $a = base64_encode($_POST['answer']) ?? $data['answer_text'];
                $value = $_POST["value"];
    
                $u = update("examqp","question='$q', answer_text='$a', value='$value'","package='$hash' AND ID='$ID'");
                if($u) {$r['OK'] = true;}
            }
        }
    } elseif($_POST['activity'] == "delete" && isset($_POST['sign'])) {
        $sign = explode(":", $_POST['sign']);
        if (count($sign) == 2) {
            $hash = $sign[0];
            $ID = $sign[1];

            $d = delete("examqp","package='$hash' AND ID='$ID'");
            if($d) {$r['OK'] = true;}
        }
    }
}
?>