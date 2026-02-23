<?Php
if(isset($_POST['activity'])) {
    if($_POST['activity'] == "delete") {
        $hash = $_POST['hash'];
        delete("exam_d","exam_id='$hash'");
        $delete = delete("examl","exam_id='$hash'");
        if($delete) {$r['OK'] = true;}
    } elseif($_POST['activity'] == "change_status" && isset($_POST['hash'])) {
        $hash = $_POST['hash']; $status = "0";
        if(isset($_POST['set_status']) && $_POST['set_status'] == "1") {$status = "1";}
        $update = update("examl","publish_status='$status'","exam_id='$hash'");
        if($update) {$r['OK'] = true;}
    }
}
?>