<?Php
if(isset($_POST['activity'])) {
    switch($_POST['activity']) {
        case "add":
            $title = $_POST['title'];
            $tipe  = $_POST['tipe'];
            $teach = $_POST['mapel_pengajar'];
            
            $search = select("subject","subject_title='$title' AND tipesubject='$tipe'");
            if(mysqli_num_rows($search) == 0) {
                $insert = insert("subject","subject_title='$title', tipesubject='$tipe'");
                if($insert) {
                    $get = select("subject","subject_title='$title' AND tipesubject='$tipe'");
                    if(mysqli_num_rows($get) == 1) {
                        $d = mysqli_fetch_array($get);
                        $ID = $d['ID'];

                        for($x=0;$x<=count($teach)-1;$x++) {
                            $explode = explode(":", $teach[$x]);
                            if(count($explode) == 2) {
                                $class = $explode[0];
                                $nip   = $explode[1];

                                insert("subjetc","subject_id='$ID', class='$class', nip='$nip'");
                            }
                        }

                        $r['OK'] = true;
                    }
                }
            }
            break;
        case "update":
            $ID = $_POST['ID'];
            $select = select("subject","ID='$ID'");
            if(mysqli_num_rows($select) == 1) {
                $mdata  = mysqli_fetch_array($select);
                $title  = $_POST['title'] ?? $mdata['subject_title'];
                $updat  = update("subject","subject_title='$title'","ID='$ID'");

                if($updat) {
                    $teach  = $_POST['mapel_pengajar'];
                    for($x=0;$x<=count($teach)-1;$x++) {
                        $exp = explode(":", $teach[$x]);
                        if(count($exp) == 2) {
                            $class = $exp[0];
                            $techr = $exp[1];

                            $check = select("subjetc","subject_id='$ID' AND class='$class'");
                            if(mysqli_num_rows($check) == 1) {
                                $thisdata = mysqli_fetch_array($check); $ids = $thisdata['ID'];
                                if($techr != "-") {
                                    update("subjetc","subject_id='$ID', class='$class', nip='$techr'","ID='$ids'");
                                } else {
                                    delete("subjetc","ID='$ids'");
                                }
                            } else {
                                if($tehcr != "-") {insert("subjetc","subject_id='$ID', class='$class', nip='$techr'");}
                            }
                        }
                    }

                    $r['OK'] = true;
                }
            }
            break;
            
        case "delete":
            $sid = $_POST['mapel_id'];
            delete("subjetc","subject_id='$sid'");
            delete("subjecs","subject_id='$sid'");
            $delet = delete("subject","ID='$sid'");
            if($delet) {
                $r['OK'] = true;
            }
        
        case "addToEkskul":
            $sid = $_POST['nisfix'];
            $mid = $_POST['ekskul'];
            if(isset($siswa[$sid])) {
                if(isset($siswa[$sid]['ekskul']) && in_array($mid, $siswa[$sid]['ekskul'])) {
                    $insert = true;
                } else {
                    $insert = insert("subjecs","nis='$sid', subject_id='$mid'");
                }

                $insert ? $r['OK'] = true : $r['MSG'] = "Gagal menambahkan murid ke ekskul.";
                
            } else {
                $r['MSG'] = "NIS tidak terdaftar";
            }
    }
}
?>