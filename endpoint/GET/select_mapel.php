<?Php
if(isset($_POST['class'])) {
    $class = $_POST['class'];
    if($user['role'] == "admin") {
        $get = query("subjetc","WHERE class='$class'");
    } elseif($user['role'] == "teacher") {
        $get = query("subjetc","WHERE class='$class' AND nip='$unme'");
    }

    if(mysqli_num_rows($get) > 0) {
        $ar = [];
        while($in = mysqli_fetch_array($get)) {
            $ar[] = "<option data-r='AJAX' data-tipe='".$mapel['detail'][$in['subject_id']]['tipe']."' value='".$in['subject_id']."'>".$mapel['detail'][$in['subject_id']]['title']."</option>";
        }
        
        $r['result'] = $ar;
        $r['OK'] = true;
    } else {
        $r['OK'] = false;
    }
}
?>