<?php
if($user['role'] == "student") {
    ?><script>window.location.replace("<?= base_url("materi") ?>");</script><?php
} else {
    switch($user['role']) {
        case "admin":
            require("home/admin.php");
            break;
        case "teacher":
            require("home/teacher.php");
            break;
    }
}
?>