<?php
switch($user['role']) {
    case "admin":
        require("video/main.php");
        break;
    case "teacher":
        require("video/main.php");
        break;
    case "student":
        require("video/siswa.php");
        break;
}
?>