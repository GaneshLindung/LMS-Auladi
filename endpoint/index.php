<?php
require("../_core.php");

$r = [];
$r['OK'] = false;
$r['POST'] = $_POST;
if(isset($_FILES)) {$r['FILES'] = $_FILES;}
if(isset($_POST['action'])) {
    $act = $_POST['action'];
    if(file_exists($act.".php")) {
        require($act.".php");
    } else {
        $r['m'] = "!exists";
    }
} else {
    $r['m'] = "missing post.action";
}

echo json_encode($r, JSON_PRETTY_PRINT);
?>