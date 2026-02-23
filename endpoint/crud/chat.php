<?Php
if(isset($_POST['activity'])) {
    if($_POST['activity'] == "new") {
        $peer = $_POST['peer'];
        $title = $_POST['title'];
        $text = base64_encode($_POST['text']);

        $image = "";
        if(isset($_FILES['image'])) {
            $image = $timestamp . "-" . $_FILES['image']['name'];
            upload($_FILES['image'], "../static/image/chat/".$image);
        }

        $insert = insert("chat","sender='$unme', peer='$peer', title='$title', image='$image', text='$text', startime='$timestamp'");
        if($insert) {$r['OK'] = true;}
    } elseif($_POST['activity'] == "send") {
        $ID = $_POST['chat_id'];
        $text = base64_encode($_POST['respond']);
        $insert = insert("chattalk","chat_id='$ID', sender='$unme', text='$text', time='$timestamp'");
        if($insert) {$r['OK'] = true;}
    }
}
?>