<?Php if ($user['role'] == "admin") { ?>
    <div class="container-fluid">
        <div class="row">
            <?php if (!isset($_GET['action'])) { ?>
                <div cl ass="col-md-12">
                    <div class="card mt-3">
                        <div class="header d-flex space-between">
                            <h4 class="title">Daftar Guru</h4>
                            <div style="display: flex;gap: 5px;">
                                <a class='btn btn-sm btn-danger' href='<?= base_url('static/files/templates/import_guru.xlsx') ?>' download><i class='fa fa-download'></i></a>
                                <button class="btn btn-sm btn-success import"><i class="fa fa-upload"></i></button>
                                <a href="<?= base_url("guru?action=add") ?>" class="btn btn-sm btn-primary">Tambah</a>
                            </div>
                        </div>
                        <form action="POST" id="uploadExcel">
                            <input type="file" name="excel_file" id="excel_file" style="display: none;" />
                        </form>
                        <script>
                            $(".import").on("click", function() {
                                $("input#excel_file").click();
                            });
                            $("input#excel_file").on("change", function() {
                                file = this.files[0];
                                if (file && confirm("Yakin ingin mengupload file ini?")) {
                                    var form = $("form#uploadExcel")[0],
                                        formData = new FormData(form);

                                    formData.append("action", "crud/teacher");
                                    formData.append("activity", "upload");

                                    $.ajax({
                                        url: "<?= base_url("endpoint/index.php") ?>",
                                        type: "POST",
                                        data: formData,
                                        processData: false,
                                        contentType: false,
                                        success: function(data) {
                                            var response = JSON.parse(data);
                                            window.location.reload();
                                            console.log(data);
                                        },
                                        error: function(data) {}
                                    });
                                }
                            });
                        </script>
                        <?php
                        $limit = 10; // Jumlah item per halaman
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $offset = ($page - 1) * $limit;

                        // Hitung total data
                        $total_query = query("teacher", "WHERE nip NOT IN ($deactivatedU)");
                        $total_rows = mysqli_num_rows($total_query);
                        $total_pages = ceil($total_rows / $limit);

                        // Ambil data dengan pagination
                        $getsiswa = query("teacher", "WHERE nip NOT IN ($deactivatedU) ORDER BY full_name ASC LIMIT $limit OFFSET $offset");
                        ?>

                        <div class="content">
                            <div class="tableHolder">
                                <table class="table table-hover table-striped action">
                                    <thead>
                                        <tr>
                                            <th>NIP</th>
                                            <th>Nama Guru</th>
                                            <th>No. Telepon</th>
                                            <th>E-Mail</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (mysqli_num_rows($getsiswa) > 0) {
                                            while ($data = mysqli_fetch_array($getsiswa)) { ?>
                                                <tr>
                                                    <td><?= $data['nip'] ?></td>
                                                    <td><a href="<?= base_url("guru?action=edit&ID=" . $data['ID']) ?>" target="_blank"><?= $data['full_name'] ?></a></td>
                                                    <td><?= $data['phone'] ?></td>
                                                    <td><?= $data['email'] ?></td>
                                                    <td><a href="<?= base_url("guru?action=edit&ID=" . $data['ID']) ?>" class="btn btn-sm btn-warning">Edit</a></td>
                                                </tr>
                                        <?php }
                                        } else {
                                            echo "<tr><td colspan='5'>Belum ada data guru.</td></tr>";
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
                </div>
                <?php } else {
                if ($_GET['action'] == "add") { ?>
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Tambah Guru</h4>
                            </div>
                            <div class="content">
                                <form>
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <div class="form-group">
                                                <label>Nama Lengkap</label>
                                                <input type="text" class="form-control" name="name" />
                                            </div>
                                        </div>
                                        <div class="col-lg-5">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Jenis Kelamin</label>
                                                <select name="gender" id="" class="form-control">
                                                    <option value="L">Laki-Laki</option>
                                                    <option value="P">Perempuan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <div class="form-group">
                                                <label>Tempat Lahir</label>
                                                <input type="text" class="form-control" name="birthloc" />
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Tanggal Lahir</label>
                                                <input type="date" class="form-control" name="birthdate" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <div class="form-group">
                                                <label>No. Telpon</label>
                                                <input type="text" class="form-control" name="phone" />
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="form-group">
                                                <label>Alamat Email</label>
                                                <input type="text" class="form-control" name="email" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <textarea name="address" rows="3" class="form-control" placeholder="Alamat Lengkap"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-5">
                                            <div class="form-group">
                                                <label>NIP</label>
                                                <input type="text" name="nip" class="form-control text-center" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input type="text" name="password" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 clearfix"></div>
                                    <div class="d-flex space-between" style="align-items: center;">
                                        <span class="text-warning"></span>
                                        <input type="button" class="btn btn-sm btn-success" value="Simpan" />
                                    </div>
                                    <p class="mt-3"></p>
                                    <script>
                                        $("input[type='button']").on("click", function() {
                                            $("span.text-warning").html("");

                                            setTimeout(function() {
                                                var form = $("form")[0],
                                                    formData = new FormData(form);
                                                formData.append("action", "crud/teacher");
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
                                                            window.location.replace("guru?action=edit&ID=" + response.result_id);
                                                        } else {
                                                            $("span.text-warning").html("Gagal memperbarui informasi.");
                                                        }
                                                    },
                                                    error: function(data) {}
                                                });
                                            }, 500);
                                        });
                                    </script>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?Php } elseif ($_GET['action'] == "edit" && isset($_GET['ID'])) {
                    $ID = $_GET['ID'];
                    $check = select("teacher", "ID='$ID'");
                    if (mysqli_num_rows($check) == 1) {
                        $data = mysqli_fetch_array($check);
                    ?>
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title">Edit Data Guru : <?= $data['nip'] ?></h4>
                                </div>
                                <div class="content">
                                    <form>
                                        <div class="row">
                                            <div class="col-lg-7">
                                                <div class="form-group">
                                                    <label>Nama Lengkap</label>
                                                    <input type="text" class="form-control" name="name" placeholder="-" value="<?= $data['full_name'] ?>" />
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Jenis Kelamin</label>
                                                    <select name="gender" id="" class="form-control">
                                                        <option value="L" <?= $data['gender'] == "L" ? " selected" : "" ?>>Laki-Laki</option>
                                                        <option value="P" <?= $data['gender'] == "P" ? " selected" : "" ?>>Perempuan</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label>Tempat Lahir</label>
                                                    <input type="text" class="form-control" name="birthloc" placeholder="-" value="<?= $data['birthloc'] ?>" />
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Tanggal Lahir</label>
                                                    <input type="date" class="form-control" name="birthdate" value="<?= $data['birthdate'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label>No. Telpon</label>
                                                    <input type="text" class="form-control" name="phone" placeholder="-" value="<?= $data['phone'] ?>" />
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <div class="form-group">
                                                    <label>Alamat Email</label>
                                                    <input type="text" class="form-control" name="email" placeholder="-" value="<?= $data['email'] ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <textarea name="address" rows="3" class="form-control"><?= $data['address'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label>NIP</label>
                                                    <input type="text" class="form-control text-center" placeholder="-" value="<?= $data['nip'] ?>" disabled />
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <div class="form-group">
                                                    <label>SIGN</label>
                                                    <input type="text" name="sign" class="form-control" placeholder="Guru" value="<?= $data['sign'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3 clearfix"></div>
                                        <div class="d-flex space-between" style="align-items: center;">
                                            <span class="text-warning"></span>
                                            <input type="button" class="btn btn-sm btn-success" value="Perbarui" />
                                        </div>
                                        <p class="mt-3"></p>
                                        <script>
                                            $("input[type='button']").on("click", function() {
                                                $("span.text-warning").html("");

                                                setTimeout(function() {
                                                    var form = $("form")[0],
                                                        formData = new FormData(form);
                                                    formData.append("action", "crud/teacher");
                                                    formData.append("activity", "update");
                                                    formData.append("ID", <?= $ID ?>);
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
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="header d-flex space-between">
                                    <h4 class="title">Mata Pelajaran</h4>
                                    <input type="button" id="addNew" class="btn btn-sm btn-success d-none" value="Tambah" />
                                </div>
                                <style>
                                    @media(max-width: 576px) {
                                        .addNew .row {
                                            display: grid;
                                            gap: 10px;
                                        }
                                    }
                                </style>
                                <div class="content">
                                    <div class="addNew d-none">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <select name="mapel" class="form-control">
                                                    <?Php for ($i = 0; $i <= count($mapel['array']) - 1; $i++) { ?><option value="<?= $mapel['array'][$i]['ID'] ?>" data-tipe="<?= $mapel['array'][$i]['tipe'] ?>"><?= $mapel['array'][$i]['tipe'] == "ekskul" ? "Ekskul - " : "" ?><?= $mapel['array'][$i]['title'] ?></option><?Php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <select name="class" class="form-control">
                                                    <?Php for ($i = 0; $i <= count($class['array']) - 1; $i++) { ?><option value="<?= $class['array'][$i]['ID'] ?>"><?= $class['array'][$i]['name'] ?></option><?Php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="button" id="addNewMapel" class="btn btn-primary" style="width: 100%;" value="Simpan" />
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table-hover table-striped mt-3" style="width: 100%;">
                                        <tbody>
                                            <?Php $nip = $data['nip'];
                                            $gmm = query("subjetc", "WHERE nip='$nip'");
                                            if (mysqli_num_rows($gmm) > 0) {
                                                while ($info = mysqli_fetch_array($gmm)) { ?>
                                                    <tr data-id="<?= $info['ID'] ?>">
                                                        <td style="padding: 10px;"><?= $class['info'][$info['class']] ?></td>
                                                        <td><?= $mapel['detail'][$info['subject_id']]['title'] ?></td>
                                                        <td class="text-right d-none" style="padding: 10px;"><span class="mapel-delete text-danger cursor-pointer" data-name="<?= $mapel['detail'][$info['subject_id']]['title'] ?>" data-class="<?= $class['info'][$info['class']] ?>" data-id="<?= $info['ID'] ?>">Hapus</span></td>
                                                    </tr>
                                            <?Php }
                                            } else {
                                                echo "";
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <script>
                                $("#addNew").on("click", function() {
                                    if ($(".addNew").hasClass("d-none")) {
                                        $(".addNew").removeClass("d-none");
                                        $(this).val("X");
                                    } else {
                                        $(".addNew").addClass("d-none");
                                        $(this).val("Tambah");
                                    }
                                });

                                $("select[name='mapel']").on("change", function() {
                                    var tipe = $(this).find(":selected").attr("data-tipe");
                                    if (tipe == "ekskul") {
                                        $("select[name='class']").hide();
                                    } else {
                                        $("select[name='class']").show();
                                    }
                                });

                                $("#addNewMapel").on("click", function() {
                                    var clsss = $("select[name='class']").val(),
                                        mapel = $("select[name='mapel']").val(),
                                        tipe = $("select[name='mapel']").find(":selected").attr("data-tipe");

                                    $.ajax({
                                        url: "<?= base_url("endpoint/index.php") ?>",
                                        type: "POST",
                                        data: {
                                            "action": "crud/teacher",
                                            "activity": "mapel_add",
                                            "nip": "<?= $data['nip'] ?>",
                                            "class": clsss,
                                            "mapel": mapel,
                                            "tipe": tipe
                                        },
                                        success: function(data) {
                                            var response = JSON.parse(data);
                                            console.log(data);
                                            if (response.OK == true) {
                                                alert("Berhasil menambahkan mapel.");
                                                window.location.reload();
                                            } else {
                                                alert("Gagal menambahkan mapel.");
                                            }
                                        },
                                        error: function(data) {}
                                    });
                                });

                                $(".mapel-delete").on("click", function() {
                                    var ID = $(this).data("id"),
                                        name = $(this).data("name"),
                                        clas = $(this).data("class");
                                    if (confirm("Apakah Anda yakin menghapus " + name + " ( " + clas + " ) ini dari guru ini?")) {
                                        $.ajax({
                                            url: "<?= base_url("endpoint/index.php") ?>",
                                            type: "POST",
                                            data: {
                                                "action": "crud/teacher",
                                                "activity": "mapel_delete",
                                                "ID": ID
                                            },
                                            success: function(data) {
                                                var response = JSON.parse(data);
                                                console.log(data);
                                                if (response.OK == true) {
                                                    $(`tr[data-id=${ID}]`).remove();
                                                } else {
                                                    alert("Gagal menghapus mapel.");
                                                }
                                            },
                                            error: function(data) {}
                                        });
                                    }
                                });
                            </script>
                        </div>
            <?Php
                    }
                }
            } ?>
        </div>
    </div>
<?Php } ?>