<?Php
if($user['role'] == "admin" && isset($_POST['activity'])) {
    switch($_POST['activity']) {
        case "updateUser":
            $IDusr = $_POST['ID'];
            $passw = $_POST['password'];
            $rpass = $_POST['retypepw'];
            $stats = $_POST['status'];

            if($passw != "" && $passw == $rpass) {
                $np = h4sh($passw);
                $update = update("user","password='$np', status='$stats'","ID='$IDusr'");
                $update ? $r['OK'] = true : $r['OK'] = false;
            } else {
                $update = update("user","status='$stats'","ID='$IDusr'");
                $update ? $r['OK'] = true : $r['OK'] = false;
            }

            break;
    }
}
?>