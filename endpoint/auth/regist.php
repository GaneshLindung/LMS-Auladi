<?php if(isset($_POST['full_name'])) {
    $ni = $_POST['nis'] ?? "";
    $fn = $_POST['full_name'] ?? "";
    $cl = $_POST['kelas'] ?? "";
    $gd = $_POST['gender'] ?? "";
    $ph = $_POST['phone'] ?? "";
    $em = $_POST['email'] ?? "";
    $bl = $_POST['birthloc'] ?? "";
    $bd = $_POST['birthdate'] ?? "";
    $pw = h4sh($_POST['password']);

    if($ni != "" && $fn  != "" && $cl != "" && $gd != "" && $ph != "" && $em != "" && $bl != "" && $bd != "" && $pw != "") {
        $check = select("rsiswa","nis='$ni'");
        if(mysqli_num_rows($check) == 0) {
            $in = insert("rsiswa","nis='$ni', password='$pw', full_name='$fn', class='$cl', address='', birthloc='$bl', birthdate='$bd', gender='$gd', email='$em', phone='$ph', status='0'");
            if($in) {$r['OK'] = true;}
        } else {
            $r['m'] = "NIS sudah terdaftar.";
        }
    } else {$r['m'] = "Lengkapi isian.";}
} ?>