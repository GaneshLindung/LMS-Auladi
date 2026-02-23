<?Php
if($user['role'] == "student") {
    $hash = $_POST['hash'];
    $pwww = ujian($_POST['password']);
    $GET  = select("exam_d","exam_id='$hash' AND class='$myclass'");
    if(mysqli_num_rows($GET) == 1) {
        $GET  = select("examl","exam_id='$hash' AND password='$pwww'");
        if(mysqli_num_rows($GET) == 1) {
            $data = mysqli_fetch_array($GET);
            $GET = select("exam_s","exam_id='$hash' AND nis='$unme'");
            if(mysqli_num_rows($GET) == 0) {
                $deadline = $timestamp + (60*$data['duration']);
                $INSERT = insert("exam_s","exam_id='$hash', nis='$unme', deadline='$deadline'");
                if($INSERT) {$r['OK'] = true;}
            } else { $r['OK'] = true; }
        } else { $r['OK'] = false; }
    }
}
?>