<?Php if(in_array($user['role'], ["student","teacher"])) { ?>
<style>
    .modal-backdrop {
        z-index: 0!important;
        background: transparent!important;
    }
</style>
<div class="container-fluid">
    <?Php if(!isset($_GET['action'])) { ?>
    <div class="card">
        <div class="header d-flex space-between">
            <h4 class="title">Daftar Pesan</h4>
            <a href="<?= base_url("chat?action=new") ?>" class="btn btn-info btn-fill"><i class="fa fa-paper-plane"></i> Kirim Pesan</a>
        </div>
        <style>
            .d-block {display: block}
            .thread {padding: 15px 0px;}
            .thread:nth-child(even) {background: #F8F8F8;}
            .thread span.category {color: #8cb8ff;font-size: 18px;}
            .thread a.title {color: #3879e0;font-size: 20px;font-weight: bold;}
            
            @media (max-width: 576px) {
                .datecom {display: flex;justify-content: space-between;}
                .mapel {display: inline-block;margin-top: 15px;}
            }

            @media (min-width: 576px) {
                .datecom {display: flex;gap: 10px;}
                .mapel {margin-left: auto;}
            }

            .truncate {
                max-width: 550px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .thread.row img {display: none;}
        </style>
        <div class="content">
            <?Php $get = query("chat","WHERE sender='$unme' OR peer='$unme' ORDER BY startime DESC"); if(mysqli_num_rows($get) > 0) { while($data = mysqli_fetch_array($get)) {?>
            <div class="thread row">
                <div class="col-md-9">
                    <div style="display: block;">
                        <a href="<?= base_url("chat?action=see&ID=".$data['ID']) ?>"><span class="category" style="font-weight: bold;"><?= $data['title'] ?></span></a>
                    </div>
                    <span class="d-block"><b>Dengan :</b> <?= $data['sender'] != $unme ? $users['data'][$data['sender']]['name'] : $users['data'][$data['peer']]['name'] ?> ( <?= $users['data'][$data['sender']]['sign'] ?> )</span>
                    <div class="truncate"><span style="font-weight: bold;"><?= $users['data'][$chat[$data['ID']]['lastChat']['from']] == $unme ? "You : " : "" ?></span><span class="text-muted"><?= base64_decode($chat[$data['ID']]['lastChat']['text']) ?></span></div>
                </div>
                <div class="col-md-3 text-muted text-right">
                    <i class="mapel" style="padding: 5px;border-radius: 5px;background: #f1ffe6;"><?= date("d F Y H:i", $chat[$data['ID']]['last']) ?></i>
                </div>
            </div>
            <?Php } }?>
        </div>
    </div>
    <?Php } else { if($_GET['action'] == "new") { ?>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="header">
                    <h4 class="title">Mulai Pesan</h4>
                </div>
                <div class="content">
                    <form enctype='multipart/form-data'>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Kirim ke</label>
                                <select id="peertype" class="form-control">
                                    <option value="">- Pilih -</option>
                                <?Php if($user['role'] == "student") { ?>
                                    <option value="guru">Guru</option>
                                    <option value="sekelas">Teman Sekelas</option>
                                    <option value="ekskul">Teman Ekskul</option>
                                <?Php } elseif($user['role'] == "teacher") { ?>
                                    <option value="kelas">Murid Kelas</option>
                                    <option value="ekskul">Murid Ekskul</option>
                                <?Php } ?>
                                </select>
                            </div>
                            <div class="col-md-8 form-group">
                                <label>Penerima</label>
                                <select name="peer" class="form-control">
                                    <option value="">- Pilih -</option>
                                <?Php
                                if($user['role'] == "student") {
                                    $getter = query("subjetc","WHERE class='$myclass' OR subject_id IN ($myekskul) GROUP BY nip");
                                    while($data = mysqli_fetch_array($getter)) {
                                        echo "<option data-tipe='guru' value='".$data['nip']."'>".$guru[$data['nip']]['name']."</option>"; 
                                    }
                                    
                                    $getter = query("siswa","WHERE class='$myclass' AND NOT nis='$unme' ORDER BY full_name ASC");
                                    while($data = mysqli_fetch_array($getter)) {
                                        echo "<option data-tipe='sekelas' value='".$data['nis']."'>".$data['full_name']."</option>";
                                    }

                                    $getter = query("subjecs","WHERE subject_id IN ($myekskul) AND NOT nis='$unme' GROUP BY nis");
                                    while($data = mysqli_fetch_array($getter)) {
                                        echo "<option data-tipe='ekskul' value='".$data['nis']."'>".$siswa[$data['nis']]['name']."</option>"; 
                                    }
                                } elseif($user['role'] == "teacher") {
                                    $getter = query("siswa","WHERE class IN ($mclasses) ORDER BY class, full_name ASC");
                                    while($data = mysqli_fetch_array($getter)) {
                                        echo "<option data-tipe='kelas' value='".$data['nis']."'>".$data['full_name']." ( ".$class['detail'][$data['class']]." )</option>";
                                    }

                                    $getter = query("subjecs","WHERE subject_id IN ($mekskul) GROUP BY nis");
                                    while($data = mysqli_fetch_array($getter)) {
                                        echo "<option data-tipe='ekskul' value='".$data['nis']."'>".$siswa[$data['nis']]['name']."</option>"; 
                                    }
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Judul Pesan</label>
                                <input type="text" name="title" class="form-control" placeholder="Judul Pesan" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>TEKS PESAN</label>
                                <textarea name="text" id="teks" rows="7" class="form-control" placeholder="Tuliskan pesanmu disini.."></textarea>
                            </div>
                        </div>
                        <div class="d-flex space-between" style="align-items: center;">
                            <span class="text-warning"></span>
                            <input type="button" id="send" class="btn btn-info btn-fill" value="KIRIM" />
                        </div>
                        <div class="mt-3"></div>
                    </form>
                    <style>
                        .modal-backdrop {
                            z-index: 0!important;
                            background: transparent!important;
                        }
                    </style>
                    <script>
                        $(document).ready(function() {
                            $('#teks').summernote({
                                height: 200, // tinggi editor
                            });
                        });

                        $("select[name='peer'] option").hide();
                        $("select[id='peertype']").on("change", function(){
                            var tipe = $(this).val();
                            $("select[name='peer']").val("");
                            $("select[name='peer'] option").hide();
                            if(tipe != "") {
                                $(`select[name='peer'] option[data-tipe=${tipe}]`).show();
                            }
                        });
                    </script>
                    <script>
                        $("input#send").on("click", function(){
                            $("span.text-warning").html("");
                            var form = $("form")[0],
                                formData = new FormData(form);
                            formData.append("action","crud/chat");
                            formData.append("activity","new");

                            var peer    = $("select[name='peer']").val(),
                                title   = $("input[name='title']").val(),
                                text    = $("textarea[name='text']").val();

                            if(peer != "" && title != "" && text != "") {
                                setTimeout(function(){
                                    $.ajax({
                                        url: "<?= base_url("endpoint/index.php") ?>",
                                        type: "POST",
                                        processData: false,
                                        contentType: false,
                                        data: formData,
                                        success:function(data){
                                            var response = JSON.parse(data);
                                            console.log(data);
                                            if(response.OK == true) {
                                                alert("Berhasil mengirim pesan baru!");
                                                window.location.replace("<?= base_url("chat") ?>");
                                            } else {
                                                $("span.text-warning").html("Gagal mengirim pesan.");
                                            }
                                        }, error:function(data){}
                                    });
                                }, 500);
                            } else {alert("Lengkapi isian");}
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
    <?Php } elseif($_GET['action'] == "see" && isset($_GET['ID'])) {
            $ID = $_GET['ID'];
            $query = query("chat","WHERE ID='$ID'");
            if(mysqli_num_rows($query) == 1) { $cd = mysqli_fetch_array($query); ?>
    <style>.xxcontent img {max-width: 60%;}</style>
    <div class="row xxcontent">
        <div class="col-md-12 d-flex">
            <h3 class="text-warning" style="font-weight: bold;margin: 0px 0px 32px;"><?= $cd['title'] ?></h3>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="header d-flex" style="gap: 16px;align-items: center;">
                    <?Php $d____p = $users['data'][$cd['sender']]['dp'] != "" ? $users['data'][$cd['sender']]['dp'] : "1HL3PQ.png"; ?>
                    <img src="<?= base_url("static/image/display_picture/").$d____p ?>" style="width: 50px;object-fit: cover;aspect-ratio: 1/1;border-radius: 50%;" />
                    <div>
                        <h5 style="margin: 0px;font-weight: bold;"><?= $users['data'][$cd['sender']]['name'] ?></h5>
                        <?= date("H:i - d F Y", $cd['startime']) ?>
                    </div>
                </div>
                <div class="content">
                    <?= nl2br(base64_decode($cd['text'])) ?>
                    <?Php
                    $getter = query("chattalk","WHERE chat_id='$ID' ORDER BY time ASC");
                    if(mysqli_num_rows($getter) > 0) { while($dtx = mysqli_fetch_array($getter)) { $ue = $users['data'][$dtx['sender']]['dp'] == "" ? "1HL3PQ.png" : $users['data'][$dtx['sender']]['dp'] ?>
                    <hr />
                    <div class="reply mt-3">
                        <div class="d-flex" style="gap: 16px;align-items: center;">
                            <img src="<?= base_url("static/image/display_picture/").$ue ?>" style="width: 50px;object-fit: cover;aspect-ratio: 1/1;border-radius: 50%;" />
                            <div>
                                <h5 style="margin: 0px;font-weight: bold;"><?= $users['data'][$dtx['sender']]['name'] ?></h5>
                                <?= date("H:i - d F Y", $dtx['time']) ?>
                            </div>
                        </div>
                        <div class="d-block mt-3">
                            <?= nl2br(base64_decode($dtx['text'])) ?>
                        </div>
                    </div>
                    <?Php }} ?>
                </div>
            </div>
            <div class="card mt-3">
                <div class="header">
                    <h4 class="title">Balas Pesan</h4>
                </div>
                <div class="content">
                    <textarea name="respond" id="respond" rows="4" class="form-control" placeholder="Ketik balasan disini.."></textarea>
                    <div class="d-flex space-between mt-3" style="align-items: center;">
                        <span class="text-warning"></span>
                        <input type="button" id="sendReply" class="btn btn-info btn-fill" value="KIRIM" />
                    </div>
                </div>
                <script>
                    $(document).ready(function() {
                        $('#respond').summernote({
                            height: 200, // tinggi editor
                        });
                    });

                    $("input#sendReply").on("click", function(){
                        $("span.text-warning").html("");
                        var text = $("textarea[name='respond']").val();

                        if(text != "") {
                            setTimeout(function(){
                                $.ajax({
                                    url: "<?= base_url("endpoint/index.php") ?>",
                                    type: "POST",
                                    data: {
                                        "action":"crud/chat",
                                        "activity":"send",
                                        "chat_id":<?= $_GET['ID'] ?>,
                                        "respond":text
                                    },
                                    success:function(data){
                                        var response = JSON.parse(data);
                                        console.log(data);
                                        if(response.OK == true) {
                                            window.location.reload();
                                        } else {
                                            $("span.text-warning").html("Gagal mengirim pesan.");
                                        }
                                    }, error:function(data){}
                                });
                            }, 500);
                        }
                    });
                </script>
            </div>
        </div>
    </div>
            <?Php
            } else {

            }
        }
    } ?>
</div>
<?Php } ?>