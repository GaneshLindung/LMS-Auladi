<?Php

function rcheck($type, $target) {
    if(in_array($type, ["POST","GET"])) {
        $result = true;
        if($type == "GET") {
            for($i=0;$i<=count($target)-1;$i++) {
                if(!isset($_GET[$target[$i]])) {
                    $result = false;
                    break;
                }
            }
        } else {
            for($i=0;$i<=count($target)-1;$i++) {if(!isset($_GET[$target[$i]])) {$result = false;break;}}
        }
    } else {$result =false;}
    return $result;
}

$r = [];
if (rcheck("GET", ['target','method','data'])) {
    $r['pass'] = true;
} else {
    $r['message'] = "Untuk mengakses API, pastikan GET 'target','method' dan 'data' telah didefinisikan.";
}

echo json_encode($r);
?>