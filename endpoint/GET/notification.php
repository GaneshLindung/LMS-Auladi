<?Php if(isset($unme)) {
    $GET = select("notification","peer='$unme' AND stick='0' LIMIT 1");
    if(mysqli_num_rows($GET) == 1) {
        $r['OK'] = true;
        $data = mysqli_fetch_array($GET);
        $ID = $data['ID'];

        if($data['type'] == "forum") {
            $reff = $forum[$data['reff']]['ID'];
        } else {
            $reff = $data['reff'];
        }

        $r['notification'] = array(
            "type" => $data['type'],
            "reff" => $reff,
            "sender" => $users['data'][$data['sender']]['name'],
            "title" => base64_decode($data['title'])
        );

        update("notification","stick='1'","ID='$ID'");
    }
} ?>