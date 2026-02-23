<?Php if(isset($_POST['nis'])) {
    $NIS = $_POST['nis'];
    $GET = select("siswa","nis='$NIS'");
    if(mysqli_num_rows($GET) == 1) {
        $data = mysqli_fetch_array($GET);
        $r['data'] = array(
            "name" => $data['full_name'],
            "nis" => $data['nis'],
            "class" => $class['info'][$data['class']]
        );
        $r['OK'] = true;
    } 
} ?>