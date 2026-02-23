<?php
switch($user['role']) {
    case "admin":
        require("materi/main.php");
        break;
    case "teacher":
        require("materi/main.php");
        break;
    case "student":
        require("materi/siswa.php");
        break;
}
?>