<?Php
if(isset($_POST['activity'])) {
    switch($_POST['activity']) {
        case "add":
            $mapel = $_POST['mapel'];
            $bab   = $_POST['bab'];

            $title = $_POST['title'];
            $linkV = $_POST['urlvideo'];

            $hash  = randomtoken(32);

            $inser = insert("video","hash='$hash', title='$title', bab='$bab', subject_id='$mapel', uploader='$unme', url='$linkV'");
            if($inser) {
                $get = select("video","hash='$hash'");
                if(mysqli_num_rows($get) == 1) {
                    $data = mysqli_fetch_array($get);
                    $ID = $data['ID'];

                    for ($i=0;$i<=count($_POST['class'])-1;$i++) {
                        $class = $_POST['class'][$i];
                        insert("video_d","video_id='$ID', class='$class'");
                    }
                }
                
                $r['OK'] = true;
            }
            break;
        case "update":
            $ID = $_POST['ID'];
            $select = select("video","ID='$ID'");
            if(mysqli_num_rows($select) == 1) {
                $mdata  = mysqli_fetch_array($select);
                $title  = $_POST['title'] ?? $mdata['title'];
                $vourl  = $_POST['urlvideo'] ?? $mdata['url'];

                $updat  = update("video","title='$title', url='$vourl'","ID='$ID'");

                if($updat) {
                    $r['OK'] = true;

                    delete("video_d","video_id='$ID'");
                    for($i = 0; $i <= count($_POST['class'])-1; $i++) {
                        $class = $_POST['class'][$i];
                        insert("video_d","video_id='$ID', class='$class'");
                    }
                }
            }
            break;
        case "delete":
            $ID = $_POST['ID'];

            $delete = delete("video","ID='$ID'");
            if($delete) {
                $r['OK'] = true;

                delete("video_d","video_id='$ID'");
            }
            break;
    }
}
?>