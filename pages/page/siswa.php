<?php
switch($user['role']) {
    case "admin":
        require("siswa/main.php");
        break;
}
?>