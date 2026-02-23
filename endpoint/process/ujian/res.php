<?Php
$nis = $_POST['NIS'];
$xid = $_POST['XID'];
$val = $_POST['VAL'];

$u = update("exam_s","cstatus='1', nilai='$val'","nis='$nis' AND exam_id='$xid'");
if ($u) {$r['OK'] = true;}
?>