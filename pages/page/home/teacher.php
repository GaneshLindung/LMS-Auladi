<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap');

    .card {
        font-family: "Nunito Sans", sans-serif;
    }

    .card .title {
        font-size: 13px;
        font-weight: bold;
    }

    .card .value {
        font-size: 20px;
        font-weight: bold;
        margin-top: 6px;
    }

    .col-md-3:first-child .card {
        border-left: 4px solid var(--blue)
    }

    .col-md-3:first-child .title {
        color: var(--blue)
    }

    .col-md-3:nth-child(2) .card {
        border-left: 4px solid var(--green)
    }

    .col-md-3:nth-child(2) .title {
        color: var(--green)
    }

    .col-md-3:nth-child(3) .card {
        border-left: 4px solid var(--orange)
    }

    .col-md-3:nth-child(3) .title {
        color: var(--orange)
    }

    .col-md-3:nth-child(4) .card {
        border-left: 4px solid var(--yellow)
    }

    .col-md-3:nth-child(4) .title {
        color: var(--yellow)
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card" style="padding: 24px 16px;display: grid;">
                <span class="title">KELAS YANG DI AMPU</span>
                <span class="value"><?= mysqli_num_rows(query("subjetc", "WHERE nip='$unme' AND NOT class='0' GROUP BY class")) ?> Kelas</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="padding: 24px 16px;display: grid;">
                <span class="title">MAPEL YANG DI AMPU</span>
                <span class="value"><?= mysqli_num_rows(query("subjetc", "WHERE nip='$unme' GROUP BY subject_id")) ?> Mapel</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="padding: 24px 16px;display: grid;">
                <span class="title">SISWA BINAAN</span>
                <?Php $classesX = implode(",", $guru[$unme]['classes']) ?>
                <span class="value"><?= mysqli_num_rows(query("siswa", "WHERE class IN ($classesX)")) ?> Siswa</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="padding: 24px 16px;display: grid;">
                <span class="title">UPLOAD MATERI / MODUL</span>
                <span class="value"><?= mysqli_num_rows(query("materi", "WHERE uploader='$unme'")) + mysqli_num_rows(query("video", "WHERE uploader='$unme'")) ?> Materi</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h4 class="card-title" style="margin: 0px 0px -10px;color: var(--blue);font-family: 'Nunito Sans', sans-serif;">Mata Pelajaran Yang di Ampu</h4>
                </div>
                <?php
                $limit = 10; // Jumlah item per halaman
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($page - 1) * $limit;

                // Hitung total data
                $total_query = query("subjetc", "WHERE nip='$unme'");
                $total_rows = mysqli_num_rows($total_query);
                $total_pages = ceil($total_rows / $limit);

                // Ambil data dengan pagination
                $SELECT = query("subjetc", "WHERE nip='$unme' ORDER BY class ASC LIMIT $limit OFFSET $offset");
                ?>
                <div class="content">
                    <table class="table table-hover table-striped">
                        <thead>
                            <th>NO</th>
                            <th>KELAS</th>
                            <th>MATA PELAJARAN</th>
                        </thead>
                        <tbody>
                            <?php if ($total_rows > 0) {
                                $no = $offset + 1; // Penomoran yang menyesuaikan dengan halaman
                                while ($data = mysqli_fetch_array($SELECT)) { ?>
                                    <tr>
                                        <td><?= $no ?></td>
                                        <td><?= $data['class'] != "0" ? "<span style='background: var(--orange);color: #FFF;padding: 3px 5px;border-radius: 3px;'>" . $class['info'][$data['class']] . "</span>" : "<i class='text-warning'>Ekskul</i>" ?></td>
                                        <td><?= $mapel['detail'][$data['subject_id']]['title'] ?></td>
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