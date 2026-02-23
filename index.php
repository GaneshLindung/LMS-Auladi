<?php require("_core.php");
$target_page = $_GET['target'] ?? "";
isset($user) ? require("pages/index.php") : require("pages/page/_auth.php");
?>