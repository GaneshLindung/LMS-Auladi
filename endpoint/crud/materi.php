<?Php
if(isset($_POST['activity'])) {
    switch($_POST['activity']) {
        case "add":
            $kelas = $_POST['class'] ?? "";
            $mapel = $_POST['mapel'] ?? "";
            $bab   = $_POST['bab'] ?? "";
            $title = $_POST['title'] ?? "";

            $hash  = randomtoken(32);

            if (isset($_FILES['file'])) {
                $file  = $_FILES['file'];
                $dots = explode(".", $file['name']);
                if(count($dots) == 2) {
                    $nm = $dots[0] . "-" . randomtoken(16);
                    $fm = fjoin($nm, $dots[1]);
                    $up = upload($file, "../static/files/modules/".$fm);
                    if($up) {
                        $insert = insert("materi","hash='$hash', title='$title', bab='$bab', subject_id='$mapel', file_name='$fm', uploader='$unme', hits='0'");
                        if($insert) {
                            $get = select("materi","hash='$hash'");
                            if(mysqli_num_rows($get) == 1) {
                                $data = mysqli_fetch_array($get);
                                $ID = $data['ID'];

                                foreach ($_POST['class'] as $clxx) {
                                    insert("materi_d","materi_id='$ID', class='$clxx'");

                                    $SELECT = query("siswa","WHERE class='$clxx' ORDER BY ID ASC");
                                    if(mysqli_num_rows($SELECT) > 0) {
                                        while($DATA = mysqli_fetch_array($SELECT)){
                                            $PEER = $DATA['nis'];
                                            $i = insert("notification","type='newmateri', reff='$mapel', sender='$unme', peer='$PEER', title='$title', timestamp='$timestamp'");
                                        }
                                    }
                                }
                            }
                            $r['OK'] = true;
                        }
                    }
                }
            }

            break;
        case "update":
            $ID = $_POST['ID'];
            $select = select("materi","ID='$ID'");
            if(mysqli_num_rows($select) == 1) {
                $mdata  = mysqli_fetch_array($select);
                $title  = $_POST['title'] ?? $mdata['title'];

                if(isset($_FILES['file'])) {
                    $dots = explode(".", $_FILES['file']['name']);
                    if(count($dots) == 2) {
                        $nm = $dots[0] . "-" . randomtoken(16);
                        $fm = fjoin($nm, $dots[1]);
                        $up = upload($_FILES['file'], "../static/files/modules/".$fm);
                        if($up) {$fn = $fm;}
                    }
                }

                $filename = $fn ?? $mdata['file_name'];

                $updat  = update("materi","title='$title', file_name='$filename'","ID='$ID'");

                if($updat) {
                    $r['OK'] = true;

                    delete("materi_d","materi_id='$ID'");
                    for($i = 0; $i <= count($_POST['class'])-1; $i++) {
                        $class = $_POST['class'][$i];
                        insert("materi_d","materi_id='$ID', class='$class'");
                    }
                }
            }
            break;
        case "delete":
            $ID = $_POST['ID'];

            $delete = delete("materi","ID='$ID'");
            if($delete) {
                $r['OK'] = true;

                delete("materi_d","materi_id='$ID'");
            }
            break;
    }
}
?>