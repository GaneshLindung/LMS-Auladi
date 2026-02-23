<div class="container-fluid">
    <div class="row">
        <?php if (!isset($_GET['action'])) { ?>
            <div class="col-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Pilih Materi Sesuai Mata Pelajaran</h4>
                    </div>
                    <?php
                    $limit = 10; // Jumlah item per halaman
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;

                    // Hitung total data
                    $total_query = query("subject", "WHERE tipesubject='umum' OR ID IN ($myekskul)");
                    $total_rows = mysqli_num_rows($total_query);
                    $total_pages = ceil($total_rows / $limit);

                    // Ambil data dengan pagination
                    $getmapel = query("subject", "WHERE tipesubject='umum' OR ID IN ($myekskul) ORDER BY tipesubject, subject_title ASC LIMIT $limit OFFSET $offset");
                    ?>

                    <div class="content">
                        <div style="display: flex;gap: 15px;max-width: 500px;margin-bottom: 15px;"></div>
                        <table class="table table-hover table-striped action">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru Pengajar</th>
                                    <th>Lihat Materi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = $offset + 1;
                                if (mysqli_num_rows($getmapel)) {
                                    while ($data = mysqli_fetch_array($getmapel)) {
                                        $cond = true;
                                        if (in_array($data['ID'], $mapel['ids']['umum'])) {
                                            if (!in_array($data['ID'], $class['more_info'][$myclass]['mapel'])) {
                                                $cond = false;
                                            }
                                        }
                                        if ($cond) { ?>
                                            <tr>
                                                <td><?= $data['tipesubject'] == "ekskul" ? "<i class='text-danger'>ekskul</i> - " : "" ?><?= $mapel['detail'][$data['ID']]['title'] ?></td>
                                                <td><?= $guru[$mapel['detail'][$data['ID']]['teachers'][$myclass]]['name'] ?? "" ?></td>
                                                <td><a href="<?= base_url("materi?action=detail&materi_id=" . $data['ID']) ?>" class="btn btn-sm btn-success"><i class="fa fa-external-link"></i> Lihat</a></td>
                                            </tr>
                                <?php
                                            $no++;
                                        }
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
        <?php } else {
            $mrid = $_GET['materi_id']; ?>
            <div class="col-md-12">
                <div class="card mt-3">
                    <div class="header">
                        <h4 class="title">Materi : <?= $mapel['detail'][$mrid]['title'] ?></h4>
                    </div>
                    <div class="content">
                        <div style="display: flex;gap: 15px;max-width: 500px;margin-bottom: 15px;">

                        </div>
                        <table class="table table-hover table-striped action">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Judul Materi / Modul</th>
                                    <th>BAB</th>
                                    <th>Download</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $getmapel = query("materi_d", "GROUP BY materi_id");
                                if (mysqli_num_rows($getmapel) > 0) {
                                    $no = 1;
                                    while ($dtx = mysqli_fetch_array($getmapel)) {
                                        $data = $materi['data'][$dtx['materi_id']];
                                        if (isset($materi['data'][$dtx['materi_id']]) && $data['subject_id'] == $mrid) {
                                            // if (in_array($data['subject_id'], $siswa[$user['username']]['ekskul']) OR in_array($myclass, $data['classes'])) {
                                            if (in_array($myclass, $data['classes'])) {
                                ?>
                                                <tr>
                                                    <td><?= $no ?></td>
                                                    <td><?= $data['title'] ?></td>
                                                    <td>BAB <?= $data['bab'] ?></td>
                                                    <td><a href="<?= base_url("/static/files/modules/" . $data['file']) ?>" class="btn btn-sm btn-success" download><i class="fa fa-download"></i> Unduh</a></td>
                                                </tr>
                                <?php
                                                $no++;
                                            }
                                        }
                                    }
                                } else {
                                    echo "<tr><td colspan='3'>Belum ada materi / modul yang diupload pada mata pelajaran ini.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>