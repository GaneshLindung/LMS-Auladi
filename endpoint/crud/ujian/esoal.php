<?Php
if(isset($_POST['activity'])) {
    if($_POST['activity'] == "add" && isset($_POST['hash'])) {
        $hash = $_POST['hash'];
        $q = base64_encode($_POST['question']);
        $value = $_POST['value'];

        $uK = md5(randomtoken(512));

        $add = insert("examqe","package='$hash', uniqueKey='$uK', question='$q', value='$value'");
        if ($add) {$r['OK'] = true;}
    } elseif($_POST['activity'] == "update" && isset($_POST['sign'])) {
        $sign = explode(":", $_POST['sign']);
        if (count($sign) == 2) {
            $hash = $sign[0];
            $ID = $sign[1];

            $get = select("examqe","package='$hash' AND ID='$ID'");
            if(mysqli_num_rows($get) == 1) {
                $data = mysqli_fetch_array($get);
                $q = base64_encode($_POST['question']) ?? $data['question'];
                $value = $_POST['value'];
    
                $u = update("examqe","question='$q', value='$value'","package='$hash' AND ID='$ID'");
                if($u) {$r['OK'] = true;}
            }
        }
    } elseif($_POST['activity'] == "delete" && isset($_POST['sign'])) {
        $sign = explode(":", $_POST['sign']);
        if (count($sign) == 2) {
            $hash = $sign[0];
            $ID = $sign[1];

            $d = delete("examqe","package='$hash' AND ID='$ID'");
            if($d) {$r['OK'] = true;}
        }
    }
}
?>