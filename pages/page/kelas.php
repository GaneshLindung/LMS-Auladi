<?Php if ($user['role'] == "admin") { ?>
    <div class="container-fluid">
        <div class="row">
            <?Php if (!isset($_GET['action'])) { ?>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header d-flex space-between" style="align-items: center;">
                            <h4 class="title">Daftar Kelas</h4>
                            <a class="btn btn-sm btn-primary" href="<?= base_url("kelas?action=add") ?>">Tambah Kelas</a>
                        </div>
                        <?php
                        $limit = 10; // Jumlah data per halaman
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $offset = ($page - 1) * $limit;

                        // Hitung total data
                        $total_query = query("class", "ORDER BY ID ASC");
                        $total_rows = mysqli_num_rows($total_query);
                        $total_pages = ceil($total_rows / $limit);

                        // Ambil data sesuai pagination
                        $getsiswa = query("class", "ORDER BY ID ASC LIMIT $limit OFFSET $offset");
                        ?>

                        <div class="content">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Kelas</th>
                                        <th style="text-align: right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($getsiswa) > 0) {
                                        while ($data = mysqli_fetch_array($getsiswa)) { ?>
                                            <tr>
                                                <td><?= $data['class_name'] ?></td>
                                                <td style="text-align: right;">
                                                    <a type="button" href="<?= base_url("kelas?action=edit&ID=" . $data['ID']) ?>" class="btn btn-sm btn-warning editClass">INFO</a>
                                                </td>
                                            </tr>
                                    <?php }
                                    } else {
                                        echo "<tr><td colspan='2'>Belum ada kelas yang tersedia.</td></tr>";
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
                <?Php } else {
                if ($_GET['action'] == "add") { ?>
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Tambah Kelas</h4>
                            </div>
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Nama Kelas</label>
                                            <input type="text" class="form-control" name="className" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 d-flex space-between">
                                        <input type="button" class="btn btn-sm btn-danger" id="deleteClass" value="Hapus Kelas">
                                        <input type="button" class="btn btn-sm btn-success" id="addNew" value="Simpan">
                                    </div>
                                </div>
                                <p class="mt-3"></p>
                            </div>
                            <script>
                                $("input#addNew").on("click", function() {
                                    var namaKelas = $("input[name='className']").val();
                                    if (namaKelas != "") {
                                        $.ajax({
                                            url: "<?= base_url("endpoint/index.php") ?>",
                                            type: "POST",
                                            data: {
                                                "action": "crud/kelas",
                                                "activity": "add",
                                                "title": namaKelas
                                            },
                                            success: function(data) {
                                                var response = JSON.parse(data);
                                                console.log(data);
                                                if (response.OK == true) {
                                                    alert("Berhasil");
                                                    window.location.replace("<?= base_url("kelas") ?>");
                                                } else {
                                                    alert("Gagal");
                                                }
                                            },
                                            error: function(data) {}
                                        });
                                    }
                                });
                            </script>
                        </div>
                    </div>
                    <?Php } elseif ($_GET['action'] == "edit" && isset($_GET['ID'])) {
                    $ID = $_GET['ID'];
                    $select = select("class", "ID='$ID'");
                    if (mysqli_num_rows($select) == 1) {
                        $data = mysqli_fetch_array($select);
                    ?>
                        <div class="col-12">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title">Edit Kelas</h4>
                                </div>
                                <div class="content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Nama Kelas</label>
                                                <input type="text" class="form-control" name="className" value="<?= $data['class_name'] ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 d-flex space-between">
                                            <input type="button" class="btn btn-sm btn-danger" id="deleteClass" value="Hapus Kelas" />
                                            <input type="button" class="btn btn-sm btn-success" id="saveChanges" value="Simpan" />
                                        </div>
                                    </div>
                                    <script>
                                        $("input#saveChanges").on("click", function() {
                                            var namaKelas = $("input[name='className']").val();
                                            $.ajax({
                                                url: "<?= base_url("endpoint/index.php") ?>",
                                                type: "POST",
                                                data: {
                                                    "action": "crud/kelas",
                                                    "activity": "edit",
                                                    "ID": <?= $_GET['ID'] ?>,
                                                    "title": namaKelas
                                                },
                                                success: function(data) {
                                                    var response = JSON.parse(data);
                                                    console.log(data);
                                                    if (response.OK == true) {
                                                        alert("Berhasil memperbarui informasi.");
                                                    } else {
                                                        alert("Gagal memperbarui informasi.");
                                                    }
                                                },
                                                error: function(data) {}
                                            });
                                        });

                                        $("input#deleteClass").on("click", function() {
                                            if (confirm("Apakah Anda yakin untuk menghapus kelas ini?")) {
                                                $.ajax({
                                                    url: "<?= base_url("endpoint/index.php") ?>",
                                                    type: "POST",
                                                    data: {
                                                        "action": "crud/kelas",
                                                        "activity": "delete",
                                                        "ID": <?= $_GET['ID'] ?>
                                                    },
                                                    success: function(data) {
                                                        var response = JSON.parse(data);
                                                        console.log(data);
                                                        if (response.OK == true) {
                                                            alert("Berhasil menghapus kelas");
                                                            window.location.replace("<?= base_url("kelas") ?>");
                                                        } else {
                                                            alert(response.msg);
                                                        }
                                                    },
                                                    error: function(data) {}
                                                });
                                            }
                                        });
                                    </script>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <div class="header d-flex space-between">
                                    <h4 class="title">Daftar Siswa</h4>
                                    <div style="display: flex;gap: 5px;">
                                        <a class='btn btn-sm btn-danger' href='<?= base_url('static/files/templates/import_murid.xlsx') ?>' download><i class='fa fa-download'></i></a>
                                        <button class="btn btn-sm btn-success import"><i class="fa fa-upload"></i></button>
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

                                            formData.append("action", "crud/kelas");
                                            formData.append("activity", "upload");
                                            formData.append("ID", "<?= $_GET['ID'] ?>");

                                            $.ajax({
                                                url: "<?= base_url("endpoint/index.php") ?>",
                                                type: "POST",
                                                data: formData,
                                                processData: false,
                                                contentType: false,
                                                success: function(data) {
                                                    var response = JSON.parse(data);
                                                    // window.location.reload();
                                                    console.log(data);
                                                },
                                                error: function(data) {}
                                            });
                                        }
                                    });
                                </script>
                                <div class="content">
                                    <?Php $get = query("siswa", "WHERE class='$ID'");
                                    if (mysqli_num_rows($get) > 0) {
                                        while ($datax = mysqli_fetch_array($get)) { ?>
                                            <div class="murid" style="border: 1px solid #EFEFEF;margin-bottom: 4px;" data-siswa="<?= $datax['ID'] ?>">
                                                <div class="content d-flex space-between" style="align-items: center;">
                                                    <?= $datax['full_name'] ?>
                                                    <div>
                                                        <a href="<?= base_url("siswa?action=edit&ID=" . $datax['ID']) ?>" class="btn btn-sm btn-warning" target="_blank">Edit</a>
                                                        <button class="btn btn-sm btn-danger btn-fill" onclick="deleteSiswa('<?= $datax['ID'] ?>', '<?= $datax['full_name'] ?>')">Hapus dari Kelas</button>
                                                    </div>
                                                </div>
                                            </div>
                                    <?Php }
                                    } ?>
                                    <button class="btn btn-sm btn-danger btn-fill mt-3" onclick="deleteAllSiswaFromClass()">Hapus Semua Siswa</button>
                                </div>
                                <script>
                                    var ID_KELAS = "<?= $_GET['ID'] ?>";

                                    function deleteSiswa(id_siswa, name) {
                                        if (confirm("Apakah Anda yakin ingin menghapus '" + name + "' dari kelas ini?")) {
                                            $.ajax({
                                                url: "<?= base_url("endpoint/index.php") ?>",
                                                type: "POST",
                                                data: {
                                                    "action": "crud/siswa",
                                                    "activity": "deleteFromClass",
                                                    "siswa": id_siswa
                                                },
                                                success: function(data) {
                                                    var response = JSON.parse(data);
                                                    if (response.OK = true) {
                                                        $(`.murid[data-siswa=${id_siswa}]`).remove();
                                                    }
                                                    console.log(data);
                                                },
                                                error: function(data) {}
                                            });
                                        }
                                    }

                                    function deleteAllSiswaFromClass() {
                                        var randomt = "<?= strtoupper(randomtoken("10")) ?>";
                                        var convirm = prompt("Ketik '" + randomt + "' untuk melanjutkan opearasi penghapusan semua siswa dari kelas ini");
                                        if (convirm == randomt) {

                                            $.ajax({
                                                url: "<?= base_url("endpoint/index.php") ?>",
                                                type: "POST",
                                                data: {
                                                    "action": "crud/kelas",
                                                    "activity": "deleteAllSiswaFromClass",
                                                    "kelas": ID_KELAS
                                                },
                                                success: function(data) {
                                                    var response = JSON.parse(data);
                                                    if (response.OK = true) {
                                                        alert("BERHASIL menghapus semua siswa dari kelas");
                                                        window.location.reload();
                                                    } else {
                                                        alert("GAGAL menghapus semua siswa dari kelas");
                                                    }
                                                    console.log(data);
                                                },
                                                error: function(data) {}
                                            });

                                        } else {
                                            if (confirm("Tidak sesuai, coba lagi?")) {
                                                deleteAllSiswaFromClass();
                                            }
                                        }
                                    }
                                </script>
                            </div>
                            <div class="card mt-3">
                                <div class="header">
                                    <h4 class="title">Daftar Mata Pelajaran</h4>
                                </div>
                                <div class="content">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Mata Pelajaran</th>
                                                <th>Guru</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?Php $get = query("subjetc", "WHERE class='$ID'");
                                            if (mysqli_num_rows($get) > 0) {
                                                while ($datax = mysqli_fetch_array($get)) { ?>
                                                    <tr>
                                                        <td><a href="<?= base_url("mapel?action=edit&mapel_id=" . $datax['subject_id']) ?>" target="_blank"><?= $mapel['detail'][$datax['subject_id']]['title'] ?></a></td>
                                                        <td><a href="<?= base_url("guru?action=edit&ID=" . $guru[$datax['nip']]['ID']) ?>" target="_blank"><?= $guru[$datax['nip']]['name'] ?></a></td>
                                                    </tr>
                                            <?Php }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
            <?Php }
                }
            } ?>
        </div>
    </div>
<?Php } ?>