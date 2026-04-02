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

                .pagination .page-link {
                    color: #ff8a2a;
                }

                .video-pagination-wrapper {
                    display: flex;
                    justify-content: center;
                    margin-top: -0.25rem;
                    margin-bottom: 2rem;
                }

                .video-pagination-wrapper .pagination {
                    margin-bottom: 0;
                    gap: 8px;
                    padding: 10px 12px;
                    border-radius: 12px;
                    background: #fff;
                    border: 1px solid #e8edf5;
                    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
                    flex-wrap: wrap;
                    justify-content: center;
                }

                .video-pagination-wrapper .page-item.disabled .page-link {
                    color: #9aa0ac;
                    pointer-events: none;
                    background-color: #f8fafc;
                    border-color: #edf2f7;
                }

                .video-pagination-wrapper .page-item {
                    margin: 0;
                }

                .video-pagination-wrapper .page-item + .page-item .page-link {
                    margin-left: 0;
                }

                .video-pagination-wrapper .page-link {
                    min-width: 40px;
                    height: 40px;
                    padding: 0 12px;
                    margin: 0 6px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 10px !important;
                    border: 1px solid #dfe7f3;
                    color: #f47b20;
                    font-weight: 600;
                    transition: all .2s ease-in-out;
                }

                .video-pagination-wrapper .page-link:hover {
                    color: #d36512;
                    border-color: #f47b20;
                    background: #fff2e8;
                    text-decoration: none;
                }

                .video-pagination-wrapper .page-item.active .page-link {
                    color: #fff;
                    background: linear-gradient(180deg, #ffa64d 0%, #f47b20 100%);
                    border-color: #f47b20;
                    box-shadow: 0 6px 14px rgba(244, 123, 32, 0.30);
                }

                @media (max-width: 768px) {
                    .pagination {overflow-x: auto;}
                    .video-pagination-wrapper .pagination {
                        gap: 6px;
                        padding: 8px;
                    }

                    .video-pagination-wrapper .page-link {
                        min-width: 34px;
                        height: 34px;
                        padding: 0 10px;
                        margin: 0 4px;
                        font-size: 14px;
                        border-radius: 8px !important;
                    }
                }
            </style>
            <div class="grid grid-4" style="margin: 20px 0px 5px;">
            <?Php
                $LIMIT = 8;
                $page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
                $subjectId = isset($_GET['subject_id']) ? (int)$_GET['subject_id'] : null;
                $videoIds = [];
                $eligibleVideoIds = [];
                $HITUNG = 0;
                $totalPages = 1;
                $basePaginationUrl = "?";
                if ($subjectId !== null && $subjectId > 0) {
                    $basePaginationUrl = "?subject_id=".$subjectId;
                }
                $paginationSeparator = $subjectId !== null && $subjectId > 0 ? "&" : "";

                if ($subjectId !== null && $subjectId > 0) {
                    if (isset($video['mapel'][$subjectId])) {
                        $videoIds = $video['mapel'][$subjectId];
                    } else {
                        $videoIds = [];
                    }
                } else {
                    $GET = query("video", "ORDER BY ID DESC");
                    if ($GET) {
                        while($dtx = mysqli_fetch_array($GET)) {
                            if (!in_array($dtx['ID'], $videoIds)) {
                                $videoIds[] = $dtx['ID'];
                            }
                        }
                    }
                }

                foreach ($videoIds as $videoId) {
                    if (isset($video['data'][$videoId])) {
                        $videoData = $video['data'][$videoId];
                        if (in_array($videoData['subject_id'], $siswa[$user['username']]['ekskul']) || in_array($myclass, $videoData['classes'])) {
                            $eligibleVideoIds[] = $videoId;
                        }
                    }
                }

                $totalData = count($eligibleVideoIds);
                if ($totalData > 0) {
                    $totalPages = ceil($totalData / $LIMIT);
                }
                if ($page > $totalPages) {
                    $page = $totalPages;
                }
                if ($page < 1) {
                    $page = 1;
                }

                $offset = ($page - 1) * $LIMIT;
                $paginatedVideoIds = array_slice($eligibleVideoIds, $offset, $LIMIT);

                if(count($paginatedVideoIds) > 0) {
                    foreach($paginatedVideoIds as $videoId) {
                        $data = $video['data'][$videoId]; ?>
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
                        $HITUNG++;
                    }
                } else {
                    echo "- Tidak ditemukan apapun -";
                }
            ?>
            </div>
            <?php if($totalPages > 1) {
                $visiblePages = [];
                $visiblePages[] = 1;
                if($totalPages > 1) {
                    $visiblePages[] = $totalPages;
                }
                for($i = $page - 1; $i <= $page + 1; $i++) {
                    if($i > 1 && $i < $totalPages) {
                        $visiblePages[] = $i;
                    }
                }
                $visiblePages = array_unique($visiblePages);
                sort($visiblePages);
            ?>
            <div class="video-pagination-wrapper">
                <nav aria-label="Pagination video interaktif">
                    <ul class="pagination">
                        <?php if($page > 1) { $prev = $page - 1; ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $basePaginationUrl.$paginationSeparator ?>page=<?= $prev ?>" aria-label="Halaman sebelumnya">&laquo;</a>
                            </li>
                        <?php } else { ?>
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&laquo;</span>
                            </li>
                        <?php } ?>

                        <?php
                            $lastShownPage = 0;
                            foreach($visiblePages as $i) {
                                if($lastShownPage > 0 && ($i - $lastShownPage) > 1) { ?>
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                <?php } ?>
                            <li class="page-item <?= $i == $page ? "active" : "" ?>">
                                <a class="page-link" href="<?= $basePaginationUrl.$paginationSeparator ?>page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php
                                $lastShownPage = $i;
                            }
                        ?>

                        <?php if($page < $totalPages) { $next = $page + 1; ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $basePaginationUrl.$paginationSeparator ?>page=<?= $next ?>" aria-label="Halaman berikutnya">&raquo;</a>
                            </li>
                        <?php } else { ?>
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&raquo;</span>
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
