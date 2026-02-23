<div class="container-fluid">
    <div class="row">
        <?php if (!isset($_GET['action'])) { ?>
            <div class="col-md-12">
                <div class="card">
                    <div class="header d-flex space-between">
                        <h4 class="title">Manajemen Ujian</h4>
                        <a href="<?= base_url("ujian?action=add") ?>" class='btn btn-sm btn-fill btn-info'>+ Tambah</a>
                    </div>
                    <style>
                        span.published {
                            color: var(--green);
                        }

                        span.waiting {
                            color: var(--red);
                        }
                    </style>

                    <!-- DEE CHANGES -->
                    <?php
                    $limit = 10; // Jumlah data per halaman
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;

                    // Hitung total data
                    $totalQuery = query("examl", "WHERE uploader='$unme'");
                    $totalData = mysqli_num_rows($totalQuery);
                    $totalPages = ceil($totalData / $limit);

                    // Ambil data dengan limit dan offset
                    $get = query("examl", "WHERE uploader='$unme' ORDER BY ID DESC LIMIT $limit OFFSET $offset");
                    $no = $offset + 1;
                    ?>

                    <div class="content">
                        <a href="<?= base_url("soal") ?>" class="btn btn-sm btn-success btn-fill">Manajemen Soal</a>
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
                                    <?php while ($data = mysqli_fetch_array($get)) {
                                        $maple = $mapel['detail'][$data['subject_id']]['title'] ?? '';
                                    ?>
                                        <tr>
                                            <td><?= $no ?></td>
                                            <td>
                                                <span style="padding: 2px 5px; border: 1px solid #DFDFDF; color: #777; border-radius: 3px;">
                                                    <span class="<?= $data['publish_status'] == "1" ? "published" : "waiting" ?>">
                                                        <i class="fa fa-circle"></i>
                                                    </span>
                                                    <?= $data['publish_status'] == "1" ? "Live" : "Waiting" ?>
                                                </span>
                                            </td>
                                            <td><?= base64_decode($data['title']) ?></td>
                                            <td><?= $mapel['detail'][$data['subject_id']]['title'] ?></td>
                                            <td><?php foreach ($exam[$data['exam_id']]['classes'] as $clx) {
                                                    $clxx[$data['ID']][] = $class['info'][$clx];
                                                }
                                                echo implode(", ", $clxx[$data['ID']]); ?></td>
                                            <td><?= $guru[$data['uploader']]['name'] ?></td>
                                            <td><a href="<?= base_url("ujian?action=lookup&ID=" . $data['ID']) ?>" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a></td>
                                        </tr>
                                    <?php $no++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <nav>
                            <ul class="pagination">
                                <?php if ($page > 1) : ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a></li>
                                <?php endif; ?>
                                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>"> <?= $i ?> </a>
                                    </li>
                                <?php endfor; ?>
                                <?php if ($page < $totalPages) : ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next</a></li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>


                    <!-- END DEE CHANGES -->
                </div>
            </div>
            <?Php } else {
            $act = $_GET['action'];
            if ($act == "add") { ?>
                <div class="col-md-6">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Tambah Ujian</h4>
                        </div>
                        <div class="content">
                            <form>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label>Judul ujian</label>
                                        <input type="text" class="form-control" name="title" placeholder="Judul Ujian" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label>Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control" rows="7"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label>Mata Pelajaran</label>
                                        <select name="subject" class="form-control">
                                            <option value="">- Pilih -</option>
                                            <?Php for ($i = 0; $i <= count($guru[$unme]['subject']) - 1; $i++) { ?>
                                                <option value="<?= $guru[$unme]['subject'][$i] ?>"><?= $mapel['detail'][$guru[$unme]['subject'][$i]]['title'] ?> <?= $mapel['detail'][$guru[$unme]['subject'][$i]]['tipe'] == "ekskul" ? "( Ekskul )" : "" ?></option>
                                            <?Php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label>Durasi ( MENIT )</label>
                                        <input type="text" name="duration" class="form-control" pattern="\d*" maxlength="3">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Password</label>
                                        <input type="text" name="password" class="form-control" placeholder="Password" />
                                        <p class="text-muted" style="margin: 10px 0px 0px;font-size: 12px;">* Catat password ini, PENTING!</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9 form-group">
                                        <label>Paket Pilihan Ganda</label>
                                        <select name="pilgand" class="form-control" disabled>
                                            <option value="">- Pilih -</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group" style="display: none;">
                                        <label>Persentase</label>
                                        <input type="number" class="form-control" name="ppctg" value="25" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9 form-group">
                                        <label>Paket Essay</label>
                                        <select name="essay" class="form-control" disabled>
                                            <option value="">- Pilih -</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group" style="display: none;">
                                        <label>Persentase</label>
                                        <input type="number" class="form-control" name="epctg" value="25">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9 form-group">
                                        <label>Paket Multiple Choice</label>
                                        <select name="multipleChoice" class="form-control" disabled>
                                            <option value="">- Pilih -</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group" style="display: none;">
                                        <label>Persentase</label>
                                        <input type="number" class="form-control" name="mpctg" value="25">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9 form-group">
                                        <label>Paket Pencocokan</label>
                                        <select name="match" class="form-control" disabled>
                                            <option value="">- Pilih -</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group" style="display: none;">
                                        <label>Persentase</label>
                                        <input type="number" class="form-control" name="cpctg" value="25">
                                    </div>
                                </div>
                                <div id="pilihkelas">
                                    <h4 style="margin: 0px 0px 15px;">Pilih Kelas</h4>
                                    <div id="classplace" style="display: grid;gap: 15px;">
                                        <select class="form-control" disabled>
                                            <option value="">- Pilih -</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <input type="button" id="addNew" class="pull-right btn btn-success btn-fill" value="SIMPAN" disabled>
                                    </div>
                                </div>
                            </form>
                            <script>
                                $("#addNew").on("click", function() {
                                    var form = $("form")[0],
                                        formData = new FormData(form),
                                        password = $("input[name='password']").val(),
                                        pg = $("input[name='ppctg']").val(),
                                        pe = $("input[name='epctg']").val(),
                                        pm = $("input[name='mpctg']").val(),
                                        pc = $("input[name='cpctg']").val(),
                                        // cc = parseInt(pg) + parseInt(pe) + parseInt(pm) + parseInt(pc);
                                        cc = 100;

                                    if (cc == 100) {
                                        formData.append("action", "crud/ujian/create");
                                        formData.append("activity", "add");

                                        $.ajax({
                                            url: "<?= base_url("endpoint/index.php") ?>",
                                            type: "POST",
                                            data: formData,
                                            processData: false,
                                            contentType: false,
                                            success: function(data) {
                                                var response = JSON.parse(data);
                                                console.log(data);
                                                if (response.OK == true) {
                                                    alert("Berhasil menambahkan ujian!");
                                                    window.location.replace("<?= base_url("ujian") ?>");
                                                } else {
                                                    alert("Gagal menambahkan ujian :( Coba lagi..");
                                                }
                                            },
                                            error: function(data) {}
                                        });
                                    } else {
                                        alert("Nilai persentase tidak sesuai (" + cc + " / 100)");
                                    }
                                });
                            </script>
                        </div>
                        <script>
                            $("select[name='subject']").on("change", function() {
                                var val = $(this).val();

                                $("select[name='pilgand'] option[data-f='AJAX'], select[name='essay'] option[data-f='AJAX']").remove();
                                $("select[name='pilgand'], select[name='essay'], #addNew").attr("disabled", "disabled");
                                $("#classplace").html("");
                                $.ajax({
                                    url: "<?= base_url("endpoint/index.php") ?>",
                                    type: "POST",
                                    data: {
                                        "action": "GET/create_ujian",
                                        "mapel": val
                                    },
                                    success: function(data) {
                                        var response = JSON.parse(data);
                                        console.log(data);
                                        if (response.OK == true) {

                                            if (response.result.choice.length > 0) {
                                                $.each(response.result.choice, function(index, item) {
                                                    var appends = item;
                                                    $("select[name='pilgand']").append(appends);
                                                });

                                                $("select[name='pilgand']").removeAttr("disabled");
                                            }

                                            if (response.result.essay.length > 0) {
                                                $.each(response.result.essay, function(index, item) {
                                                    var appends = item;
                                                    $("select[name='essay']").append(appends);
                                                });

                                                $("select[name='essay']").removeAttr("disabled");
                                            }

                                            if (response.result.multiple.length > 0) {
                                                $.each(response.result.multiple, function(index, item) {
                                                    var appends = item;
                                                    $("select[name='multipleChoice']").append(appends);
                                                });

                                                $("select[name='multipleChoice']").removeAttr("disabled");
                                            }

                                            if (response.result.match.length > 0) {
                                                $.each(response.result.match, function(index, item) {
                                                    var appends = item;
                                                    $("select[name='match']").append(appends);
                                                });

                                                $("select[name='match']").removeAttr("disabled");
                                            }

                                            if (response.result.choice.length > 0 && response.result.essay.length > 0) {
                                                if (response.result.classes.length > 0) {
                                                    $.each(response.result.classes, function(index, item) {
                                                        var appends = item;
                                                        $("#classplace").append(appends);
                                                    });
                                                }
                                            } else {
                                                $("#classplace").html('<select class="form-control" disabled><option value="">- Pilih -</option></select>');
                                            }
                                        } else {
                                            $("select[name='mapel']").html("");
                                            $("select[name='mapel']").attr("disabled", true);
                                        }
                                    },
                                    error: function(data) {}
                                });
                            });

                            $(document.body).on("click", ".fgroup", function() {
                                var kelas = $(this).data("kelas");

                                if (!$(this).hasClass("active")) {
                                    $(`input[name='class[]'][value=${kelas}]`).prop("checked", true);
                                    $(this).addClass("active");
                                } else {
                                    $(`input[name='class[]'][value=${kelas}]`).prop("checked", false);
                                    $(this).removeClass("active");
                                }

                                var count = $(".fgroup.active").length;
                                if (count > 0) {
                                    $("#addNew").removeAttr("disabled");
                                } else {
                                    $("#addNew").attr("disabled", true);
                                }
                            });
                        </script>
                    </div>
                </div>
                <?Php } elseif ($act == "edit" && isset($_GET['hash'])) {
                $hash = $_GET['hash'];
                $GET = select("examl", "exam_id='$hash' AND uploader='$unme'");
                if (mysqli_num_rows($GET) == 1) {
                    $data = mysqli_fetch_array($GET); ?>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Update Ujian</h4>
                            </div>
                            <div class="content">
                                <form>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label>Judul ujian</label>
                                            <input type="text" class="form-control" name="title" placeholder="Judul Ujian" value="<?= base64_decode($data['title']) ?>" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label>Status Publikasi</label>
                                            <select class="publishStatus form-control" data-hash="<?= $hash ?>">
                                                <option value="0" <?= $data['publish_status'] == "0" ? "selected" : false ?>>Wait</option>
                                                <option value="1" <?= $data['publish_status'] == "1" ? "selected" : false ?>>Publikasi</option>
                                            </select>
                                        </div>
                                    </div>
                                    <script>
                                        $(".publishStatus").on("change", function() {
                                            var hash = $(this).data("hash");
                                            var status = $(this).val();

                                            if (status == "1") {
                                                var sst = "Aktif";
                                            } else {
                                                var sst = "Tidak Aktif";
                                            }
                                            if (confirm("Apakah Anda yakin ingin merubah status ujian ini menjadi '" + sst + "' ?")) {
                                                $.ajax({
                                                    url: "<?= base_url("endpoint/index.php") ?>",
                                                    type: "POST",
                                                    data: {
                                                        "action": "crud/ujian/exam",
                                                        "activity": "change_status",
                                                        "hash": hash,
                                                        "set_status": status
                                                    },
                                                    success: function(data) {
                                                        var response = JSON.parse(data);
                                                        console.log(data);
                                                        if (response.OK == true) {
                                                            window.location.reload();
                                                        }
                                                    },
                                                    error: function(data) {}
                                                });
                                            }
                                        });
                                    </script>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label>Deskripsi</label>
                                            <textarea name="deskripsi" class="form-control" rows="7"><?= base64_decode($data['description']) ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label>Mata Pelajaran</label>
                                            <select name="subject" class="form-control">
                                                <option value="">- Pilih -</option>
                                                <?Php for ($i = 0; $i <= count($guru[$unme]['subject']) - 1; $i++) {
                                                    if ($mapel['detail'][$guru[$unme]['subject'][$i]]['tipe'] != "ekskul") { ?>
                                                        <option value="<?= $guru[$unme]['subject'][$i] ?>" <?= $data['subject_id'] == $guru[$unme]['subject'][$i] ? "selected" : "" ?>><?= $mapel['detail'][$guru[$unme]['subject'][$i]]['title'] ?> <?= $mapel['detail'][$guru[$unme]['subject'][$i]]['tipe'] == "ekskul" ? "( Ekskul )" : "" ?></option>
                                                <?Php }
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 form-group">
                                            <label>Durasi ( MENIT )</label>
                                            <input type="text" name="duration" class="form-control" pattern="\d*" maxlength="3" value="<?= $data['duration'] ?>" />
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Password</label>
                                            <input type="text" name="password" class="form-control" placeholder="Password" />
                                            <p class="text-muted" style="margin: 10px 0px 0px;font-size: 12px;">* Catat password ini, PENTING!</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-9 form-group">
                                            <label>Paket Pilihan Ganda</label>
                                            <input type="text" class="form-control" value="<?= $soal[$data['pilgan']]['title'] ?? "- tidak ada -" ?>" disabled />
                                        </div>
                                        <div class="col-md-3 form-group" style="display: none;">
                                            <label>Persentase</label>
                                            <input type="number" class="form-control" name="ppctg" value="<?= $data['percent_p'] ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-9 form-group">
                                            <label>Paket Essay</label>
                                            <input type="text" class="form-control" value="<?= $soal[$data['essay']]['title'] ?? "- tidak ada -" ?>" disabled />
                                        </div>
                                        <div class="col-md-3 form-group" style="display: none;">
                                            <label>Persentase</label>
                                            <input type="number" class="form-control" name="epctg" value="<?= $data['percent_e'] ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-9 form-group">
                                            <label>Paket Multiple Choice</label>
                                            <input type="text" class="form-control" value="<?= $soal[$data['multiple']]['title'] ?? "- tidak ada -" ?>" disabled />
                                        </div>
                                        <div class="col-md-3 form-group" style="display: none;">
                                            <label>Persentase</label>
                                            <input type="number" class="form-control" name="mpctg" value="<?= $data['percent_m'] ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-9 form-group">
                                            <label>Paket Pencocokan</label>
                                            <input type="text" class="form-control" value="<?= $soal[$data['cocok']]['title'] ?? "- tidak ada -" ?>" disabled />
                                        </div>
                                        <div class="col-md-3 form-group" style="display: none;">
                                            <label>Persentase</label>
                                            <input type="number" class="form-control" name="cpctg" value="<?= $data['percent_c'] ?>">
                                        </div>
                                    </div>
                                    <div id="pilihkelas">
                                        <h4 style="margin: 0px 0px 15px;">Pilih Kelas</h4>
                                        <div id="classplace" style="display: grid;gap: 15px;">
                                            <?Php foreach ($guru[$unme]['sclass'][$data['subject_id']] as $dataxx) { ?>
                                                <div class="fgroup <?= in_array($dataxx, $exam[$data['exam_id']]['classes'])  ? 'active' : '' ?>" data-kelas="<?= $dataxx ?>"><input type="checkbox" name="class[]" value="<?= $dataxx ?>" <?= in_array($dataxx, $exam[$data['exam_id']]['classes'])  ? 'checked' : '' ?>> <span><?= $class['info'][$dataxx] ?></span></div>
                                            <?Php } ?>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <input type="button" class="btn btn-danger" onclick="deleteExam()" value="Delete" />
                                            <input type="button" id="addNew" class="pull-right btn btn-success btn-fill" value="SIMPAN" />
                                        </div>
                                    </div>
                                </form>
                                <script>
                                    function deleteExam() {
                                        var convirm = prompt("Ketik 'confirm' untuk melanjutkan tindakan ini");
                                        if (convirm == "confirm") {
                                            $.ajax({
                                                url: "<?= base_url("endpoint/index.php") ?>",
                                                type: "POST",
                                                data: {
                                                    "action": "crud/ujian/exam",
                                                    "activity": "delete",
                                                    "hash": "<?= $_GET['hash'] ?>"
                                                },
                                                success: function(data) {
                                                    var response = JSON.parse(data);
                                                    console.log(data);
                                                    if (response.OK == true) {
                                                        alert("Berhasil menghapus ujian");
                                                        window.location.replace("<?= base_url("ujian") ?>");
                                                    }
                                                },
                                                error: function(data) {}
                                            });
                                        }
                                    }

                                    $("#addNew").on("click", function() {
                                        var form = $("form")[0],
                                            formData = new FormData(form),
                                            pg = $("input[name='ppctg']").val(),
                                            pe = $("input[name='epctg']").val(),
                                            pm = $("input[name='mpctg']").val(),
                                            pc = $("input[name='cpctg']").val(),
                                            cc = parseInt(pg) + parseInt(pe) + parseInt(pm) + parseInt(pc);

                                        if (cc == 100) {
                                            formData.append("action", "crud/ujian/create");
                                            formData.append("activity", "update");
                                            formData.append("hash", "<?= $hash ?>");

                                            $.ajax({
                                                url: "<?= base_url("endpoint/index.php") ?>",
                                                type: "POST",
                                                data: formData,
                                                processData: false,
                                                contentType: false,
                                                success: function(data) {
                                                    var response = JSON.parse(data);
                                                    console.log(data);
                                                    if (response.OK == true) {
                                                        alert("Berhasil memperbarui informasi");
                                                    }
                                                },
                                                error: function(data) {}
                                            });
                                        } else {
                                            alert("Nilai persentase tidak sesuai (" + cc + " / 100)");
                                        }
                                    });
                                </script>
                            </div>
                            <script>
                                $("select[name='subject']").on("change", function() {
                                    var val = $(this).val();

                                    $("select[name='pilgand'] option[data-f='AJAX'], select[name='essay'] option[data-f='AJAX']").remove();
                                    $("select[name='pilgand'], select[name='essay'], #addNew").attr("disabled", "disabled");
                                    $("#classplace").html("");
                                    $.ajax({
                                        url: "<?= base_url("endpoint/index.php") ?>",
                                        type: "POST",
                                        data: {
                                            "action": "GET/create_ujian",
                                            "mapel": val
                                        },
                                        success: function(data) {
                                            var response = JSON.parse(data);
                                            console.log(data);
                                            if (response.OK == true) {

                                                if (response.result.choice.length > 0) {
                                                    $.each(response.result.choice, function(index, item) {
                                                        var appends = item;
                                                        $("select[name='pilgand']").append(appends);
                                                    });

                                                    $("select[name='pilgand']").removeAttr("disabled");
                                                }

                                                if (response.result.essay.length > 0) {
                                                    $.each(response.result.essay, function(index, item) {
                                                        var appends = item;
                                                        $("select[name='essay']").append(appends);
                                                    });

                                                    $("select[name='essay']").removeAttr("disabled");
                                                }

                                                if (response.result.choice.length > 0 && response.result.essay.length > 0) {
                                                    if (response.result.classes.length > 0) {
                                                        $.each(response.result.classes, function(index, item) {
                                                            var appends = item;
                                                            $("#classplace").append(appends);
                                                        });
                                                    }
                                                } else {
                                                    $("#classplace").html('<select class="form-control" disabled><option value="">- Pilih -</option></select>');
                                                }
                                            } else {
                                                $("select[name='mapel']").html("");
                                                $("select[name='mapel']").attr("disabled", true);
                                            }
                                        },
                                        error: function(data) {}
                                    });
                                });

                                $(document.body).on("click", ".fgroup", function() {
                                    var kelas = $(this).data("kelas");

                                    if (!$(this).hasClass("active")) {
                                        $(`input[name='class[]'][value=${kelas}]`).prop("checked", true);
                                        $(this).addClass("active");
                                    } else {
                                        $(`input[name='class[]'][value=${kelas}]`).prop("checked", false);
                                        $(this).removeClass("active");
                                    }

                                    var count = $(".fgroup.active").length;
                                    if (count > 0) {
                                        $("#addNew").removeAttr("disabled");
                                    } else {
                                        $("#addNew").attr("disabled", true);
                                    }
                                });
                            </script>
                        </div>
                    </div>
                <?Php }
            } elseif ($act == "lookup" && isset($_GET['ID'])) {
                $ID  = $_GET['ID'];
                $GET = select("examl", "ID='$ID' AND uploader='$unme'");
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
                                <style>
                                    .dflx {
                                        display: grid;
                                        gap: 16px;
                                    }

                                    .card {
                                        margin-bottom: 0px !important;
                                    }
                                </style>
                                <div class="dflx">
                                    <div class="card" style="padding: 24px 16px;">
                                        <h4 style="margin-top: 0px;font-weight: bold;">Deskripsi</h4>
                                        <p style=""><?= nl2br(base64_decode($data['description'])) ?></p>
                                    </div>
                                    <div class="card" style="padding: 24px 16px;">
                                        <h4 style="margin-top: 0px;font-weight: bold;">Komposisi</h4>
                                        <div class="row">
                                            <div class="col-md-9 form-group">
                                                <label>Paket Pilihan Ganda</label>
                                                <input type="text" class="form-control" value="<?= $soal[$data['pilgan']]['title'] ?? "- tidak ada -" ?>" disabled />
                                            </div>
                                            <div class="col-md-3 form-group" style="display: none;">
                                                <label>Persentase</label>
                                                <input type="number" class="form-control" name="ppctg" value="<?= $data['percent_p'] ?>" disabled />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-9 form-group">
                                                <label>Paket Essay</label>
                                                <input type="text" class="form-control" value="<?= $soal[$data['essay']]['title'] ?? "- tidak ada -" ?>" disabled />
                                            </div>
                                            <div class="col-md-3 form-group" style="display: none;">
                                                <label>Persentase</label>
                                                <input type="number" class="form-control" name="epctg" value="<?= $data['percent_e'] ?>" disabled />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-9 form-group">
                                                <label>Paket Multiple Choice</label>
                                                <input type="text" class="form-control" value="<?= $soal[$data['multiple']]['title'] ?? "- tidak ada -" ?>" disabled />
                                            </div>
                                            <div class="col-md-3 form-group" style="display: none;">
                                                <label>Persentase</label>
                                                <input type="number" class="form-control" name="mpctg" value="<?= $data['percent_m'] ?>" disabled />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-9 form-group">
                                                <label>Paket Essay</label>
                                                <input type="text" class="form-control" value="<?= $soal[$data['cocok']]['title'] ?? "- tidak ada -" ?>" disabled />
                                            </div>
                                            <div class="col-md-3 form-group" style="display: none;">
                                                <label>Persentase</label>
                                                <input type="number" class="form-control" name="cpctg" value="<?= $data['percent_c'] ?>" disabled />
                                            </div>
                                        </div>
                                    </div>
                                    <div style="border: 1px solid #DFDFDF;padding: 16px;">
                                        <h4 style="margin: 0px;">Kelas yang ditugaskan</h4>
                                        <div class="mt-3">
                                            <?Php for ($c = 0; $c <= count($exam[$data['exam_id']]['classes']) - 1; $c++) { ?><div class="classes"><?= $class['info'][$exam[$data['exam_id']]['classes'][$c]] ?></div> <?Php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 d-flex space-between">
                                    <a href="<?= base_url("ujian?action=edit&hash=" . $data['exam_id']); ?>" class="btn btn-sm btn-warning btn-fill"><i class="fa fa-edit"></i></a>
                                    <!-- <div>
                            <?= $data['pilgan'] != "" ? '<a href="' . base_url('soal?action=edit&hash=' . $data['pilgan']) . '" class="btn btn-sm btn-success btn-fill"><i class="fa fa-search"></i> Pilihan Ganda</a>' : '' ?>
                            <?= $data['essay'] != "" ? '<a href="' . base_url('soal?action=edit&hash=' . $data['essay']) . '" class="btn btn-sm btn-primary btn-fill"><i class="fa fa-search"></i> Soal Essay</a>' : '' ?>
                        </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                <?Php }
            } elseif ($act == "results" && isset($_GET['hash'])) {
                $exhh = $_GET['hash']; ?>
                <div class="col-md-12">
                    <div class="card">
                        <div class="header d-flex" style="justify-content: space-between;">
                            <h4 class="title">Hasil Ujian</h4>
                            <a href="endpoint/process/download_csv.php?hash=<?= $exhh ?>" class="btn btn-success btn-sm">
                                Download Excel <i class="fa fa-download"></i>
                            </a>
                        </div>
                        <div class="content"style="box-sizing: border-box; overflow-x: scroll;">
                            <table class="table table-striped action" >
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
                <?php
                $soalPB = $exam[$HASH]['ppctg']; // Persentase soal pilgan
                $soalEB = $exam[$HASH]['epctg']; // Persentase soal essay
                $totalNilaiValue = 0; // Variabel untuk menyimpan total nilai dari semua tipe soal
                $totalNilaiEvaluation = 0; // Variabel untuk menyimpan total nilai dari semua tipe soal

                // Query untuk mengambil data jawaban siswa
                $GEET = query("examrec", "WHERE exam_id='$HASH' AND nis='$NIS'");
                while ($DATA = mysqli_fetch_array($GEET)) {
                    // Ambil nilai (value) dari setiap tipe soal
                    $totalNilaiValue += (float)$DATA['value']; // Menjumlahkan nilai dari semua tipe soal
                    $totalNilaiEvaluation += (float)$DATA['evaluation']; // Menjumlahkan nilai dari semua tipe soal
                }

                $totalNilai = $totalNilaiValue / $totalNilaiEvaluation * 100;

                // Update nilai total ke tabel exam_s
                update("exam_s", "nilai='$totalNilai'", "exam_id='$HASH' AND nis='$NIS'");
                ?>
                <div class="col-md-8">
                    <div class="card">
                        <div class="header">
                            <div style="width: 100%;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div style="flex: 1;">
                                        <h4 style="margin: 0;">Riwayat Isian Ujian</h4>
                                    </div>
                                    <div style="margin-left: 20px;">
                                        <!-- <button id="addNilai">Berikan Nilai</button>    -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            $("#addNilai").on("click", function($act = "record" && isset($_GET['hash']) && isset($_GET['nis'])) {
                                $HASH = $_GET['hash'];
                                $NIS = $_GET['nis']; ? >
                                <?php
                                $soalPB = $exam[$HASH]['ppctg']; // Persentase soal pilgan
                                $soalEB = $exam[$HASH]['epctg']; // Persentase soal essay
                                $totalNilaiValue = 0; // Variabel untuk menyimpan total nilai dari semua tipe soal
                                $totalNilaiEvaluation = 0; // Variabel untuk menyimpan total nilai dari semua tipe soal

                                // Query untuk mengambil data jawaban siswa
                                $GEET = query("examrec", "WHERE exam_id='$HASH' AND nis='$NIS'");
                                while ($DATA = mysqli_fetch_array($GEET)) {
                                    // Ambil nilai (value) dari setiap tipe soal
                                    $totalNilaiValue += (float)$DATA['value']; // Menjumlahkan nilai dari semua tipe soal
                                    $totalNilaiEvaluation += (float)$DATA['evaluation']; // Menjumlahkan nilai dari semua tipe soal
                                }

                                $totalNilai = round($totalNilaiValue / $totalNilaiEvaluation * 100);

                                // Update nilai total ke tabel exam_s
                                update("exam_s", "cstatus='1'", "exam_id='$HASH' AND nis='$NIS'");
                                ?>
                            });
                            location.reload();
                        </script>
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
                                <span class="d-block"><b>Siswa</b> : <?= $siswa[$_GET['nis']]['name'] ?></span>
                                <span class="d-block"><b>Kelas</b> : <?= $class['detail'][$siswa[$_GET['nis']]['class']] ?></span>
                                <span class="d-block"><b>Nilai</b> : <span id="Nilai"><?= $totalNilai ?></span></span>
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

                                </div>
                                <?Php $GET = query("examrec", "WHERE qtype='pilgan' AND exam_id='$HASH' AND nis='$NIS'");
                                $no = 1;
                                while ($data = mysqli_fetch_array($GET)) { ?>
                                    <div class="pilgan soal">
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

                                </div>
                                <?Php $GET = query("examrec", "WHERE qtype='essay' AND exam_id='$HASH' AND nis='$NIS'");
                                while ($data = mysqli_fetch_array($GET)) { ?>
                                    <div class="essay soal <?= $data['cstatus'] == "1" ? ($data['evaluation'] == 1 ? "correct" : "wrong") : "" ?>" data-question-id="<?= $data['question_id'] ?>">
                                        <p class="question"><?= base64_decode($exae[$data['question_id']]['question']) ?></p>
                                        <textarea class="form-control" readonly><?= $data['answer'] ?></textarea>
                                        <p class="mt-2">Nilai : <span class="evaluate"><?= $data['value'] ?></span></p>
                                        <div><button class="mt-3 openControl btn btn-sm <?= $data['cstatus'] == "1" ? "done" : "" ?>" data-question-id="<?= $data['question_id'] ?>">UBAH</button></div>
                                        <div class="evaluation mt-3 <?= $data['cstatus'] == "1" ? "done" : "" ?>" style="display: flex;justify-content: space-between;">
                                            <select class="form-control" data-question-id="<?= $data['question_id'] ?>" style="width: 125px;">
                                                <option value="0">SALAH</option>
                                                <?Php for ($xi = 1; $xi <= $exae[$data['question_id']]['max_value']; $xi++) { ?>
                                                    <option value='<?= $xi ?>' <?= $xi == $data['value'] ? "selected" : "" ?>><?= $xi ?></option>
                                                <?php } ?>
                                            </select>
                                            <input type="submit" class="essayCorrection btn btn-info" data-question-id="<?= $data['question_id'] ?>" value="SIMPAN" />
                                        </div>
                                    </div>
                                <?Php $no++;
                                } ?>
                            </div>
                            <div id="multichoice">
                                <div class="d-flex space-between">
                                    <h4>Multiple Choice</h4>

                                </div>
                                <?Php $GET = query("examrec", "WHERE qtype='multiple' AND exam_id='$HASH' AND nis='$NIS'");
                                while ($data = mysqli_fetch_array($GET)) { ?>
                                    <div class="multiple soal" data-question-id="<?= $data['question_id'] ?>">
                                        <p class="question"><?= base64_decode($exmc[$data['question_id']]['question']) ?></p>
                                        <?Php foreach ($exmc[$data['question_id']]['option'] as $key => $val) {
                                            $true = $exmc[$data['question_id']]['true'];
                                            $anw = json_decode($data['answer']); ?>
                                            <div><input type='checkbox' <?= in_array($key, $anw) ? "checked" : "" ?>> <span class="<?= in_array($key, $true) ? "right" : "wrong" ?>"><?= base64_decode($val) ?></span></div>
                                        <?Php } ?>
                                    </div>
                                <?Php $no++;
                                } ?>
                            </div>
                            <div id="match">
                                <div class="d-flex space-between">
                                    <h4>Pencocokan</h4>

                                </div>
                                <style>
                                    span.result {
                                        font-size: 20px;
                                    }

                                    span.result.true:before {
                                        content: "✓";
                                        color: green;
                                    }

                                    span.result.false:before {
                                        content: "✖";
                                        color: red;
                                    }
                                </style>
                                <?Php $GET = query("examrec", "WHERE qtype='match' AND exam_id='$HASH' AND nis='$NIS'");
                                while ($data = mysqli_fetch_array($GET)) { ?>
                                    <div class="match soal" data-question-id="<?= $data['question_id'] ?>">
                                        <p class="question"><?= base64_decode($exmm[$data['question_id']]['question']) ?></p>
                                        <p>
                                            <b>Jawaban :</b>
                                            <span><?= base64_decode($excp[$data['answer']]) ?></span>
                                            <span class="result <?= $data['answer'] == $exmm[$data['question_id']]['answer_id'] ? "true" : "false" ?>"></span>
                                        </p>
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

                                function coba() {
                                    console.log("terpanggil");
                                }

                                function update() {
                                    console.log("terpanggill");

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
                                            location.reload();
                                        },
                                        error: function(data) {
                                            console.error("Terjadi kesalahan: " + data);
                                        }
                                    });
                                }

                                // update();

                                $(".openControl").on("click", function() {
                                    var QID = $(this).data("question-id");
                                    $(`div.soal[data-question-id=${QID}] .evaluation`).show();
                                    $(this).hide();
                                });

                                $("input.essayCorrection").on("click", function() {
                                    var QID = $(this).data("question-id");
                                    var VAL = $(`select[data-question-id=${QID}]`).val();

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

                                                $(`.essay.soal[data-question-id=${QID}] span.evaluate`).html(VAL);

                                                // update();
                                            }
                                        },
                                        error: function(data) {}
                                    });
                                    location.reload();
                                });
                            </script>
                        </div>
                    </div>
                </div>
        <?Php }
        } ?>
    </div>
</div>