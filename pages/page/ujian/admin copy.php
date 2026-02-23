<div class="container-fluid">
    <div class="row">
        <?php if (!isset($_GET['action'])) { ?>
            <div class="col-md-12">
                <div class="card">
                    <div class="header d-flex space-between">
                        <h4 class="title">Manajemen Ujian</h4>
                        <a href="<?= base_url("soal") ?>" class="btn btn-sm btn-success btn-fill">S O A L</a>
                    </div>
                    <style>
                        span.published {
                            color: var(--green);
                        }

                        span.waiting {
                            color: var(--red);
                        }
                    </style>
                    <<?php
                        $limit = 10; // Jumlah item per halaman
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $offset = ($page - 1) * $limit;

                        // Hitung total data
                        $total_query = query("examl", "ORDER BY ID DESC");
                        $total_rows = mysqli_num_rows($total_query);
                        $total_pages = ceil($total_rows / $limit);

                        // Ambil data dengan pagination
                        $get = query("examl", "ORDER BY ID DESC LIMIT $limit OFFSET $offset");
                        ?>

                        <div class="content">
                        <div class="tableHolder">
                            <table class="table table-hover table-striped mt-3 action">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th></th>
                                        <th>Judul Ujian</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Kelas</th>
                                        <th>Uploader</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = $offset + 1;
                                    while ($data = mysqli_fetch_array($get)) {
                                        $maple = $mapel['detail'][$data['subject_id']]['title'] ?? '';
                                    ?>
                                        <tr>
                                            <td><?= $no ?></td>
                                            <td>
                                                <span style="padding: 2px 5px;border: 1px solid #DFDFDF;color: #777;border-radius: 3px;">
                                                    <span class="<?= $data['publish_status'] == "1" ? "published" : "waiting" ?>"><i class="fa fa-circle"></i></span>
                                                    <?= $data['publish_status'] == "1" ? "Live" : "Waiting" ?>
                                                </span>
                                            </td>
                                            <td><?= base64_decode($data['title']) ?></td>
                                            <td><?= $mapel['detail'][$data['subject_id']]['title'] ?></td>
                                            <td><?php foreach ($exam[$data['exam_id']]['classes'] as $clx) {
                                                    $clxx[$data['ID']][] = $class['info'][$clx];
                                                }
                                                echo implode(", ", $clxx[$data['ID']]) ?></td>
                                            <td><?= $guru[$data['uploader']]['name'] ?></td>
                                            <td><a href="<?= base_url("ujian?action=lookup&ID=" . $data['ID']) ?>" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a></td>
                                        </tr>
                                    <?php
                                        $no++;
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <!-- Pagination Controls -->
                            <nav>
                                <ul class="pagination">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a></li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($page < $total_pages): ?>
                                        <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next</a></li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                </div>
            </div>
    </div>
    <?Php } else {
            $act = $_GET['action'];
            if ($act == "edit" && isset($_GET['hash'])) {
                $hash = $_GET['hash'];
                $GET = select("examl", "exam_id='$hash' AND uploader='$unme'");
                if (mysqli_num_rows($GET) == 1) {
                    $data = mysqli_fetch_array($GET); ?>
        <?Php }
            } elseif ($act == "lookup" && isset($_GET['ID'])) {
                $ID  = $_GET['ID'];
                $GET = select("examl", "ID='$ID'");
                if (mysqli_num_rows($GET) == 1) {
                    $data = mysqli_fetch_array($GET); ?>
            <div class="col-md-6">
                <div class="card">
                    <div class="header d-flex space-between">
                        <h4 class="title">Detail Ujian</h4>
                        <div style="display: flex;gap: 10px;">
                            <a href="<?= base_url("ujian?action=results&hash=" . $data['exam_id']) ?>" class='btn btn-sm btn-fill btn-warning'><i class="fa fa-eye"></i> Lihat Hasil</a>
                            <a href="<?= base_url("ujian") ?>" class='btn btn-sm btn-fill btn-info'><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                    <style>
                        .classes {
                            display: inline-block;
                            padding: 3px 16px;
                            border: 1px solid #DFDFDF;
                            border-radius: 4px;
                        }
                    </style>
                    <div class="content">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td>Judul Tugas</td>
                                    <td><?= base64_decode($data['title']) ?></td>
                                </tr>
                                <tr>
                                    <td>Mata Pelajaran</td>
                                    <td><?= $mapel['detail'][$data['subject_id']]['title'] ?? "ERROR ! SUBJECT NOT FOUND" ?></td>
                                </tr>
                                <tr>
                                    <td>Durasi</td>
                                    <td><?= $data['duration'] ?> Menit</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="card" style="padding: 24px 16px;">
                            <h4 style="margin-top: 0px;font-weight: bold;">Deskripsi</h4>
                            <p style=""><?= nl2br(base64_decode($data['description'])) ?></p>
                        </div>
                        <div style="border: 1px solid #DFDFDF;padding: 16px;">
                            <h4 style="margin: 0px;">Kelas yang ditugaskan</h4>
                            <div class="mt-3">
                                <?Php for ($c = 0; $c <= count($exam[$data['exam_id']]['classes']) - 1; $c++) { ?><div class="classes"><?= $class['info'][$exam[$data['exam_id']]['classes'][$c]] ?></div> <?Php } ?>
                            </div>
                        </div>
                        <div class="mt-3 d-flex space-between">
                            <a href="<?= base_url("ujian?action=edit&hash=" . $data['exam_id']); ?>" class="btn btn-sm btn-warning btn-fill"><i class="fa fa-edit"></i></a>
                            <div>
                                <?= $data['pilgan'] != "" ? '<a href="' . base_url('soal?action=edit&hash=' . $data['pilgan']) . '" class="btn btn-sm btn-success btn-fill"><i class="fa fa-search"></i> Pilihan Ganda</a>' : '' ?>
                                <?= $data['essay'] != "" ? '<a href="' . base_url('soal?action=edit&hash=' . $data['essay']) . '" class="btn btn-sm btn-primary btn-fill"><i class="fa fa-search"></i> Soal Essay</a>' : '' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?Php }
            } elseif ($act == "results" && isset($_GET['hash'])) {
                $exhh = $_GET['hash']; ?>
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h4 class="title">Hasil Ujian</h4>
                </div>
                <div class="content">
                    <table class="table table-striped action">
                        <thead>
                            <th>Nama Siswa</th>
                            <th>Dikumpulkan</th>
                            <th>Nilai</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <?Php
                            if ($exam[$exhh]['uploader'] == $unme) {
                                $GET = query("exam_s", "WHERE exam_id='$exhh' ORDER BY ID ASC");
                                if (mysqli_num_rows($GET) > 0) {
                                    while ($data = mysqli_fetch_array($GET)) { ?>
                                        <tr>
                                            <td><?= $siswa[$data['nis']]['name'] ?> [ <?= $data['nis'] ?> ]</td>
                                            <td><?= date("d M Y - H:i:s", $data['stored']) ?></td>
                                            <td><?= $data['nilai'] ?></td>
                                            <td>
                                                <a href="<?= base_url("ujian?action=record&hash=" . $_GET['hash'] . "&nis=" . $data['nis']) ?>" class="btn btn-sm btn-info">Buka <i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                            <?Php }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?Php } elseif ($act == "record" && isset($_GET['hash']) && isset($_GET['nis'])) {
                $HASH = $_GET['hash'];
                $NIS = $_GET['nis']; ?>
        <?Php
                $soalPB = $exam[$HASH]['ppctg'];
                $soalEB = $exam[$HASH]['epctg'];
                $soalP = 0;
                $soalE = 0;
                $csoalP = 0;
                $csoalE = 0;
                $GEET = query("examrec", "WHERE exam_id='$HASH' AND nis='$NIS'");
                while ($DATA = mysqli_fetch_array($GEET)) {
                    $DATA['qtype'] == "pilgan" ? $soalP++ : ($DATA['qtype'] == "essay" ? $soalE++ : false);
                    $DATA['cstatus'] == "1" && $DATA['evaluation'] == "1" ? ($DATA['qtype'] == "pilgan" ? $csoalP++ : ($DATA['qtype'] == "essay" ? $csoalE++ : false)) : false;
                }

                $nilai = (($csoalP / $soalP) * $soalPB) + (($csoalE / $soalE) * $soalEB);
                update("exam_s", "nilai='$nilai'", "exam_id='$HASH' AND nis='$NIS'");
        ?>
        <div class="col-md-8">
            <div class="card">
                <div class="header">
                    <h4 class="title">Riwayat Isian Ujian</h4>
                </div>
                <style>
                    .d-block {
                        display: block;
                    }

                    #primaryData {
                        font-size: 23px;
                    }
                </style>
                <div class="content">
                    <div id="primaryData">
                        <span class="d-block"><b>Nama Siswa</b> : <?= $siswa[$_GET['nis']]['name'] ?></span>
                        <span class="d-block"><b>Kelas</b> : <?= $class['detail'][$siswa[$_GET['nis']]['class']] ?></span>
                        <span class="d-block"><b>Nilai</b> : <span id="Nilai"><?= $nilai ?></span></span>
                    </div>
                    <style>
                        .soal {
                            padding: 16px;
                            border: 1px solid #DFDFDF;
                            margin-top: 16px;
                        }

                        .soal.correct {
                            background: #f2fff8;
                        }

                        .soal.wrong {
                            background: #fff2f2;
                        }

                        .soal span {
                            margin-left: 5px;
                        }

                        .soal span.right {
                            color: blue;
                            font-weight: bold;
                        }
                    </style>
                    <div id="pilgan">
                        <div class="d-flex space-between">
                            <h4>Pilgan</h4>
                            <h4><?= $exam[$HASH]['ppctg'] ?>%</h4>
                        </div>
                        <?Php $GET = query("examrec", "WHERE qtype='pilgan' AND exam_id='$HASH' AND nis='$NIS'");
                        $no = 1;
                        while ($data = mysqli_fetch_array($GET)) { ?>
                            <div class="pilgan soal <?= $data['answer'] == $exac[$data['question_id']]['true'] ? "correct" : "wrong" ?>">
                                <p class="question"><?= base64_decode($exac[$data['question_id']]['question']) ?></p>
                                <?Php foreach ($exac[$data['question_id']]['option'] as $key => $val) { ?>
                                    <div><input type='radio' <?= $data['answer'] == $key ? "checked" : "disabled" ?>> <span class="<?= $exac[$data['question_id']]['true'] == $key ? "right" : "wrong" ?>"><?= base64_decode($val) ?></span></div>
                                <?Php } ?>
                            </div>
                        <?Php $no++;
                        } ?>
                    </div>
                    <div id="essays">
                        <div class="d-flex space-between">
                            <h4>Essay</h4>
                            <h4><?= $exam[$HASH]['epctg'] ?>%</h4>
                        </div>
                        <?Php $GET = query("examrec", "WHERE qtype='essay' AND exam_id='$HASH' AND nis='$NIS'");
                        while ($data = mysqli_fetch_array($GET)) { ?>
                            <div class="essay soal <?= $data['cstatus'] == "1" ? ($data['evaluation'] == 1 ? "correct" : "wrong") : "" ?>" data-question-id="<?= $data['question_id'] ?>">
                                <p class="question"><?= base64_decode($exae[$data['question_id']]['question']) ?></p>
                                <textarea class="form-control" readonly><?= $data['answer'] ?></textarea>
                                <div><button class="mt-3 openControl btn btn-sm <?= $data['cstatus'] == "1" ? "done" : "" ?>" data-question-id="<?= $data['question_id'] ?>">UBAH</button></div>
                                <div class="evaluation mt-3 <?= $data['cstatus'] == "1" ? "done" : "" ?>">
                                    <input type="button" class="correctW btn btn-sm btn-danger btn-fill" data-val="0" data-question-id="<?= $data['question_id'] ?>" value="Salah">
                                    <input type="button" class="correctC btn btn-sm btn-success btn-fill" data-val="1" data-question-id="<?= $data['question_id'] ?>" value="Benar">
                                </div>
                            </div>
                        <?Php $no++;
                        } ?>
                    </div>
                    <script>
                        $(".openControl:not(.done)").hide();
                        $(".evaluation.done").hide();

                        var XID = "<?= $_GET['hash'] ?>";
                        var NIS = "<?= $_GET['nis'] ?>";

                        var PPCTG = "<?= $exam[$HASH]['ppctg'] ?>",
                            EPCTG = "<?= $exam[$HASH]['epctg'] ?>";

                        function update() {
                            var Pilgn = $(".pilgan").length;
                            var PilgnRight = $(".pilgan.correct").length;
                            $("#pilganHolder").html(PilgnRight + " / " + Pilgn + " - " + parseInt((PilgnRight / Pilgn) * 100) + "%")

                            var Essay = $(".essay").length;
                            var EssayRight = $(".essay.correct").length;
                            $("#essayHolder").html(EssayRight + " / " + Essay + " - " + parseInt((EssayRight / Essay) * 100) + "%");

                            var Total = Pilgn + Essay;
                            var TotalRight = PilgnRight + EssayRight;

                            var Nilai = parseInt((TotalRight / Total) * 100);

                            $.ajax({
                                url: "<?= base_url("endpoint/index.php") ?>",
                                type: "POST",
                                data: {
                                    "action": "process/ujian/res",
                                    "NIS": NIS,
                                    "XID": XID,
                                    "VAL": Nilai
                                },
                                success: function(data) {
                                    var response = JSON.parse(data);
                                    console.log(data);
                                    if (response.OK == true) {
                                        $("span#Nilai").html(Nilai);
                                    }
                                },
                                error: function(data) {}
                            });
                        }

                        // update();

                        $(".openControl").on("click", function() {
                            var QID = $(this).data("question-id");
                            $(`div.soal[data-question-id=${QID}] .evaluation`).show();
                            $(this).hide();
                        });

                        $(".correctW, .correctC").on("click", function() {
                            var QID = $(this).data("question-id");
                            var VAL = $(this).data("val");

                            $.ajax({
                                url: "<?= base_url("endpoint/index.php") ?>",
                                type: "POST",
                                data: {
                                    "action": "process/ujian/evaluation",
                                    "NIS": NIS,
                                    "XID": XID,
                                    "QID": QID,
                                    "VAL": VAL
                                },
                                success: function(data) {
                                    var response = JSON.parse(data);
                                    console.log(data);
                                    if (response.OK == true) {
                                        $(`div.soal[data-question-id=${QID}] .evaluation`).hide();
                                        $(`div.soal[data-question-id=${QID}] .openControl`).show();

                                        if (VAL == "0") {
                                            $(`div.soal[data-question-id=${QID}]`).removeClass("correct").addClass("wrong");
                                        } else {
                                            $(`div.soal[data-question-id=${QID}]`).removeClass("wrong").addClass("correct");
                                        }

                                        // update();
                                    }
                                },
                                error: function(data) {}
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
<?Php }
        } ?>
</div>
</div>