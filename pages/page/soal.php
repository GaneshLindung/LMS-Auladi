<?Php if ($user['role'] == "teacher") { ?>
    <div class="container-fluid">
        <?Php if (!isset($_GET['action'])) { ?>
            <div class="d-flex" style="align-items: center;gap: 15px;">
                <span><b style="padding: 9px 15px;border-bottom: 2px solid #888;color: #888;">Tipe Soal :</b></span>
                <select name="tipe" class="form-control" style="max-width: 175px;">
                    <option value="">Pilihan Ganda</option>
                    <option value="essay" <?= isset($_GET['essay']) ? "selected" : "" ?>>Essay</option>
                    <option value="multiple" <?= isset($_GET['multiple']) ? "selected" : "" ?>>Multiple Choice</option>
                    <option value="match" <?= isset($_GET['match']) ? "selected" : "" ?>>Pencocokan</option>
                </select>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header d-flex space-between">
                            <h4 class="title">Paket Soal Ujian</h4>
                            <?Php $typeNow = isset($_GET['essay']) ? "essay" : (isset($_GET['multiple']) ? "multiple" : (isset($_GET['match']) ? "match" : "choice")) ?>
                            <a href="<?= base_url("soal?action=add&type=" . $typeNow) ?>" class="btn btn-sm btn-info btn-fill">+ Tambah</a>
                        </div>
                        <?php
                        $limit = 10; // Jumlah item per halaman
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $offset = ($page - 1) * $limit;

                        // Tentukan tipe soal berdasarkan parameter
                        $type = isset($_GET['essay']) ? "essay" : (isset($_GET['multiple']) ? "multiple" : (isset($_GET['match']) ? "match" : "choice"));

                        // Hitung total data
                        $total_query = query("exam_p", "WHERE type='$type' AND uploader='$unme'");
                        $total_rows = mysqli_num_rows($total_query);
                        $total_pages = ceil($total_rows / $limit);

                        // Ambil data dengan pagination
                        $query = query("exam_p", "WHERE type='$type' AND uploader='$unme' ORDER BY ID DESC LIMIT $limit OFFSET $offset");

                        ?>
                        <div class="content">
                            <table class="table table-hover action table-striped">
                                <thead>
                                    <th>No</th>
                                    <th>Judul Paket</th>
                                    <th>Mata Pelajaran</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    <?php if ($total_rows > 0) {
                                        $no = $offset + 1; // Adjust numbering per page
                                        while ($data = mysqli_fetch_array($query)) { ?>
                                            <tr>
                                                <td><?= $no ?></td>
                                                <td><?= $data['title'] ?></td>
                                                <td><?= $mapel['detail'][$data['subject_id']]['title'] ?></td>
                                                <td>
                                                    <a href="<?= base_url("soal?action=edit&hash=" . $data['hash']) ?>" class="btn btn-sm btn-warning">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                    <?php $no++;
                                        }
                                    } ?>
                                </tbody>
                            </table>

                            <!-- Pagination Controls -->
                            <nav>
                                <ul class="pagination">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item"><a class="page-link" href="?<?= $type ?>=true&page=<?= $page - 1 ?>">Previous</a></li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                            <a class="page-link" href="?<?= $type ?>=true&page=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($page < $total_pages): ?>
                                        <li class="page-item"><a class="page-link" href="?<?= $type ?>=true&page=<?= $page + 1 ?>">Next</a></li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                        <script>
                            $("select[name='tipe']").on("change", function() {
                                var val = $(this).val();
                                if (val == "") {
                                    location.href = "<?= base_url("soal") ?>";
                                } else {
                                    location.href = "<?= base_url("soal?") ?>" + val;
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
            <?Php } else {
            $act = $_GET['action'];
            if ($act == "edit" && isset($_GET['hash']) && isset($soal[$_GET['hash']])) {
                $hash = $_GET["hash"];
                require("soal/style.php"); ?>
                <h3 style="margin: 0px 0px 32px;">Edit Paket Soal</h3>
                <div class="row">
                    <?Php require("soal/editTitle.php"); ?>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header d-flex space-between">
                                <h4 class="title">Manajemen Soal</h4>
                                <div style="display: flex;gap: 5px;">
                                    <form id="uploadExcel" enctype="multipart/form-data"><input type="file" name="excelFile" style="display: none;" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" /></form>
                                    <?Php if ($act == "edit" && isset($soal[$_GET['hash']])) {
                                        if ($soal[$_GET['hash']]['tipe'] == "choice") {
                                            echo "<a class='btn btn-sm btn-danger' href='" . base_url('static/files/templates/bank_pilganda.xlsx') . "' download><i class='fa fa-download'></i></a>";
                                        } elseif ($soal[$_GET['hash']]['tipe'] == "essay") {
                                            echo "<a class='btn btn-sm btn-danger' href='" . base_url('static/files/templates/bank_essay.xlsx') . "' download><i class='fa fa-download'></i></a>";
                                        }
                                    } ?>
                                    <button class="uploadExcel btn btn-sm btn-success btn-fill"><i class="fa fa-upload"></i></button>
                                    <input type="button" class="btn-add btn btn-sm btn-info btn-fill" value="+ Tambah" />
                                </div>
                            </div>
                            <div class="content">
                                <?Php if (isset($soal[$hash]) && in_array($soal[$hash]['tipe'], ["choice", "essay", "multiple", "match"])) {
                                    $SOL = $soal[$hash];
                                    $GET = $SOL['tipe'] == "choice" ? query("examqc", "WHERE package='$hash' ORDER BY ID ASC") : ($SOL['tipe'] == "essay" ? query("examqe", "WHERE package='$hash' ORDER BY ID ASC") : ($SOL['tipe'] == "multiple" ? query("examqm", "WHERE package='$hash' ORDER BY ID ASC") : ($SOL['tipe'] == "match" ? query("examqp", "WHERE package='$hash' ORDER BY ID ASC") : select("examqc", "package='1'"))));
                                    if ($SOL['tipe'] == "choice") {
                                        require("soal/editChoice.php");
                                    } elseif ($SOL['tipe'] == "essay") {
                                        require("soal/editEssay.php");
                                    } elseif ($SOL['tipe'] == "multiple") {
                                        require("soal/editMultiple.php");
                                    } elseif ($SOL['tipe'] == "match") {
                                        require("soal/editMatch.php");
                                    }
                                } ?>
                            </div>
                            <script>
                                $(".uploadExcel").on("click", function() {
                                    $("input[name='excelFile']").click();
                                });
                                $("input[name='excelFile']").on("change", function() {
                                    file = this.files[0];
                                    if (file && confirm("Yakin ingin mengupload file ini?")) {
                                        var form = $("form#uploadExcel")[0],
                                            formData = new FormData(form);

                                        formData.append("action", "crud/ujian/create");
                                        formData.append("activity", "uploadExcel");
                                        formData.append("type", "<?= $SOL['tipe'] ?>");
                                        formData.append("hash", "<?= $hash ?>");

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
                        </div>
                    </div>
                </div>
        <?Php } elseif ($act == "add") {
                require("soal/addPackage.php");
            }
        } ?>
    </div>
<?Php } ?>