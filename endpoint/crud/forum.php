<?Php
if(isset($_POST['activity'])) {
    if($_POST['activity'] == "add") {
        $HASH  = md5(randomtoken(10240));
        $title = base64_encode($_POST['title']);
        $categ = $_POST['category'];
        $class = $_POST['class'];
        $mapel = $_POST['mapel'];
        $deskr = base64_encode($_POST['deskripsi']);

        $insert = insert("forum","hash='$HASH', thread_starter='$unme', publish_date='$timestamp', category='$categ', class='$class', subject_id='$mapel', title='$title', description='$deskr'");
        if($insert) {
            $r['OK'] = true;

            if ($class != "0") {
                $SELECT = query("siswa","WHERE class='$class' ORDER BY ID ASC");
                if(mysqli_num_rows($SELECT) > 0) {
                    while($DATA = mysqli_fetch_array($SELECT)){
                        $PEER = $DATA['nis'];
                        $i = insert("notification","type='forum', reff='$HASH', sender='$unme', peer='$PEER', title='$title', timestamp='$timestamp'");
                    }
                }
            }
        }
    }

    if($_POST['activity'] == "talk_post") {
        $forumid = $_POST['forum_id'];
        $user_id = $unme;
        $publish = $timestamp;
        $reponse = base64_encode($_POST['response']);
        
        $insert  = insert("forum_talk","forum_id='$forumid', user_id='$user_id', publish_date='$publish', respond='$reponse'");
        if($insert) {$r['OK'] = true;}
    }

    if($_POST['activity'] == "delete") {
        $forumid = $_POST['forum_id'];

        $delete = delete("forum","ID='$forumid'");
        if($delete) {$r['OK'] = true;}
    }
}
?>