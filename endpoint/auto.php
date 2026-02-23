<?php
require("../_core.php");
header('Content-Type: application/json');

if(isset($_GET['c8d9459f4'])) {
    if(isset($_GET['target']) && isset($alldata[$_GET['target']])) {
        echo json_encode($alldata[$_GET['target']], JSON_PRETTY_PRINT);
    } else {
        if(isset($_GET['pretty'])) {
            if(isset($_GET['private_access'])) {$alldata['access'] = $KunciKemanaSaja;}
            echo json_encode($alldata, JSON_PRETTY_PRINT);
        } else {
            echo json_encode($alldata);
        }
    }
}

// $FOREACH = query("examqe","ORDER BY ID ASC");
// while($data = mysqli_fetch_array($FOREACH)) {
//     $pkg = $data['package'];
//     $qid = $data['ID'];
    
//     $uK  = md5(randomtoken(512));
//     update("examqe","uniqueKey='$uK'","package='$pkg' AND ID='$qid'");
// }
?>