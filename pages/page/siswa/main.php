<?Php if (in_array($user['role'], ["admin", "teacher"])) { ?>
    <div class="container-fluid">
        <div class="row">
            <?php if (!isset($_GET['action'])) { ?>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header d-flex space-between">
                            <h4 class="title">Daftar Siswa</h4>
                            <?= $user['role'] == "teacher" && count($guru[$unme]['subject']) > 0 ? abtn(base_url("siswa?action=addToEkskul"), "btn-info btn-fill", "+ Murid Ekskul") : false ?>
                            <?= $user['role'] == "admin" ? "<div style='display: flex;gap: 5px;'><a class='btn btn-sm btn-danger' href='" . base_url('static/files/templates/import_siswa.xlsx') . "' download><i class='fa fa-download'></i></a> <button class='btn btn-sm btn-success import'><i class='fa fa-upload'></i></button> <a class='btn btn-sm btn-info btn-fill' href='" . base_url("siswa?action=add") . "'>Tambah Siswa</a></div>" : false ?>
                        </div>
                        <?php
                        $limit = 10; // Jumlah item per halaman
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $offset = ($page - 1) * $limit;

                        // Hitung total data
                        $total_query = $user['role'] == "teacher"
                            ? query("siswa", "WHERE class IN ($mclasses) AND nis NOT IN ($deactivatedU)")
                            : query("siswa", "WHERE nis NOT IN ($deactivatedU)");
                        $total_rows = mysqli_num_rows($total_query);
                        $total_pages = ceil($total_rows / $limit);

                        // Ambil data dengan pagination
                        $getsiswa = $user['role'] == "teacher"
                            ? query("siswa", "WHERE class IN ($mclasses) AND nis NOT IN ($deactivatedU) ORDER BY class ASC LIMIT $limit OFFSET $offset")
                            : query("siswa", "WHERE nis NOT IN ($deactivatedU) ORDER BY class ASC LIMIT $limit OFFSET $offset");
                        ?>

                        <div class="content">
                            <?Php if ($user['role'] == "admin") { ?>
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

                                            formData.append("action", "crud/siswa");
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
                            <?Php } ?>
                            <div class="tableHolder">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>NIS</th>
                                            <th>Nama Siswa</th>
                                            <?= isset($_GET['ekskul']) ? "<th>Ekskul</th>" : "" ?>
                                            <th>Kelas</th>
                                            <?= $user['role'] == "admin" ? "<th>Edit</th>" : "" ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (mysqli_num_rows($getsiswa) > 0) {
                                            while ($data = mysqli_fetch_array($getsiswa)) {
                                                $edit = '<td><a href="' . base_url("siswa?action=edit&ID=" . $data['ID']) . '" class="btn btn-sm btn-warning">Edit</a></td>';
                                                $look = base_url("siswa?action=lookup&ID=" . $data['ID']);
                                        ?>
                                                <tr class="lists" data-class='<?= $data['class'] ?>'>
                                                    <td><?= $data['nis'] ?></td>
                                                    <td><?= $user['role'] == "admin" ? '<a href="' . $look . '" target="_blank">' . $siswa[$data['nis']]['name'] . '</a>' : $siswa[$data['nis']]['name'] ?></td>
                                                    <td><?= $class['info'][$data['class']] ?? "<i>NULL</i>" ?></td>
                                                    <?= $user['role'] == "admin" ? $edit : "" ?>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='4'>Belum ada data siswa.</td></tr>";
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
                <?php } else {
                if ($_GET['action'] == "add") { ?>
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Tambah Siswa</h4>
                            </div>
                            <div class="content">
                                <form>
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <div class="form-group">
                                                <label>Nama Lengkap</label>
                                                <input type="text" class="form-control" name="name" placeholder="Nama Siswa" />
                                            </div>
                                        </div>
                                        <div class="col-lg-5">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Jenis Kelamin</label>
                                                <select name="gender" class="form-control">
                                                    <option value="">- Pilih -</option>
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
                                                <input type="text" class="form-control" name="birthloc" placeholder="Tempat Lahir" />
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
                                                <input type="text" class="form-control" name="phone" placeholder="-" />
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="form-group">
                                                <label>Alamat Email</label>
                                                <input type="text" class="form-control" name="email" placeholder="-" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <textarea name="address" rows="3" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-5">
                                            <div class="form-group">
                                                <label>NIS</label>
                                                <input type="text" name="nis" class="form-control text-center" />
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="form-group">
                                                <label>Kelas</label>
                                                <select name="kelas" class="form-control">
                                                    <option value="">- Pilih -</option>
                                                    <?Php foreach ($class['array'] as $classs) {
                                                        echo '<option value="' . $classs['ID'] . '">' . $classs['name'] . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label>Kata sandi</label>
                                            <input type="password" class="form-control" name="password" placeholder="Kata sandi" style="display: block;margin-bottom: 10px;" />
                                            <input type="password" class="form-control" name="rassword" placeholder="Ketik ulang kata sandi" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="button" class="btn pull-right btn-sm btn-success" value="Simpan" disabled />
                                        </div>
                                    </div>
                                    <p class="mt-3"></p>
                                    <script>
                                        setInterval(function() {
                                            $('input[type="text"], input[type="date"], input[type="password"], select').each(function() {
                                                if ($(this).val() === '') {
                                                    condition = false;
                                                    return false;
                                                } else {
                                                    if (condition = true) {
                                                        condition = true;
                                                    }
                                                }
                                            });

                                            if (condition == true) {
                                                $("input[type='button']").removeAttr("disabled");
                                            } else {
                                                $("input[type='button']").attr("disabled", true);
                                            }
                                        }, 500);

                                        $("input[type='button']").on("click", function() {
                                            var password = $("input[name='password']").val(),
                                                rassword = $("input[name='rassword']").val();

                                            if (password == rassword) {
                                                var form = $("form")[0],
                                                    formData = new FormData(form);

                                                formData.append("action", "crud/siswa");
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
                                                            window.location.replace("<?= base_url("siswa") ?>");
                                                        } else {
                                                            alert("Gagal menambahkan user");
                                                        }
                                                    },
                                                    error: function(data) {}
                                                });
                                            } else {
                                                alert("Password tidak sama");
                                            }
                                        });
                                    </script>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?Php } elseif (($_GET['action']) == "edit" && isset($_GET['ID']) && $user['role'] == "admin") {
                    $ID = $_GET['ID'];
                    $check = select("siswa", "ID='$ID'");
                    if (mysqli_num_rows($check) == 1) {
                        $data = mysqli_fetch_array($check);
                    ?>
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title">Edit Data Siswa : <?= $data['nis'] ?></h4>
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
                                                    <label>NIS</label>
                                                    <input type="text" class="form-control text-center" placeholder="-" value="<?= $data['nis'] ?>" disabled />
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <div class="form-group">
                                                    <label>Kelas</label>
                                                    <select name="kelas" id="" class="form-control">
                                                        <option value="0">Pilih Kelas</option>
                                                        <?Php for ($i = 0; $i <= count($class['array']) - 1; $i++) { ?>
                                                            <option value="<?= $class['array'][$i]['ID'] ?>" <?= $data['class'] == $class['array'][$i]['ID'] ? "selected" : "" ?>><?= $class['array'][$i]['name'] ?></option>
                                                        <?Php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="row">
                            <div class="col-12">
                                <h4 class="title">Ekskul</h4>
                                <div class="mt-3">
                                <?Php
                                // $tekskul = $siswa[$data['nis']]['ekskul'] ?? ["X","Z"];
                                // for($i = 0;$i<=count($mapel['tipe']['ekskul'])-1;$i++) {
                                // $thiss = $mapel['tipe']['ekskul'][$i];
                                // $nmmp = "juieahao12o3i0".$i; 
                                ?>
                                        <input type='checkbox' name="ekskul[]" id='<?= "" // $nmmp 
                                                                                    ?>' value='<?= "" // $thiss['ID'] 
                                                                                                ?>' <?= "" // in_array($thiss['ID'], $tekskul) ? "checked" : "" 
                                                                                                    ?>> <label for='<?= "" //$nmmp 
                                                                                                                                    ?>'><?= "" // $mapel['tipe']['ekskul'][$i]['NAME'] 
                                                                                                                                                                ?></label><br/><?Php
                                                                                                                                                                                                                                            // }
                                                                                                                                                                                                                                            ?>
                                </div>
                            </div>
                        </div> -->
                                        <div class="mt-3 clearfix"></div>
                                        <div class="d-flex space-between">
                                            <?Php
                                            // $deacti = '<input type="button" class="activatio deact btn btn-sm btn-danger" value="Nonaktifkan" />';
                                            // $active = '<input type="button" class="activatio react btn btn-sm btn-warning" value="Aktifkan" />';
                                            // in_array($data['nis'], $users['deactivated']) ? $active : $deacti;
                                            ?>
                                            <span></span>
                                            <input type="button" class="btn btn-sm btn-success" value="Perbarui" />
                                        </div>
                                        <p class="mt-3"></p>
                                        <script>
                                            $("input[type='button']").on("click", function() {
                                                var form = $("form")[0],
                                                    formData = new FormData(form);
                                                formData.append("action", "crud/siswa");
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
                                                            alert("Berhasil memperbarui informasi.");
                                                        }
                                                    },
                                                    error: function(data) {}
                                                });
                                            });
                                        </script>
                                    </form>
                                </div>
                            </div>
                        </div><?Php }
                        } elseif ($_GET['action'] == "addToEkskul" && $user['role'] == "admin") { ?>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Tambah Murid Ekskul</h4>
                            </div>
                            <div class="content">
                                <form>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label>NIS</label>
                                            <input type="text" class="form-control" name="nis" placeholder="123456789" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label>Pilih Ekskul</label>
                                            <select name="ekskul" class="form-control">
                                                <option value="">- Pilih -</option>
                                                <?Php for ($i = 0; $i <= count($mapel['tipe']['ekskul']) - 1; $i++) {
                                                    $mpl = $mapel['tipe']['ekskul'][$i];
                                                    if (in_array($mpl['ID'], $guru[$unme]['subject'])) { ?><option value="<?= $mpl['ID'] ?>"><?= $mpl['NAME'] ?></option><?Php }
                                                                                                                                                                    } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="button" class="btn btn-sm btn-info btn-fill" value="TAMBAHKAN" />
                                </form>
                                <script>
                                    $("form input[type='button']").on("click", function() {
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
                                                    window.location.replace("<?= base_url("siswa") ?>");
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
                    <?Php } elseif ($_GET['action'] == "lookup" && isset($_GET['ID'])) {
                            $ID = $_GET['ID'];
                            $check = select("siswa", "ID='$ID'");
                            if (mysqli_num_rows($check) == 1) {
                                $data = mysqli_fetch_array($check);
                    ?>
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title">Data Siswa : <?= $data['nis'] ?></h4>
                                </div>
                                <div class="content">
                                    <form>
                                        <div class="row">
                                            <div class="col-lg-7">
                                                <div class="form-group">
                                                    <label>Nama Lengkap</label>
                                                    <input type="text" class="form-control" placeholder="-" value="<?= $data['full_name'] ?>" disabled />
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Jenis Kelamin</label>
                                                    <select id="" class="form-control" disabled>
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
                                                    <input type="text" class="form-control" placeholder="-" value="<?= $data['birthloc'] ?>" disabled />
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Tanggal Lahir</label>
                                                    <input type="date" class="form-control" value="<?= $data['birthdate'] ?>" disabled />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label>No. Telpon</label>
                                                    <input type="text" class="form-control" value="<?= $data['phone'] ?>" disabled />
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <div class="form-group">
                                                    <label>Alamat Email</label>
                                                    <input type="text" class="form-control" value="<?= $data['email'] ?>" disabled />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <textarea rows="3" class="form-control" readonly><?= $data['address'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label>NIS</label>
                                                    <input type="text" class="form-control text-center" placeholder="-" value="<?= $data['nis'] ?>" disabled />
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <div class="form-group">
                                                    <label>Kelas</label>
                                                    <input type="text" class="form-control" value="<?= $class['detail'][$data['class']] ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="mt-3"></p>
                                    </form>
                                </div>
                            </div>
                        </div>
            <?Php }
                        }
                    } ?>
        </div>
    </div>
<?Php } ?>