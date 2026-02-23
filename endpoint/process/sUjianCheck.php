<?Php
require("../../_core.php");
header('Content-Type: application/json');

$FETCH = query("examrec","WHERE qtype='pilgan' AND cstatus='0' ORDER BY exam_id ASC, question_id ASC");
if (mysqli_num_rows($FETCH) > 0) { while ($FDAT = mysqli_fetch_array($FETCH)) {
    $ID = $FDAT['ID'];
    $HS = $FDAT['answer'] == $exac[$FDAT['question_id']] ? "1" : "0";
    update("examrec","cstatus='1', evaluation='$HS'","ID='$ID'");
} }

$FETCH = query("examrec","WHERE qtype='essay' AND cstatus='0' AND answer='' ORDER BY exam_id ASC, question_id ASC");
if (mysqli_num_rows($FETCH) > 0) { while ($FDAT = mysqli_fetch_array($FETCH)) {
    $ID = $FDAT['ID'];
    update("examrec","cstatus='1', evaluation='0'","ID='$ID'");
} }
?>