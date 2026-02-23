<?Php
$nis = $_POST['NIS'];
$xid = $_POST['XID'];
$qid = $_POST['QID'];
$val = $_POST['VAL'];

$eva = $val > 0 ? 1 : 0;

$GET = select("examrec","nis='$nis' AND exam_id='$xid' AND question_id='$qid'");
if (mysqli_num_rows($GET) == 1) { $data = mysqli_fetch_array($GET);
    $ID = $data['ID'];
    $update = update("examrec","cstatus='1', value='$val'","ID='$ID'");
    if($update) {$r['OK'] = true;}
}
?>