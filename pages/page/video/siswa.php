<style>
    .videoWrapper {
        position: relative;
        padding-bottom: 56.25%;
        padding-top: 25px;
        height: 0;
    }

    .videoWrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0px solid transparent;
        border-radius: 4px;
        overflow: hidden;
    }

    .video.card {border-radius: 10px;overflow: hidden;}
    .video.card .header {background: #FFF;padding-bottom: 5px;border-bottom: 1px solid #EFEFEF;}
    .video.card .header h5 {color: #4e73df!important;font-weight: bold;}

    @media (min-width: 768px) {
        .md-jbetween {display: flex;justify-content: space-between;align-items: center;}
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="title" style="margin: 0px;font-weight: bold;">Video Interaktif</h2>
            <div class="md-jbetween">
                <h4 style="margin: 0px;">Daftar Video Pada Pelajaran Kamu</h4>
                <?Php if(isset($_GET['subject_id'])) {$addx = "grid-template-columns: 1fr 1fr;";} ?>
                <div style="display: grid;gap: 15px;max-width: 500px;margin-bottom: 15px;<?= $addx ?>">
                    <select name="pilih_mapel" class="filtermateri form-select form-control shadow-none" data-change="mapel">
                        <option value="all">Semua Mapel</option>
                        <?php for($i=0;$i<count($mapel['tipe']['umum']);$i++) {
                            $tID = $mapel['tipe']['umum'][$i]['ID'];
                            $tAC = ""; if(isset($_GET['subject_id'])) {
                                if($_GET['subject_id'] == $tID) {$tAC = "selected";}
                            }
                        ?>
                        <option value="<?= $tID ?>" data-tipe="umum" <?= $tAC ?>><?= $mapel['tipe']['umum'][$i]['NAME'] ?></option>
                        <?php } ?>
                        <?php for($i=0;$i<count($mapel['tipe']['ekskul']);$i++) {
                            $tID = $mapel['tipe']['ekskul'][$i]['ID'];
                            $tAC = ""; if(isset($_GET['subject_id'])) {
                                if($_GET['subject_id'] == $tID) {$tAC = "selected";}
                            }
                            if(in_array($mapel['tipe']['ekskul'][$i]['ID'], explode(",",$myekskul))) { ?>
                        <option value="<?= $mapel['tipe']['ekskul'][$i]['ID'] ?>" data-tipe="ekskul" <?= $tAC ?>>EKSKUL - <?= $mapel['tipe']['ekskul'][$i]['NAME'] ?></option>
                        <?php }} ?>
                    </select>
                    <?Php if(isset($_GET['subject_id'])) { ?>
                    <select name="pilih_bab" class="filtermateri form-select form-control shadow-none" data-change="bab">
                        <option value="all">Semua BAB</option>
                        <?Php for($xx = 1; $xx <= 20; $xx ++) {echo "<option value='".$xx."'>BAB ".$xx."</option>";} ?>
                    </select>
                    <?Php } ?>
                    <script>
                        $("select.filtermateri").on("change", function(){
                            var chang = $(this).data("change");
                            var mapel = $("select[name='pilih_mapel']").val();
                            var bab   = $("select[name='pilih_bab']").val();
                            
                            $(".video").hide();
                            if (chang == "mapel") {
                                $("select[name='pilih_bab']").val("all");
                                if(mapel == "all") {
                                    $(`.video`).show();
                                    location.href = "?";
                                } else {
                                    // $(`.video[data-mapel=${mapel}]`).show();
                                    location.href = "?subject_id=" + mapel;
                                }
                            } else if (chang == "bab") {
                                if (bab == "all") {
                                    if(mapel == "all") {
                                        $(".video").show();
                                    } else {
                                        $(`.video[data-mapel=${mapel}]`).show();
                                    }
                                } else {
                                    if(mapel == "all") {
                                        $(`.video[data-bab=${bab}]`).show();
                                    } else {
                                        $(`.video[data-mapel=${mapel}][data-bab=${bab}]`).show();
                                    }
                                }
                            }
                        });
                    </script>
                </div>
            </div>
            <style>
                .clickable {
                    cursor: pointer;
                    border-bottom: 1px dashed #efefef;
                }

                .pagination button {
                    border: 1px solid #EDEDED;
                    background: #FFF;
                    padding: 5px 10px;
                    border-radius: 4px;
                } .pagination button.active {
                    border: 1px solid var(--blue);
                }

                @media (max-width: 768px) {
                    .pagination {overflow-x: scroll;}
                }
            </style>
            <div class="grid grid-4" style="margin: 20px 0px 5px;">
            <?Php
                $LIMIT = 8;
                if(isset($_GET['subject_id'])) {
                    if(isset($video['mapel'][$_GET['subject_id']])) {
                        $OBX = $video['mapel'][$_GET['subject_id']];
                        $XLS = implode(",", $OBX);
                        $GET = query("video_d","WHERE video_id IN ($XLS) ORDER BY id DESC");
                        $GND = query("video_d","WHERE video_id IN ($XLS) ORDER BY id ASC LIMIT 1");
                    }
                } else {
                    if(isset($_GET['page']) && $_GET['page'] > 1) {
                        $P = $_GET['page']; $PX = ($P * $LIMIT) - $LIMIT;
                        $GET = query("video_d","GROUP BY video_id ORDER BY id DESC LIMIT $PX, $LIMIT");
                        $GND = query("video_d","GROUP BY video_id ORDER BY id ASC LIMIT 1");
                    } else {
                        $GET = query("video_d","GROUP BY video_id ORDER BY id DESC LIMIT $LIMIT");
                        $GND = query("video_d","GROUP BY video_id ORDER BY id ASC LIMIT 1");
                    }   
                }

                $last = false;
                $end = mysqli_fetch_array($GND);
                $eid = $end['ID'];
                
                if(isset($GET)) {
                    $DX = []; $HITUNG = 0;
                    while($dtx = mysqli_fetch_array($GET)) {
                        if (isset($video['data'][$dtx['video_id']]) && !in_array($dtx['video_id'], $DX)) {
                            $DX[] = $dtx['video_id'];
                            $data = $video['data'][$dtx['video_id']];
                            if (in_array($data['subject_id'], $siswa[$user['username']]['ekskul']) || in_array($myclass, $data['classes'])) { ?>
                        <div class="video card" data-mapel="<?= $data['subject_id'] ?>" data-bab="<?= $data['bab'] ?>">
                            <div class="header">
                                <h5 class="card-title"><?= $data['title'] ?></h5>
                            </div>
                            <div class="content">
                                <div class="videoWrapper">
                                    <iframe class="embed-responsive-item" src="<?= $data['url'] ?>" allowfullscreen=""></iframe>
                                </div>
                                <hr/>
                                <div>
                                    <p class="text-muted"><b>Kategori : </b><span class="clickable sortByCat"><?= ucwords($mapel['detail'][$data['subject_id']]['tipe']) ?></span> / <span class="clickable sortBySubject"><?= ucwords($mapel['detail'][$data['subject_id']]['title']) ?></span><br/><b>Uploader :</b> <?= $guru[$data['uploader']]['name'] ?></p>
                                </div>
                            </div>
                        </div> <?php
                            $HITUNG++; }

                            if ($dtx['ID'] == $eid) {$last = true;}
                        }
                    }
                } else {
                    echo "- Tidak ditemukan apapun -";
                }
            ?>
            </div>
            <div class="d-flex" style="justify-content: space-between;">
                <div>
                    <?php if(isset($_GET['page']) && $_GET['page'] > 1) { $px = $_GET['page'] > 1 ? $_GET['page'] - 1 : $_GET['page'] + 1; ?>
                        <a href="?page=<?= $px ?>" class="btn btn-sm btn-primary" type="button">< Prev</a>
                    <?php } ?>
                </div>
                <div>
                    <?php if(isset($data) && $HITUNG == $LIMIT && $last == false) { $px =  isset($_GET['page']) ? ($_GET['page'] + 1) : 2;?>
                        <a href="?page=<?= $px ?>" class="btn btn-sm btn-primary" type="button">Next ></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>