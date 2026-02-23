<style>
    .modal-backdrop {
        z-index: 0!important;
        background: transparent!important;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <?Php if(!isset($_GET['action'])) { ?>
        <div class="col-md-12">
            <div class="card">
                <div class="header d-flex space-between">
                    <h4 class="title">Daftar Forum</h4>
                    <?= '<a href="'.base_url("forum?action=add").'" class="btn btn-sm btn-info btn-fill">Posting Pembahasan</a>' ?>
                </div>
                <style>
                    .thread {padding: 15px 0px;}
                    .thread:nth-child(even) {background: #EFEFEF;}
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
                </style>
                <div class="content">
                    <?Php

                    $gf = query("forum_talk","ORDER BY ID ASC");
                    while($dt = mysqli_fetch_array($gf)) {
                        $ft[$dt['forum_id']] = array(
                            "count_comment" => isset($ft[$dt['forum_id']]['count_comment']) ? $ft[$dt['forum_id']]['count_comment'] + 1 : "1"
                        );
                    }

                    $getdata = $user['role'] == "admin" ? query("forum","ORDER BY ID DESC") : ($user['role'] == "teacher" ? query("forum","WHERE class IN ($mclasses) ORDER BY ID DESC") : query("forum","WHERE class='$myclass' OR subject_id IN ($myekskul) ORDER BY ID DESC"));
                    if(mysqli_num_rows($getdata) > 0) { while($data = mysqli_fetch_array($getdata)) {
                        $cond = false;
                        if($user['role'] == "teacher") {
                            if(isset($classes['teacher'][$user['username']]["x".$data['class']]) && in_array($data['subject_id'], $classes['teacher'][$user['username']]["x".$data['class']])) {$cond = true;}
                        } elseif($user['role'] == "student") {
                            if($data['class'] != 0) {
                                if($classes['student'][$user['username']]["class"] == $data['class']) {$cond = true;}
                            } else {
                                if(isset($classes['student'][$user['username']]["x".$data['class']])) {$cond = true;}
                            }
                        } elseif($user['role'] == "admin") {$cond = true;}

                        if($cond == true) {
                        ?>
                    <div class="thread row">
                        <div class="col-md-9">
                            <div style="display: block;">
                                <span class="category">[<?= $data['category'] == "1" ? "Tanya" : ($data['category'] == "2" ? "Berbagi" : "Pembahasan") ?>]</span>
                                <a href="<?= base_url("forum?action=open&ID=".$data['ID']) ?>" class="title"><?= base64_decode($data['title']) ?></a>
                            </div>
                            <span class="text-muted">Oleh : <?= $users['data'][$data['thread_starter']]['name'] ?> ( <?= $users['data'][$data['thread_starter']]['sign'] ?> )</span>
                            <div class="datecom">
                                <span class="text-danger">Dimulai : <?= date("d M Y", $data['publish_date']) ?></span>
                                <span><i class="fa fa-cloud text-warning"></i> <?= $ft[$data['ID']]['count_comment'] ?? "0" ?> Komentar</span>
                            </div>
                        </div>
                        <div class="col-md-3 text-muted text-right">
                            <i class="mapel" style="padding: 5px;border-radius: 5px;background: #f1ffe6;"><?= $mapel['detail'][$data['subject_id']]['title'] ?? "ERROR : Mapel ID not_found" ?></i>
                        </div>
                    </div>
                    <?Php }}} ?>
                </div>
            </div>
        </div>
        <?Php } else { $act = $_GET['action']; if($act == "open" && isset($_GET['ID'])) { $ID = $_GET['ID']; $get = select("forum","ID='$ID'"); if(mysqli_num_rows($get) == 1) { $data = mysqli_fetch_array($get); ?>
        <style>
            span.category {font-size: 29px;}
            #creator * {display: block;}

            @media (min-width: 768px) {
                #titleSection {display: flex;align-items: center;gap: 10px;}
            }
            
            @media (max-width: 992px) {
                #creator {display: none;}
            }
        </style>
        <div class="col-md-12">
            <h3 style="margin: 0px 0px 15px 0px;">Forum Diskusi</h3>
            <div class="card">
                <div id="titleSection" class="content">
                    <span class="category" style="font-size: 23px;">[<?= $data['category'] == "1" ? "Tanya" : ($data['category'] == "2" ? "Berbagi" : "Pembahasan") ?>]</span>
                    <span style="font-size: 30px;"><?= base64_decode($data['title']) ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8" id="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h5 class="title"><b>Isi Pembahasan</b></h5>
                                </div>
                                <div class="content">
                                    <div style="padding: 16px 0px;font-size: 15px;">
                                        <?= nl2br(base64_decode($data['description'])) ?>
                                    </div>
                                    <?Php if($user['role'] == "admin" || $unme == $data['thread_starter']) { ?>
                                        <a class="btn btn-sm btn-danger cursor-pointer" onclick="deleteThread()"><i class="fa fa-close"></i> Hapus</a>
                                        <script>
                                            function deleteThread() {
                                                var convirm = prompt("Ketik 'confirm' untuk melanjutkan tindakan ini");
                                                if (convirm == "confirm") {
                                                    $.ajax({
                                                        url: "<?= base_url("endpoint/index.php") ?>",
                                                        type: "POST",
                                                        data: {
                                                            "action":"crud/forum",
                                                            "activity":"delete",
                                                            "forum_id":"<?= $ID ?>"
                                                        },
                                                        success:function(data){
                                                            var response = JSON.parse(data);
                                                            console.log(data);
                                                            if(response.OK == true) {
                                                                window.location.replace("<?= base_url("forum") ?>");
                                                            }
                                                        }, error:function(data){}
                                                    });
                                                }
                                            }
                                        </script>
                                    <?Php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title">Komentar</h4>
                                </div>
                                <div class="content">
                                    <style>
                                        .comment {padding: 10px;border: 1px dashed #EFEFEF;}
                                        .comment:nth-child(even) {background: #F7F7F7;}
                                        span.name {font-size: 16px;color: #3879e0;}
                                        .fprofile {object-fit: cover;aspect-ratio: 1/1;}
                                    </style>
                                    <?Php
                                    $getcomment = query("forum_talk","WHERE forum_id='$ID' ORDER BY ID ASC");
                                    while($datx = mysqli_fetch_array($getcomment)) {
                                        $nmc = $users['data'][$datx['user_id']]['name'] ?? "ERROR : USER NOT FOUND"; ?>
                                        <div class="comment" style="margin-bottom: 16px;display: flex;gap: 16px;">
                                            <div style="max-width: 75px;">
                                                <img src="<?= $users['data'][$datx['user_id']]['dp'] != "" ? (file_exists("static/image/display_picture/".$users['data'][$datx['user_id']]['dp']) ? base_url("static/image/display_picture/".$users['data'][$datx['user_id']]['dp']) : $defaultDP) : $defaultDP ?>" class="fprofile" />
                                            </div>
                                            <div style="width: auto;    ">
                                                <div><span class="name"><?= $nmc ?></span><span class="text-muted"> (<?= $users['data'][$datx['user_id']]['sign'] ?>)</span></div>
                                                <div><span><?= base64_decode(nl2br($datx['respond'])) ?></span></div>
                                            </div>
                                        </div>
                                    <?Php } ?>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <h6>Komentar Baru</h6>
                                            <form class="d-block mt-3">
                                                <textarea name="response" id="respond" class="form-control mt-1" rows="4" placeholder=""></textarea>
                                                <input type="button" class="mt-3 btn btn-fill btn-info" value="KIRIM">
                                            </form>
                                            <script>
                                                $(document).ready(function() {
                                                    $('#respond').summernote({
                                                        height: 200, // tinggi editor
                                                    });
                                                });

                                                $("form input[type='button']").on("click", function(){
                                                    var form = $("form")[0],
                                                        formData = new FormData(form);

                                                    formData.append("action","crud/forum");
                                                    formData.append("activity","talk_post");
                                                    formData.append("forum_id", <?= $ID ?>);

                                                    $.ajax({
                                                        url: "<?= base_url("endpoint/index.php") ?>",
                                                        type: "POST",
                                                        data: formData,
                                                        processData: false,
                                                        contentType: false,
                                                        success:function(data){
                                                            var response = JSON.parse(data);
                                                            console.log(data);
                                                            if(response.OK == true) {
                                                                window.location.reload();
                                                            }
                                                        }, error:function(data){}
                                                    });
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" id="creator">
                    <div class="card">
                        <div class="header">
                            <h5 class="title"><b>Pembuat Posting</b></h5>
                        </div>
                        <div class="content">
                            <div>
                                <img src="<?= $users['data'][$data['thread_starter']]['dp'] != "" ? (file_exists("static/image/display_picture/".$users['data'][$data['thread_starter']]['dp']) ? base_url("static/image/display_picture/".$users['data'][$data['thread_starter']]['dp']) : $defaultDP) : $defaultDP ?>" style="max-width: 175px;border-radius: 5px;" />
                                <span style="margin-top: 10px;font-size: 17px;"><?= $users['data'][$data['thread_starter']]['name'] ?? "ERROR" ?></span>
                                <span><?= $users['data'][$data['thread_starter']]['sign'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?Php }} if($act == "add") { ?>
        <div class="col-md-6">
            <div class="card">
                <div class="header">
                    <h4 class="title">Buat Forum Pembahasan</h4>
                </div>
                <div class="content">
                    <form>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Pilih Kategori</label>
                                    <select name="category" class="form-control">
                                        <option value="">- Pilih -</option>
                                        <option value="1">Tanya</option>
                                        <option value="2">Berbagi</option>
                                        <option value="3">Pembahasan</option>
                                    </select>
                                </div>
                            </div>
                            <?Php if(in_array($user['role'], ["admin","teacher"])) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Pilih Kelas</label>
                                    <select name="class" class="form-control">
                                        <option value="">Pilih Kelas</option>
                                    <?Php
                                        if($user['role'] == "teacher") {
                                            for($i=0;$i<=count($class['array'])-1;$i++) {
                                                if(in_array($class['array'][$i]['ID'], $guru[$unme]['classes'])) {
                                                    echo "<option value='".$class['array'][$i]['ID']."'>".$class['array'][$i]['name']."</option>";
                                                }
                                            }
                                        } else {
                                            foreach($class['array'] as $clx) {
                                                echo "<option value='".$clx['ID']."'>".$clx['name']."</option>";
                                            }
                                        }
                                    ?>
                                        <option value="0">Ekskul</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Pilih Mata Pelajaran</label>
                                    <select name="mapel" class="form-control" disabled>
                                        <option value="">Pilih Mapel</option>
                                    </select>
                                </div>
                            </div>
                            <?Php } else { ?>
                            <input type="hidden" name="class" value="<?= $myclass ?>" />
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Pilih Mata Pelajaran</label>
                                    <select name="mapel" class="form-control">
                                        <option value="">Pilih Mapel</option>
                                        <?Php foreach($class['more_info'][$myclass]['mapel'] as $cid) { echo "<option value='".$cid."'>".$mapel['detail'][$cid]['title']."</option>"; } ?>
                                    </select>
                                </div>
                            </div>
                            <?Php } ?>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>JUDUL FORUM</label>
                                <input type="text" name="title" class="form-control" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <textarea class="form-control" id="theKonten" name="deskripsi" style="height: 250px;" placeholder="Deskripsi"></textarea>
                            </div>
                        </div>
                        <div class="row"><div class="col-md-12"><input type="button" class="btn btn-fill btn-info" value="KIRIM" /></div></div>
                    </form>
                    <script>
                        $(document).ready(function() {
                            $('#theKonten').summernote({
                                height: 200, // tinggi editor
                            });
                        });
                        
                        $("select[name='class']").on("change", function(){
                            var val = $(this).val();
                            $("select[name='mapel'] option[data-r='AJAX']").remove();
                            $.ajax({
                                url: "<?= base_url("endpoint/index.php") ?>",
                                type: "POST",
                                data: {"action":"GET/select_mapel","class":val},
                                success:function(data){
                                    var response = JSON.parse(data);
                                    console.log(data);
                                    if(response.OK == true) {
                                        $("select[name='mapel']").attr("disabled", false);
                                        
                                        $.each(response.result, function(index, item) {
                                            var appends = item;
                                            $("select[name='mapel']").append(appends);
                                        });
                                    } else {
                                        $("select[name='mapel']").html("");
                                        $("select[name='mapel']").attr("disabled", true);
                                    }
                                }, error:function(data){}
                            });
                        });

                        $("form input[type='button']").on("click", function(){
                            var form = $("form")[0],
                                formData = new FormData(form);

                            formData.append("action","crud/forum");
                            formData.append("activity","add");

                            $.ajax({
                                url: "<?= base_url("endpoint/index.php") ?>",
                                type: "POST",
                                data: formData,
                                processData: false,
                                contentType: false,
                                success:function(data){
                                    var response = JSON.parse(data);
                                    console.log(data);
                                    if(response.OK == true) {
                                        window.location.replace("<?= base_url("forum") ?>");
                                    } else {
                                        alert("Lengkapi isian");
                                    }
                                }, error:function(data){}
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
        <?Php } } ?>
    </div>
</div>