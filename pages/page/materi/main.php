<div class="container-fluid">
    <div class="row">
        <?Php if (!isset($_GET['action'])) { ?>
            <div class="col-md-12">
                <div class="card">
                    <div class="header d-flex space-between">
                        <h4 class="title">Daftar Semua Materi</h4>
                        <?= $user['role'] == "teacher" ? "<a href='" . base_url("materi?action=add") . "' class='btn btn-sm btn-fill btn-info'>Tambah</a>" : "" ?>
                    </div>


                    <div class="content">
                        <div style="display: flex;gap: 15px;max-width: 500px;margin-bottom: 15px;">
                            <select name="pilih_kelas" class="filtermateri form-select form-control shadow-none" data-change="kelas">
                                <option value="all">Semua Kelas</option>
                                <?Php
                                for ($i = 0; $i <= count($class['array']) - 1; $i++) {
                                    if ($user['role'] == "admin") {
                                        echo "<option value='" . $class['array'][$i]['ID'] . "'>" . $class['array'][$i]['name'] . "</option>";
                                    } elseif ($user['role'] == "teacher") {
                                        if (in_array($class['array'][$i]['ID'], $guru[$unme]['classes'])) {
                                            echo "<option value='" . $class['array'][$i]['ID'] . "'>" . $class['array'][$i]['name'] . "</option>";
                                        }
                                    }
                                }
                                ?>
                                <option value="0">Ekskul</option>
                            </select>
                            <select name="pilih_mapel" class="filtermateri form-select form-control shadow-none" data-change="mapel">
                                <option value="all">Semua Mapel</option>
                                <?Php
                                for ($x = 0; $x <= count($mapel['array']) - 1; $x++) {
                                    if ($user['role'] == "admin") {
                                        echo "<option data-tipe='" . $mapel['array'][$x]['tipe'] . "' value='" . $mapel['array'][$x]['ID'] . "'>" . $mapel['array'][$x]['title'] . "</option>";
                                    } elseif ($user['role'] == "teacher") {
                                        if (in_array($mapel['array'][$x]['ID'], $guru[$unme]['subject'])) {
                                            echo "<option data-tipe='" . $mapel['array'][$x]['tipe'] . "' value='" . $mapel['array'][$x]['ID'] . "'>" . $mapel['array'][$x]['title'] . "</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                            <select name="pilih_bab" data-change="bab" class="filtermateri form-select form-control shadow-none">
                                <option value="all">Semua BAB</option>
                                <?Php for ($i = 1; $i <= 20; $i++) {
                                    echo "<option value='" . $i . "'>BAB " . $i . "</option>";
                                } ?>
                            </select>
                            <script>
                                $("select.filtermateri").on("change", function() {
                                    var chang = $(this).data("change");
                                    var kelas = $("select[name='pilih_kelas']").val();
                                    var mapel = $("select[name='pilih_mapel']").val();
                                    var bab = $("select[name='pilih_bab']").val();

                                    $("tbody tr").hide();
                                    if (chang == "kelas") {
                                        $("select[name='pilih_mapel'], select[name='pilih_bab']").val("all").change();
                                        if (kelas != "all") {
                                            $('tbody tr').each(function() {
                                                var dataClasses = $(this).data('classes');
                                                if (Array.isArray(dataClasses)) {
                                                    if (dataClasses.includes(parseInt(kelas))) {
                                                        $(this).show();
                                                        $(this).addClass("able");
                                                    } else {
                                                        $(this).removeClass("able");
                                                        $(this).hide();
                                                    }
                                                }
                                            });
                                        }

                                        if (kelas == "0") {
                                            $("select[name='pilih_mapel'] option[data-tipe='umum']").hide();
                                        } else {
                                            $("select[name='pilih_mapel'] option[data-tipe='umum']").show();
                                        }
                                    } else if (chang == "mapel") {
                                        $("select[name='pilih_bab']").val("all").change();

                                        if (mapel == "all") {
                                            if (kelas == "all") {
                                                $(`tbody tr`).show();
                                            } else {
                                                $(`tbody tr.able`).show();
                                            }
                                        } else {
                                            if (kelas == "all") {
                                                $(`tbody tr[data-subject=${mapel}]`).show();
                                            } else {
                                                $(`tbody tr.able[data-subject=${mapel}]`).show();
                                            }
                                        }
                                    } else if (chang == "bab") {
                                        if (bab == "all") {
                                            if (kelas == "all" && mapel == "all") {
                                                $("tbody tr").show();
                                            } else {
                                                if (kelas == "all") {
                                                    $(`tbody tr[data-subject=${mapel}]`).show();
                                                } else if (mapel == "all") {
                                                    $(`tbody tr.able`).show();
                                                } else {
                                                    $(`tbody tr.able[data-subject=${mapel}]`).show();
                                                }
                                            }
                                        } else {
                                            if (kelas == "all" && mapel == "all") {
                                                $(`tbody tr[data-bab=${bab}]`).show();
                                            } else {
                                                if (kelas == "all") {
                                                    $(`tbody tr[data-subject=${mapel}][data-bab=${bab}]`).show();
                                                } else if (mapel == "all") {
                                                    $(`tbody tr.able[data-bab=${bab}]`).show();
                                                } else {
                                                    $(`tbody tr.able[data-subject=${mapel}][data-bab=${bab}]`).show();
                                                }
                                            }
                                        }
                                    }
                                });
                            </script>
                        </div>
                        <div class="tableHolder">
                            <table class="table table-hover table-striped action">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Mapel</th>
                                        <th>BAB</th>
                                        <th>Kelas</th>
                                        <?= $user['role'] == "admin" ? "<th>Uploader</th>" : "" ?>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $limit = 10; // Jumlah data per halaman
                                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                    $offset = ($page - 1) * $limit;

                                    // Hitung total data
                                    $totalQuery = $user['role'] == "admin" ? query("materi", "") : ($user['role'] == "teacher" ? query("materi", "WHERE uploader='$unme'") : query("materi", "WHERE ID='HELLO'"));
                                    $totalData = mysqli_num_rows($totalQuery);
                                    $totalPages = ceil($totalData / $limit);

                                    // Ambil data dengan limit dan offset
                                    $getmapel = $user['role'] == "admin" ? query("materi", "ORDER BY ID DESC LIMIT $limit OFFSET $offset") : ($user['role'] == "teacher" ? query("materi", "WHERE uploader='$unme' ORDER BY ID DESC LIMIT $limit OFFSET $offset") : query("materi", "WHERE ID='HELLO' LIMIT $limit OFFSET $offset"));
                                    $no = $offset + 1;

                                    if (mysqli_num_rows($getmapel) > 0) {
                                        while ($data = mysqli_fetch_array($getmapel)) {
                                    ?>
                                            <tr data-classes="[<?= implode(",", $materi['data'][$data['ID']]['classes']) ?>]" data-subject="<?= $data['subject_id'] ?>" data-bab="<?= $data['bab'] ?>" data-id="<?= $data['ID'] ?>">
                                                <td><?= $data['title'] ?></td>
                                                <td><?= $mapel['detail'][$data['subject_id']]['title'] ?></td>
                                                <td>BAB <?= $data['bab'] ?></td>
                                                <td><?Php foreach ($materi['data'][$data['ID']]['classes'] as $clxa) {
                                                        $clxxa[$data['ID']][] = $class['detail'][$clxa];
                                                    }
                                                    echo implode(",", $clxxa[$data['ID']]); ?></td>
                                                <?= $user['role'] == "admin" ? '<td><span>' . $guru[$data['uploader']]['name'] . '</span> <span class="text-muted">[' . $data['uploader'] . ']</span></td>' : '' ?>
                                                <td>
                                                    <a href="<?= base_url("static/files/modules/" . $data['file_name']) ?>" class="btn btn-sm btn-success btn-fill" download><i class="fa fa-download"></i></a>
                                                    <input type="button" class="deleteMateri btn btn-sm btn-danger" data-id="<?= $data['ID'] ?>" value="Hapus" />
                                                    <a href="<?= base_url("materi?action=edit&ID=" . $data['ID']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                                </td>
                                            </tr>
                                    <?php
                                            $no++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>Belum ada materi / modul yang diupload.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <script>
                            $(".deleteMateri").on("click", function() {
                                var id = $(this).data("id");

                                if (confirm("Apakah Anda yakin ingin menghapus materi ini?")) {
                                    $.ajax({
                                        url: "<?= base_url("endpoint/index.php") ?>",
                                        type: "POST",
                                        data: {
                                            "action": "crud/materi",
                                            "activity": "delete",
                                            "ID": id
                                        },
                                        success: function(data) {
                                            var response = JSON.parse(data);
                                            console.log(data);
                                            if (response.OK == true) {
                                                $(`tbody tr[data-id=${id}]`).remove();
                                            }
                                        },
                                        error: function(data) {}
                                    });
                                } else {
                                    alert("Operasi dibatalkan");
                                }
                            });
                        </script>
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

                </div>
            </div>
            <?Php } else {
            $action = $_GET['action'];
            if ($action == "add") { ?>
                <div class="col-md-6">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Tambah Materi</h4>
                        </div>
                        <div class="content">
                            <form>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Judul</label>
                                            <input type="text" class="form-control" name="title" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Mata Pelajaran</label>
                                            <select name="mapel" class="form-select form-control shadow-none">
                                                <option data-r="default" value="">- Pilih -</option>
                                                <?Php for ($i = 0; $i <= count($guru[$unme]['subject']) - 1; $i++) { ?>
                                                    <option value="<?= $guru[$unme]['subject'][$i] ?>"><?= $mapel['detail'][$guru[$unme]['subject'][$i]]['title'] ?> <?= $mapel['detail'][$guru[$unme]['subject'][$i]]['tipe'] == "ekskul" ? "( Ekskul )" : "" ?></option>
                                                <?Php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>BAB</label>
                                            <select name="bab" class="form-select form-control shadow-none">
                                                <option value="">- Pilih -</option>
                                                <?Php for ($i = 1; $i <= 20; $i++) {
                                                    echo "<option value='" . $i . "'>BAB " . $i . "</option>";
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kelas</label>
                                            <div id="pilihkelas" style="display: grid;gap: 10px;">
                                                <input type="text" class="form-control" value="Pilih Kelas" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Upload File Materi</label>
                                            <input type="file" name="file" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex space-between" style="align-items: center;">
                                    <span class="text-warning"></span>
                                    <input type="button" id="simpan" class="btn btn-info btn-fill pull-right" value="Simpan" />
                                </div>
                            </form>
                            <script>
                                $("select[name='mapel']").on("change", function() {
                                    var val = $(this).val();
                                    $("#pilihkelas").html("");
                                    $.ajax({
                                        url: "<?= base_url("endpoint/index.php") ?>",
                                        type: "POST",
                                        data: {
                                            "action": "GET/select_class",
                                            "mapel": val
                                        },
                                        success: function(data) {
                                            var response = JSON.parse(data);
                                            console.log(data);
                                            if (response.OK == true) {
                                                $.each(response.result, function(index, item) {
                                                    var appends = item;
                                                    $("#pilihkelas").append(appends);
                                                });

                                                if (response.tipe == "ekskul") {
                                                    $("#pilihkelas .fgroup[data-kelas='0']").click();
                                                }
                                            } else {
                                                $("select[name='mapel']").html("");
                                                $("select[name='mapel']").attr("disabled", true);
                                            }
                                        },
                                        error: function(data) {}
                                    });
                                });

                                $("input#simpan").on("click", function() {
                                    var kelas = $("select[name='kelas']").val(),
                                        mapel = $("select[name='mapel']").val(),
                                        bab = $("select[name='bab']").val(),
                                        judul = $("input[name='title']").val(),
                                        file = $("input[name='file']");

                                    var form = $("form")[0],
                                        formData = new FormData(form);

                                    formData.append("action", "crud/materi");
                                    formData.append("activity", "add");

                                    if (kelas != "" && mapel != "" && bab != "" && judul != "" && file.get(0).files.length !== 0) {
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
                                                    alert("Proses berhasil");
                                                    window.location.replace("<?= base_url("materi") ?>");
                                                } else {
                                                    alert("Gagal menambahkan materi");
                                                }
                                            },
                                            error: function(data) {}
                                        });
                                    } else {
                                        alert("Lengkapi isian terlebih dahulu");
                                    }
                                });

                                $(document).on("click", ".fgroup", function() {
                                    var kelas = $(this).data("kelas");

                                    if (!$(this).hasClass("active")) {
                                        $(`input[name='class[]'][value=${kelas}]`).prop("checked", true);
                                        $(this).addClass("active");
                                    } else {
                                        $(`input[name='class[]'][value=${kelas}]`).prop("checked", false);
                                        $(this).removeClass("active");
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <?Php } elseif ($action == "edit" && isset($_GET['ID'])) {
                $ID = $_GET['ID'];
                $qe = $user['role'] == "admin" ? select("materi", "ID='$ID'") : ($user['role'] == "teacher" ? select("materi", "ID='$ID' AND uploader='$unme'") : select("materi", "ID='HELLo'"));
                if (mysqli_num_rows($qe) == 1) {
                    $data = mysqli_fetch_array($qe);
                ?>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Edit Materi</h4>
                            </div>
                            <div class="content">
                                <form>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Judul</label>
                                                <input type="text" class="form-control" name="title" value="<?= $data['title'] ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Mata Pelajaran</label>
                                                <select name="mapel" class="form-select form-control shadow-none">
                                                    <option data-r="default" value="">- Pilih -</option>
                                                    <?Php for ($i = 0; $i <= count($guru[$unme]['subject']) - 1; $i++) { ?>
                                                        <option value="<?= $guru[$unme]['subject'][$i] ?>" <?= $data['subject_id'] == $guru[$unme]['subject'][$i] ? "selected" : "" ?>><?= $mapel['detail'][$guru[$unme]['subject'][$i]]['title'] ?> <?= $mapel['detail'][$guru[$unme]['subject'][$i]]['tipe'] == "ekskul" ? "( Ekskul )" : "" ?></option>
                                                    <?Php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>BAB</label>
                                                <select name="bab" class="form-select form-control shadow-none">
                                                    <option value="">- Pilih -</option>
                                                    <option value="1">BAB 1</option>
                                                    <option value="2">BAB 2</option>
                                                    <option value="3">BAB 3</option>
                                                    <option value="4">BAB 4</option>
                                                    <option value="5">BAB 5</option>
                                                    <option value="6">BAB 6</option>
                                                    <option value="7">BAB 7</option>
                                                    <option value="8">BAB 8</option>
                                                    <option value="9">BAB 9</option>
                                                </select>
                                            </div>
                                        </div>
                                        <script>
                                            $("select[name='bab']").val("<?= $data['bab'] ?>");
                                        </script>
                                        <style>
                                            .fgroup {
                                                /* display: inline-block; */
                                                gap: 10px;
                                                align-items: center;
                                                font-size: 20px;
                                                color: #777;
                                                cursor: pointer;
                                                padding: 4.5px 15px;
                                                border: 1px solid #DFDFDF;
                                                border-radius: 3px;
                                            }

                                            .fgroup.active {
                                                border: 1px solid #5ca3ff;
                                                color: #5ca3ff;
                                            }

                                            .fgroup input[type='checkbox'] {
                                                /* display: none; */
                                            }
                                        </style>
                                        <div class="col-md-6">
                                            <label>Kelas</label>
                                            <div id="pilihkelas" style="display: grid;gap: 10px;">
                                                <?Php for ($o = 0; $o <= count($guru[$data['uploader']]['sclass'][$data['subject_id']]) - 1; $o++) {
                                                    $dx = $guru[$data['uploader']]['sclass'][$data['subject_id']][$o]; ?>
                                                    <div class='fgroup <?= in_array($dx, $materi['data'][$data['ID']]['classes']) ? "active" : "" ?>' data-kelas='<?= $dx ?>'><input type='checkbox' name='class[]' value='<?= $dx ?>' <?= in_array($dx, $materi['data'][$data['ID']]['classes']) ? "checked" : "" ?> /> <span><?= $class['info'][$dx] ?></span></div>
                                                <?Php } ?>
                                            </div>
                                            <script>
                                                $(".fgroup").on("click", function() {
                                                    var kelas = $(this).data("kelas");

                                                    if (!$(this).hasClass("active")) {
                                                        $(`input[name='class[]'][value=${kelas}]`).prop("checked", true);
                                                        $(this).addClass("active");
                                                    } else {
                                                        $(`input[name='class[]'][value=${kelas}]`).prop("checked", false);
                                                        $(this).removeClass("active");
                                                    }
                                                });
                                            </script>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <label>File</label>
                                            <div><a href="<?= base_url("static/files/modules/" . $data['file_name']) ?>" class="btn btn-sm form-control" target="_blank"><i class="fa fa-download"></i> Download</a></div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Upload File Baru ( Opsional )</label>
                                                <input type="file" name="file" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex space-between" style="align-items: center;">
                                        <span class="text-warning"></span>
                                        <input type="button" class="btn btn-info btn-fill pull-right" value="Simpan" />
                                    </div>
                                </form>
                                <script>
                                    $("select[name='mapel']").on("change", function() {
                                        var val = $(this).val();
                                        $("#pilihkelas").html("");
                                        $.ajax({
                                            url: "<?= base_url("endpoint/index.php") ?>",
                                            type: "POST",
                                            data: {
                                                "action": "GET/select_class",
                                                "mapel": val
                                            },
                                            success: function(data) {
                                                var response = JSON.parse(data);
                                                console.log(data);
                                                if (response.OK == true) {
                                                    $.each(response.result, function(index, item) {
                                                        var appends = item;
                                                        $("#pilihkelas").append(appends);
                                                    });

                                                    if (response.tipe == "ekskul") {
                                                        $("#pilihkelas .fgroup[data-kelas='0']").click();
                                                    }
                                                } else {
                                                    $("select[name='mapel']").html("");
                                                    $("select[name='mapel']").attr("disabled", true);
                                                }
                                            },
                                            error: function(data) {}
                                        });
                                    });

                                    $("input[type='button']").on("click", function() {
                                        $("span.text-warning").html("");

                                        var form = $("form")[0],
                                            formData = new FormData(form);
                                        formData.append("action", "crud/materi");
                                        formData.append("activity", "update");
                                        formData.append("ID", <?= $_GET['ID'] ?>);

                                        setTimeout(function() {
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
                                                        $("span.text-warning").html("Berhasil memperbarui informasi.");
                                                    } else {
                                                        $("span.text-warning").html("Gagal memperbarui informasi.");
                                                    }
                                                },
                                                error: function(data) {}
                                            });
                                        }, 500);
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
        <?Php
                }
            }
        } ?>
    </div>
</div>