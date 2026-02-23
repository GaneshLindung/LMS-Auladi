<?Php
if(in_array($user['role'], ["student","teacher"]) && isset($_POST['activity'])) {
    switch($_POST['activity']) {
        case "update":
            $fn = $_POST['full_name'];
            $gd = $_POST['gender'];
            $ph = $_POST['phone'];
            $em = $_POST['email'];
            $bl = $_POST['birthloc'];
            $bd = $_POST['birthdate'];
            $ad = $_POST['address'];

            $tb = $user['role'] == "student" ? "siswa" : "teacher";
            $id = $user['role'] == "student" ? "nis" : "nip";
            $up = update($tb, "full_name='$fn', address='$ad', birthloc='$bl', birthdate='$bd', gender='$gd', phone='$ph', email='$em'","$id='$unme'");
            if($up) {$r['OK'] = true;}

            if(isset($_FILES['profile_photo'])) {
                $file  = $_FILES['profile_photo'];
                $dots = explode(".", $file['name']);
                if(count($dots) == 2) {
                    $fm = fjoin(randomtoken(64), $dots[1]);
                    $up = upload($file, "../static/image/display_picture/".$fm);

                    $up = update($tb, "photo='$fm'","$id='$unme'");
                }
            }
            
            break;
        
        case "updatePassword":
            $pw = h4sh($_POST['newPassword']);
            $up = update("user","password='$pw'","username='$unme'");
            if($up) {$r['OK'] = true;}
            break;
    }
}
?>