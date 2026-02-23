<?Php
if(isset($_POST['mapel'])) {
    $mpl = $_POST['mapel'];
    $get = query("subjetc","WHERE subject_id='$mpl' AND nip='$unme'");
    if(mysqli_num_rows($get) > 0) { $ar = [];
        $r['tipe'] = "umum";
        while($in = mysqli_fetch_array($get)) {
            $ar[] = "<div class='fgroup' data-kelas='".$in['class']."'><input type='checkbox' name='class[]' value='".$in['class']."' /> <span>".$class['info'][$in['class']]."</span></div>";
            $r['tipe'] = $in['class'] == "0" ? "ekskul" : "umum";
        }
        
        $r['result'] = $ar;
        $r['OK'] = true;
    } else {
        $r['OK'] = false;
    }
}
?>