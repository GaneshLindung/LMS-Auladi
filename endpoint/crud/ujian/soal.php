<?Php
if(isset($_POST['activity'])) {
    if($_POST['activity'] == "delete" && isset($_POST['hash'])) {
        $hash = $_POST['hash'];
        delete("examqc","package='$hash'");
        delete("examqe","package='$hash'");
        update("examl","pilgan=''","pilgan='$hash'");
        update("examl","essay=''","essay='$hash'");
        $delete = delete("exam_p","hash='$hash'");
        if($delete) {$r['OK'] = true;}
    }
}
?>