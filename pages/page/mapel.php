<?Php if ($user['role'] == "admin") { ?>
    <div class="container-fluid">
        <div class="row">
            <?php if (!isset($_GET['action'])) { ?>
                <div class="col-md-12">
                    <div class="card">
                        <div class="header d-flex space-between" style="align-items-center">
                            <h4 class="title">Daftar Mapel</h4>
                            <a href="<?= base_url("mapel?action=add") ?>" class="btn btn-sm btn-primary">Tambah Mapel</a>
                        </div>
                        <?php
                        $limit = 10; // Jumlah data per halaman
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $offset = ($page - 1) * $limit;

                        // Hitung total data
                        $total_query = query("subject", "ORDER BY CASE WHEN tipesubject='umum' THEN 0 ELSE 1 END, subject_title ASC");
                        $total_rows = mysqli_num_rows($total_query);
                        $total_pages = ceil($total_rows / $limit);

                        // Ambil data sesuai pagination
                        $getsiswa = query("subject", "ORDER BY CASE WHEN tipesubject='umum' THEN 0 ELSE 1 END, subject_title ASC LIMIT $limit OFFSET $offset");
                        ?>

                        <div class="content">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Mapel</th>
                                        <th style="text-align: right;">Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($getsiswa) > 0) {
                                        while ($data = mysqli_fetch_array($getsiswa)) { ?>
                                            <tr>
                                                <td><?= $data['tipesubject'] == "ekskul" ? "<i class='text-warning'>Ekskul</i> - " : "" ?><?= $data['subject_title'] ?></td>
                                                <td style="text-align: right;">
                                                    <a href="<?= base_url("mapel?action=edit&mapel_id=" . $data['ID']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                                </td>
                                            </tr>
                                    <?php }
                                    } else {
                                        echo "<tr><td colspan='2'>Belum ada mata pelajaran.</td></tr>";
                                    } ?>
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
                <?php } else {
                if ($_GET['action'] == "add") { ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Tambah Mata Pelajaran</h4>
                            </div>
                            <div class="content">
                                <form>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Nama Mapel</label>
                                                <input type="text" class="form-control" name="title" placeholder="-" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Tipe</label>
                                                <select name="tipe" id="" class="form-control">
                                                    <option value="umum">Umum</option>
                                                    <option value="ekskul">Ekskul</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="selectTeacher">
                                        <?php
                                        $getGuru = query("teacher", "ORDER BY full_name ASC");
                                        while ($gx = mysqli_fetch_array($getGuru)) {
                                            $Getguru[] = array(
                                                "nip"  => $gx['nip'],
                                                "name" => $gx['full_name']
                                            );
                                        }

                                        $getkelas = query("class", "ORDER BY ID ASC");
                                        while ($kdata = mysqli_fetch_array($getkelas)) {
                                        ?>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" value="<?= $kdata['class_name'] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <select name="mapel_pengajar[]" class="form-control">
                                                            <option value="">-</option>
                                                            <?Php for ($ix = 0; $ix <= count($Getguru) - 1; $ix++) { ?>
                                                                <option value="<?= $kdata['ID'] ?>:<?= $Getguru[$ix]['nip'] ?>"><?= $Getguru[$ix]['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>

                                    <input type="button" class="btn btn-info btn-fill pull-right" value="Simpan" />
                                    <div class="clearfix"></div>
                                </form>
                                <script>
                                    // $("select[name='tipe']").on("change", function(){
                                    //     var tipe = $(this).val();
                                    //     if(tipe == "ekskul") {
                                    //         $("#selectTeacher").hide();
                                    //     } else {
                                    //         $("#selectTeacher").show();
                                    //     }
                                    // });

                                    $("input[type='button']").on("click", function() {
                                        var form = $("form")[0],
                                            formData = new FormData(form);
                                        formData.append("action", "crud/mapel");
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
                                                    window.location.replace("<?= base_url("mapel") ?>");
                                                } else {
                                                    alert("Gagal memperbarui informasi.");
                                                }
                                            },
                                            error: function(data) {}
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                    <?Php } elseif ($_GET['action'] == "edit" && isset($_GET['mapel_id'])) {
                    $mapelId = $_GET['mapel_id'];
                    $query = select("subject", "ID='$mapelId'");
                    if (mysqli_num_rows($query) == 1) {
                        $mdata = mysqli_fetch_array($query);
                    ?>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title">Edit Mata Pelajaran</h4>
                                </div>
                                <div class="content">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-8 form-group">
                                                <label>Nama Mapel</label>
                                                <input type="text" class="form-control" name="title" placeholder="-" value="<?= $mdata['subject_title'] ?>" />
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Tipe</label>
                                                    <select class="form-control" disabled>
                                                        <option value="umum" <?= $mdata['tipesubject'] == "umum" ? "selected" : "" ?>>Umum</option>
                                                        <option value="ekskul" <?= $mdata['tipesubject'] == "umum" ? "" : "selected" ?>>Ekskul</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <?Php for ($c = 0; $c <= count($class['array']) - 1; $c++) { ?>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" value="<?= $class['array'][$c]['name'] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <select name="mapel_pengajar[]" class="form-control">
                                                            <option value="<?= $class['array'][$c]['ID'] . ":-" ?>">-</option>
                                                            <?Php for ($ix = 0; $ix <= count($guru['array']) - 1; $ix++) {
                                                                $simp = $class['array'][$c]['ID'] . ":" . $mapelId . ":" . $guru['array'][$ix]['nip']; ?>
                                                                <option data-state="<?= $simp ?>" value="<?= $class['array'][$c]['ID'] ?>:<?= $guru['array'][$ix]['nip'] ?>" <?= in_array($simp, $subjetc) ? "selected" : "" ?>><?= $guru['array'][$ix]['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        <?Php } ?>

                                        <div class="d-flex space-between" style="align-items: center;">
                                            <span class="text-warning"></span>
                                            <div style="display: flex;gap: 10px;align-items: center;">
                                                <span class="deleteMapel text-danger cursor-pointer" style="padding: 0px 2px;border-bottom: 1px solid var(--red);">Hapus Mapel</span>
                                                <input type="button" id="update" class="btn btn-info btn-fill" value="Simpan" />
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </form>
                                    <script>
                                        var mapelId = <?= $mapelId ?>;

                                        $("span.deleteMapel").on("click", function() {
                                            var convirm = prompt("Ketik 'confirm' jika Anda ingin menghapus mapel ini beserta guru yang terlibat & kelas yang di empu.");
                                            if (convirm == "confirm") {
                                                $.ajax({
                                                    url: "<?= base_url("endpoint/index.php") ?>",
                                                    type: "POST",
                                                    data: {
                                                        "action": "crud/mapel",
                                                        "activity": "delete",
                                                        "mapel_id": mapelId
                                                    },
                                                    success: function(data) {
                                                        var response = JSON.parse(data);
                                                        console.log(data);
                                                        if (response.OK == true) {
                                                            alert("Proses berhasil");
                                                            window.location.replace("<?= base_url("mapel") ?>");
                                                        } else {
                                                            alert("Proses gagal, mohon refresh halaman & ulangi");
                                                        }
                                                    },
                                                    error: function(data) {}
                                                });
                                            }
                                        });

                                        $("input[type='button']#update").on("click", function() {
                                            $("span.text-warning").html("");
                                            setTimeout(function() {
                                                var form = $("form")[0],
                                                    formData = new FormData(form);
                                                formData.append("action", "crud/mapel");
                                                formData.append("ID", mapelId);
                                                formData.append("activity", "update");
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
                        <?Php if ($mdata['tipesubject'] == "ekskul") { ?>
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="header" style="display: flex;justify-content: space-between;gap: 5px;">
                                        <h4 class="title">Daftar Murid Ekskul</h4>
                                        <div>
                                            <a class='btn btn-sm btn-danger' href="<?= base_url('static/files/templates/import_murid.xlsx') ?>" download><i class='fa fa-download'></i></a>
                                            <button class="btn btn-sm btn-success import"><i class="fa fa-upload"></i></button>
                                            <a class="btn btn-sm btn-primary btn-fill" href="<?= base_url('mapel?action=addToEkskul&mapel_id=' . $mapelId) ?>">Tambah</a>
                                        </div>
                                    </div>
                                    <form action="POST" id="uploadExcel"> <input type="file" name="excel_file" id="excel_file" style="display: none;" /> </form>
                                    <div class="content">
                                        <table class="table table-hover table-striped action">
                                            <thead>
                                                <tr>
                                                    <th>Nama Siswa</th>
                                                    <th>Kelas</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $getsiswa = query("subjecs", "WHERE subject_id='$mapelId' ORDER BY ID DESC");
                                                while ($data = mysqli_fetch_array($getsiswa)) {
                                                ?>
                                                    <tr>
                                                        <td><a href="<?= base_url("siswa?action=lookup&ID=" . $siswa[$data['nis']]['ID']) ?>"><?= $siswa[$data['nis']]['name'] ?></a> <span class="text-muted">[ <?= $data['nis'] ?> ]</span></td>
                                                        <td><?= $class['info'][$siswa[$data['nis']]['class']] ?></td>
                                                        <td>
                                                            <input type="button" class="deleteFe" data-nis="<?= $data['nis'] ?>" value="Keluarkan" />
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <script>
                                        $(".import").on("click", function() {
                                            $("input#excel_file").click();
                                        });
                                        $("input#excel_file").on("change", function() {
                                            file = this.files[0];
                                            if (file && confirm("Yakin ingin mengupload file ini?")) {
                                                var form = $("form#uploadExcel")[0],
                                                    formData = new FormData(form);

                                                formData.append("action", "crud/mapel");
                                                formData.append("activity", "upload");
                                                formData.append("mapel_id", mapelId);

                                                alert(mapelId);

                                                // $.ajax({
                                                //     url: "<?= base_url("endpoint/index.php") ?>",
                                                //     type: "POST",
                                                //     data: formData,
                                                //     processData: false,
                                                //     contentType: false,
                                                //     success:function(data){
                                                //         var response = JSON.parse(data);
                                                //         window.location.reload();
                                                //         console.log(data);
                                                //     }, error:function(data){}
                                                // });
                                            }
                                        });

                                        $(".deleteFe").on("click", function() {
                                            var nis = $(this).data("nis");
                                            var cnv = prompt("Ketik 'confirm' jika Anda yakin menghapus siswa ini dari ekskul ini.");

                                            if (cnv == "confirm") {
                                                $.ajax({
                                                    url: "<?= base_url("endpoint/index.php") ?>",
                                                    type: "POST",
                                                    data: {
                                                        "action": "crud/mapel",
                                                        "activity": "deleteFe",
                                                        "mapel_id": mapelId
                                                    },
                                                    success: function(data) {
                                                        var response = JSON.parse(data);
                                                        console.log(data);
                                                        if (response.OK == true) {
                                                            alert("Proses berhasil");
                                                            window.location.replace("<?= base_url("mapel") ?>");
                                                        } else {
                                                            alert("Proses gagal, mohon refresh halaman & ulangi");
                                                        }
                                                    },
                                                    error: function(data) {}
                                                });
                                            }
                                        });
                                    </script>
                                </div>
                            </div>
                        <?Php } ?>
                    <?php }
                } elseif ($_GET['action'] == "addToEkskul" && isset($_GET['mapel_id']) && $user['role'] == "admin") {
                    $mplid = $_GET['mapel_id']; ?>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Tambah Murid Ekskul</h4>
                            </div>
                            <div class="content">
                                <form>
                                    <div class="row">
                                        <div class="col-sm-12 form-group">
                                            <label>NIS</label>
                                            <div style="display: grid;gap: 15px;grid-template-columns: 9fr 3fr;">
                                                <input type="number" class="form-control" name="nis" placeholder="Nomor Induk Siswa" />
                                                <input type="button" id="getSiswa" class="btn btn-sm btn-info btn-fill form-control" value="CEK" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="dataValid" class="d-none" style="border: 1px solid #DFDFDF;border-radius: 4px;padding: 10px 8px;margin-bottom: 10px;">
                                                <h4 style="margin: 0px;font-size: 18px;font-weight: bold;">Data Siswa</h4>
                                                <div><span><b>Nama : </b><span id="name">-</span></span></div>
                                                <div><span><b>NIS : </b><span id="nis">-</span></span></div>
                                                <div><span><b>Kelas : </b><span id="class">-</span></span></div>
                                            </div>
                                            <div id="dataInvalid" class="d-none">
                                                <p class="alert alert-danger">NIS tidak terdaftar</p>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="nisfix" />
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label>Pilih Ekskul</label>
                                            <select name="ekskul" class="form-control" readonly>
                                                <?Php foreach ($mapel['tipe']['ekskul'] as $ekskull) {
                                                    $stx = $ekskull['ID'] == $mplid ? "selected" : "";
                                                    echo "<option value='" . $ekskull['ID'] . "' " . $stx . ">" . $ekskull['NAME'] . "</option>";
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="button" id="addToEkskul" class="btn btn-sm btn-info btn-fill" value="TAMBAHKAN" disabled />
                                </form>
                                <script>
                                    $("form input[type='button']#getSiswa").on("click", function() {
                                        var nis = $("input[name='nis']").val();

                                        $("#dataValid, #dataInvalid").addClass("d-none");

                                        if (nis != "") {
                                            $.ajax({
                                                url: "<?= base_url("endpoint/index.php") ?>",
                                                type: "POST",
                                                data: {
                                                    "action": "GET/siswa",
                                                    "activity": "get",
                                                    "nis": nis
                                                },
                                                success: function(data) {
                                                    var response = JSON.parse(data);
                                                    console.log(data);
                                                    if (response.OK == true) {
                                                        $("span#name").html(response.data.name);
                                                        $("span#nis").html(response.data.nis);
                                                        $("span#class").html(response.data.class);

                                                        $("input[name='nisfix']").val(response.data.nis);

                                                        $("#dataValid").removeClass("d-none");
                                                        $("#addToEkskul").removeAttr("disabled");
                                                    } else {
                                                        $("#dataInvalid").removeClass("d-none");
                                                        $("#addToEkskul").attr("disabled", true);
                                                    }
                                                },
                                                error: function(data) {}
                                            });
                                        }
                                    });

                                    $("form input[type='button']#addToEkskul").on("click", function() {
                                        var form = $("form")[0],
                                            formData = new FormData(form);

                                        formData.append("action", "crud/mapel");
                                        formData.append("activity", "addToEkskul");

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
                                                    window.location.replace("<?= base_url("mapel?action=edit&mapel_id=" . $mplid) ?>");
                                                } else {
                                                    alert(response.MSG);
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
<?Php } ?>