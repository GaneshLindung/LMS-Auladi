<?php
switch($user['role']) {
    case "admin":
        require("ujian/admin.php");
        break;
    case "teacher":
        require("ujian/teacher.php");
        break;
    case "student":
        require("ujian/siswa.php");
        break;
}
?>