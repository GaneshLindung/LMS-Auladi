<?Php
if(isset($_POST['mapel'])) {
    $mpl = $_POST['mapel'];

    $r['result'] = array(
        "choice" => [],
        "essay" => [],
        "classes" => []
    );

    $get = query("exam_p","WHERE subject_id='$mpl' AND uploader='$unme'");
    if(mysqli_num_rows($get) > 0) {
        while($data = mysqli_fetch_array($get)) {$r['result'][$data['type']][] = "<option value='".$data['hash']."' data-f='AJAX'>".$data['title']."</option>";}
    }

    $get2 = query("subjetc","WHERE subject_id='$mpl' AND nip='$unme'");
    if(mysqli_num_rows($get2) > 0) {
        while($in = mysqli_fetch_array($get2)) {
            $r['result']['classes'][] = "<div class='fgroup' data-kelas='".$in['class']."'><input type='checkbox' name='class[]' value='".$in['class']."' /> <span>".$class['info'][$in['class']]."</span></div>";
            $r['tipe'] = $in['class'] == "0" ? "ekskul" : "umum";
        }
    }
    $r['OK'] = true;
}
?>