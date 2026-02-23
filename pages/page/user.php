<?Php if ($user['role'] == "admin") { ?>
    <div class="container-fluid">
        <div class="row">
            <?php if (!isset($_GET['action'])) { ?>
                <div class="col-md-12">
                    <a href="<?= base_url("user?action=activation") ?>" class="btn btn-outline-success">Aktivasi Pengguna ( <?= mysqli_num_rows(query("rsiswa", "WHERE status='0'")) ?> )</a>
                    <div class="card mt-3">
                        <div class="header d-flex space-between">
                            <h4 class="title">Daftar Pengguna</h4>
                            <select id="filters" class="form-control" style="margin: 0px 0px 16px;max-width: 350px;">
                                <option value="all">Semua</option>
                                <option value="admin">Admin</option>
                                <option value="teacher">Guru</option>
                                <option value="student">Murid</option>
                            </select>
                        </div>
                        <style>
                            tr.users span.admin:before {
                                content: "Admin";
                                padding: 2px 10px;
                                border-radius: 4px;
                                color: #555;
                                background: #03fc90;
                            }

                            tr.users span.teacher:before {
                                content: "Guru";
                                padding: 2px 10px;
                                border-radius: 4px;
                                color: #FFF;
                                background: #fc7f03;
                            }

                            tr.users span.student:before {
                                content: "Murid";
                                padding: 2px 10px;
                                border-radius: 4px;
                                color: #FFF;
                                background: #037ffc;
                            }

                            i.active {
                                color: #adedcb;
                            }

                            i.deactive {
                                color: #edadad;
                            }
                        </style>
                        <?php
                        $limit = 10; // Jumlah data per halaman
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $offset = ($page - 1) * $limit;

                        // Hitung total data
                        $total_query = query("user", "ORDER BY ID ASC");
                        $total_rows = mysqli_num_rows($total_query);
                        $total_pages = ceil($total_rows / $limit);

                        // Ambil data sesuai pagination
                        $guser = query("user", "ORDER BY ID ASC LIMIT $limit OFFSET $offset");
                        ?>

                        <div class="content">
                            <div class="tableHolder">
                                <table class="table table-hover table-striped action">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Name</th>
                                            <th>Level</th>
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (mysqli_num_rows($guser) > 0) {
                                            while ($data = mysqli_fetch_array($guser)) {
                                                $name   = $data['role'] == "teacher" ? $guru[$data['username']]['name'] : ($data['role'] == "student" ? $siswa[$data['username']]['name'] : "ADMIN");
                                                $level  = $data['role'] == "teacher" ? "teacher" : ($data['role'] == "student" ? "student" : "admin");
                                                $status = $data['status'] == 1 ? 'active' : 'deactive';
                                                echo "<tr class='users' data-role='" . $data['role'] . "'>
                                <td>" . $data['username'] . "</td>
                                <td><i class='fa fa-circle " . $status . "'></i> " . $name . "</td>
                                <td><span class='" . $level . "'></span></td>
                                <td><a href='" . base_url("user?action=edit&ID=" . $data['ID']) . "' class='btn btn-sm btn-warning'>Edit</a></td>
                            </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='4'>Belum ada data user.</td></tr>";
                                        } ?>
                                    </tbody>
                                </table>
                            </div>

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

                        <script>
                            $("select#filters").on("change", function() {
                                var val = $(this).val();
                                $("tr.users").addClass("d-none");
                                if (val != "all") {
                                    $(`tr.users[data-role=${val}]`).removeClass("d-none");
                                } else {
                                    $("tr.users").removeClass("d-none");
                                }
                            });
                        </script>

                    </div>
                </div>
                <?php } else {
                if ($_GET['action'] == "activation") { ?>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Request Aktivasi Siswa</h4>
                                <?= isset($_GET['rejected']) ? "<p class='category'>[ Ditolak ]</p>" : "" ?>
                            </div>
                            <div class="content">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>NIS</th>
                                            <th>Nama Lengkap</th>
                                            <th>Kelas</th>
                                            <th>No. Telp</th>
                                            <th>E-mail</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $guser = !isset($_GET['rejected']) ? query("rsiswa", "WHERE status='0' ORDER BY ID DESC") : query("rsiswa", "WHERE status='1' ORDER BY ID DESC");
                                        $no = 1;
                                        while ($data = mysqli_fetch_array($guser)) {
                                            echo "<tr><td>" . $no . "</td><td>" . $data['nis'] . "</td><td>" . $data['full_name'] . "</td><td>" . $class['info'][$data['class']] . "</td><td>" . $data['phone'] . "</td><td>" . $data['email'] . "</td><td><a href='" . base_url("user?action=detail&ID=" . $data['ID']) . "' type='button' class='btn btn-sm btn-success activate'>Selengkapnya</a></tr>";
                                            $no++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div class="footer">
                                    <?Php if (!isset($_GET['rejected'])) {
                                        echo '<a href=' . base_url("user?action=activation&rejected") . '><i class="fa fa-clock-o"></i> Ditolak</a>';
                                    } else {
                                        echo '<a href=' . base_url("user?action=activation") . '><i class="fa fa-angle-left"></i> Kembali</a>';
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } elseif ($_GET['action'] == "detail" && isset($_GET['ID'])) { ?>
                    <div class="col-md-3">
                        <a href="<?= base_url("user?action=activation") ?>">
                            < Kembali</a>
                                <div class="card mt-3">
                                    <div class="header">
                                        <h4 class="title">REQ : Informasi Siswa</h4>
                                    </div>
                                    <div class="content">
                                        <?Php
                                        $ID = $_GET['ID'];
                                        $query = select("rsiswa", "ID='$ID'");
                                        if (mysqli_num_rows($query) == 1) {
                                            $data = mysqli_fetch_array($query);
                                        ?>
                                            <style>
                                                table#datasiswa {
                                                    margin-bottom: 16px;
                                                }

                                                table#datasiswa tr td:first-child {
                                                    font-weight: bold;
                                                }

                                                table#datasiswa tr td:nth-child(2) {
                                                    padding: 4px 16px;
                                                }
                                            </style>
                                            <table id="datasiswa">
                                                <tr>
                                                    <td>Nama</td>
                                                    <td>:</td>
                                                    <td><?= $data['full_name'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>NIS</td>
                                                    <td>:</td>
                                                    <td><?= $data['nis'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Kelas</td>
                                                    <td>:</td>
                                                    <td><?= $class['info'][$data['class']] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>TTL</td>
                                                    <td>:</td>
                                                    <td><?= $data['birthloc'] ?>, <?= $data['birthdate'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>No. Telpon</td>
                                                    <td>:</td>
                                                    <td><?= $data['phone'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>E-Mail</td>
                                                    <td>:</td>
                                                    <td><?= $data['email'] ?></td>
                                                </tr>
                                            </table>
                                            <div style="display: flex;gap: 5px;">
                                                <input type='button' class='action btn btn-sm btn-success' data-action="accept" value='Aktifkan' />
                                                <input type='button' class='action btn btn-sm btn-danger' data-action="reject" value='Tolak' /></td>
                                            </div>
                                            <script>
                                                function setUser(action, id) {
                                                    $.ajax({
                                                        url: "<?= base_url("endpoint/index.php") ?>",
                                                        type: "POST",
                                                        data: {
                                                            "action": "auth/rsiswa",
                                                            "activity": action,
                                                            "ID": id
                                                        },
                                                        success: function(data) {
                                                            var response = JSON.parse(data);
                                                            console.log(data);
                                                            if (response.OK == true) {
                                                                if (response.t == "accepted") {
                                                                    alert("Berhasil menerima siswa.");
                                                                    window.location.replace("<?= base_url("siswa") ?>");
                                                                } else if (response.t == "rejected") {
                                                                    alert("Berhasil menolak siswa.");
                                                                    window.location.replace("<?= base_url("user?action=activation") ?>");
                                                                }
                                                            } else {
                                                                alert("Gagal! Silahkan coba lagi..");
                                                            }
                                                        },
                                                        error: function(data) {}
                                                    });
                                                }

                                                $("input[type='button'].action").on("click", function() {
                                                    var action = $(this).data("action"),
                                                        ID = <?= $_GET['ID'] ?>;

                                                    if (confirm("Apakah Anda yakin '" + action + "' user ini?")) {
                                                        var request = setUser(action, ID);
                                                    } else {
                                                        alert("Aksi dibatalkan");
                                                    }
                                                });
                                            </script>
                                        <?Php
                                        } else {
                                            echo "<p class='alert alert-danger'>Ups, permintaan pendaftaran tidak ditemukan!</p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                    </div>
                    <?php } elseif ($_GET['action'] == "edit" && isset($_GET['ID'])) {
                    $ID = $_GET['ID'];
                    $check = select("user", "ID='$ID'");
                    if (mysqli_num_rows($check) == 1) {
                        $data = mysqli_fetch_array($check);
                        $edit = $data['role'] == "teacher" ? "<a href='" . base_url('guru?action=edit&ID=' . $guru[$data['username']]['ID']) . "'>Edit Data Guru</a>" : ($data['role'] == "student" ? "<a href='" . base_url('siswa?action=edit&ID=' . $siswa[$data['username']]['ID']) . "'>Edit Data Siswa</a>" : "");
                    ?>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title">Edit User</h4>
                                    <p class="category"><?= $data['role'] == "teacher" ? "Guru" : "Murid" ?></p>
                                </div>
                                <div class="content">
                                    <form>
                                        <div class="row">
                                            <div class="col-lg-7">
                                                <div class="form-group">
                                                    <label>Nama Lengkap</label>
                                                    <input type="text" class="form-control" placeholder="-" value="<?= $data['role'] == "teacher" ? $guru[$data['username']]['name'] : ($data['role'] == "student" ? $siswa[$data['username']]['name'] : "admin")  ?>" readonly />
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select name="status" class="form-select form-control">
                                                        <option value="1" <?= $data['status'] == 1 ? "selected" : "" ?>>Aktif</option>
                                                        <option value="0" <?= $data['status'] == 0 ? "selected" : "" ?>>Non-Aktif</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="pwChanger">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label>Ubah Katasandi</label>
                                                        <input type="password" name="password" class="form-control" placeholder="Katasandi baru" />
                                                        <input type="password" name="retypepw" class="form-control" placeholder="Ketik ulang katasandi baru" style="margin-top: 10px;" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="d-flex space-between">
                                            <?= $edit ?>
                                            <input type="button" id="saveChanges" class="btn btn-sm btn-success" value="Simpan" />
                                        </div>
                                        <div class="mt-3"></div>
                                        <script>
                                            var currentUsername = "<?= $data['username'] ?>";

                                            $("input#saveChanges").on("click", function() {
                                                var cond = true,
                                                    form = $("form")[0],
                                                    formData = new FormData(form),
                                                    password = $("input[name='password']").val(),
                                                    passworr = $("input[name='retypepw']").val();

                                                if (password == passworr) {
                                                    var cond = true;
                                                } else {
                                                    var cond = false;
                                                    alert("Password tidak sesuai");
                                                }

                                                if (cond == true) {
                                                    formData.append("action", "crud/user");
                                                    formData.append("activity", "updateUser");
                                                    formData.append("ID", <?= $_GET['ID'] ?>);
                                                    $.ajax({
                                                        url: "<?= base_url("endpoint/index.php") ?>",
                                                        type: "POST",
                                                        data: formData,
                                                        processData: false,
                                                        contentType: false,
                                                        success: function(data) {
                                                            var response = JSON.parse(data);
                                                            if (response.OK == true) {
                                                                alert("Berhasil mengubah informasi.");
                                                                $("input[type='password']").val("");
                                                            } else {
                                                                alert("Gagal mengubah informasi");
                                                            }
                                                        },
                                                        error: function(data) {}
                                                    });
                                                }
                                            });
                                        </script>
                                    </form>
                                </div>
                            </div>
                        <?php
                    }
                        ?>
                        </div>
                <?php }
            } ?>
        </div>
    </div>
<?Php } ?>