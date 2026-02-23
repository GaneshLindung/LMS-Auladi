<?Php
    if(isset($_POST['activity']) && isset($_POST['ID'])) {
        $activity = $_POST['activity'];
        $ID = $_POST['ID'];
        $check  = select("rsiswa","ID='$ID'");
        if(mysqli_num_rows($check) == 1) {
            $data   = mysqli_fetch_array($check);

            switch($activity){
                case "accept":
                    $nis    = $data['nis'];
                    $pw     = $data['password'];
                    $cl     = $data['class'];
                    $fn     = $data['full_name'];
                    $addr   = $data['address'];
                    $bil    = $data['birthloc'];
                    $bid    = $data['birthdate'];
                    $gd     = $data['gender'];
                    $email  = $data['email'];
                    $phone  = $data['phone'];
                    $status = $data['status'];
                    
                    $insert1 = insert("siswa","nis='$nis', class='$cl', full_name='$fn', address='$addr', birthloc='$bil', birthdate='$bid', gender='$gd', email='$email', phone='$phone'");
                    if($insert1) {
                        $insert2 = insert("user","username='$nis', password='$pw', role='student', status='1'");
                        if($insert2) {
                            $update = update("rsiswa","status='2'","ID='$ID'");
                            if($update) {
                                $r['OK'] = true;
                                $r['t'] = "accepted";
                            }
                        }
                    }
                    break;
                case "reject":
                    $update = update("rsiswa","status='1'","ID='$ID'");
                    if($update) {
                        $r['OK'] = true;
                        $r['t'] = "rejected";
                    }
                    break;
            }
        }
    } 
?>