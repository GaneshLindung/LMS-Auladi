<?Php update("notification","status='1'","peer='$unme'"); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h3 class="title">Notifikasi</h3>
                </div>
                <div class="content">
                    <div class="tableHolder">
                        <table class="table table-striped">
                            <tbody>
                            <?Php
                            $GET = query("notification","WHERE peer='$unme' ORDER BY ID DESC");
                            if(mysqli_num_rows($GET) > 0) { while($DATA = mysqli_fetch_array($GET)) {
                                $reff = $DATA['reff'];
                                $sender = $DATA['sender'];
                                $peer = $DATA['peer'];
                                $title = base64_decode($DATA['title']);
                                $description = $DATA['description'];
                                $stick = $DATA['stick'];
                                $status = $DATA['status'];

                                switch($DATA['type']) {
                                    case "forum":
                                        if(isset($forum[$reff])) {
                                            $notifikasi = "<a href='".base_url("forum?action=open&ID=".$forum[$reff]['ID'])."'><i class='fa fa-external-link'></i></a> Forum baru dibuka : " . $title;
                                        }
                                        break;
                                    case "newmateri":
                                        if (isset($mapel['detail'][$DATA['reff']])) {
                                            $notifikasi = "<a href='".base_url("materi?action=detail&materi_id=".$reff)."'><i class='fa fa-external-link'></i></a> Ada materi baru di mata pelajaran " . $mapel['detail'][$DATA['reff']]['title'];
                                        }
                                        break;
                                }
                                ?><tr><td><?= date("d M Y, H:i", $DATA['timestamp']) . " - " . $notifikasi ?? "" ?></td></tr><?Php } } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>