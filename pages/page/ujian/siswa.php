<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="header d-flex space-between">
                    <h4 class="title">Daftar Ujian</h4>
                </div>
                <style>
                    span.undone,
                    span.done {
                        color: #555;
                        padding: 3px 10px;
                        border-radius: 3px;
                    }

                    span.undone {
                        background: #ff9e7a;
                    }

                    span.done {
                        background: #8cff7a;
                    }
                </style>
                <?php
                $limit = 10; // Jumlah item per halaman
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($page - 1) * $limit;

                // Hitung total data
                $total_query = query("exam_d", "WHERE class='$myclass'");
                $total_rows = mysqli_num_rows($total_query);
                $total_pages = ceil($total_rows / $limit);

                // Ambil data dengan pagination
                $get = query("exam_d", "WHERE class='$myclass' LIMIT $limit OFFSET $offset");
                ?>

                <div class="content" style="overflow: scroll;">
                    <table class="table table-hover table-striped mt-3">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Judul/Nama Ujian</th>
                                <th>Mata Pelajaran</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = $offset + 1; // Penomoran sesuai halaman
                            while ($data = mysqli_fetch_array($get)) {
                                if ($exam[$data['exam_id']]['publish_status'] == 1) { ?>
                                    <tr>
                                        <td><?= $no ?></td>
                                        <td><?= base64_decode($exam[$data['exam_id']]['title']) ?></td>
                                        <td><span style="background: #FF9500;color: #FFF;padding: 3px 5px;border-radius: 3px;"><?= $mapel['detail'][$exam[$data['exam_id']]['subject_id']]['title'] ?></span></td>
                                        <td>
                                            <?php
                                            if (!isset($exas[$data['exam_id']][$unme])) {
                                                echo "<span class='undone'>BELUM DIKERJAKAN</span>";
                                            } else {
                                                if ($exas[$data['exam_id']][$unme]['cstatus'] == 1) {
                                                    echo "<span class='done'>DONE [ Nilai : " . $exas[$data['exam_id']][$unme]['nilai'] . " ]</span>";
                                                } else {
                                                    echo "<span class='done'>DONE</span>";
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td><a class="pull-right btn btn-sm btn-info btn-fill" href="<?= base_url("exam?hash=" . $data['exam_id']) ?>">Open <i class="fa fa-external-link"></i></a></td>
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
</div>