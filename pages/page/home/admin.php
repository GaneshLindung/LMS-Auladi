<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap');

    .card {font-family: "Nunito Sans", sans-serif;}
    .card .title {font-size: 13px;font-weight: bold;}
    .card .value {font-size: 20px;font-weight: bold;margin-top: 6px;}

    .col-md-3:first-child .card {border-left: 4px solid var(--blue)}
    .col-md-3:first-child .title {color: var(--blue)}

    .col-md-3:nth-child(2) .card {border-left: 4px solid var(--pink)}
    .col-md-3:nth-child(2) .title {color: var(--pink)}

    .col-md-3:nth-child(3) .card {border-left: 4px solid var(--orange)}
    .col-md-3:nth-child(3) .title {color: var(--orange)}

    .col-md-3:nth-child(4) .card {border-left: 4px solid var(--green)}
    .col-md-3:nth-child(4) .title {color: var(--green)}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card" style="padding: 24px 16px;display: grid;">
                <span class="title">SISWA LAKI-LAKI</span>
                <span class="value"><?= mysqli_num_rows(query("siswa","WHERE gender='L'")) ?> Siswa</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="padding: 24px 16px;display: grid;">
                <span class="title">SISWA PEREMPUAN</span>
                <span class="value"><?= mysqli_num_rows(query("siswa","WHERE gender='P'")) ?> Siswa</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="padding: 24px 16px;display: grid;">
                <span class="title">JUMLAH UJIAN</span>
                <span class="value"><?= mysqli_num_rows(query("examl","")) ?> Ujian</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="padding: 24px 16px;display: grid;">
                <span class="title">UPLOAD MATERI / VIDEO</span>
                <span class="value"><?= mysqli_num_rows(query("materi","")) ?> Materi & <?= mysqli_num_rows(query("video","")) ?> Video</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="header">
                    <h4 class="title">Statistik Login User</h4>
                </div>
                <div class="content">
                    
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="header">
                    <h4 class="card-title" style="margin: 0px 0px -10px;color: var(--blue);font-family: 'Nunito Sans', sans-serif;">User Rank by login ^10</h4>
                </div>
                <div class="content">
                    <table class="table table-hover table-striped">
                        <thead>
                            <th>NO</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Jumlah</th>
                        </thead>
                        <tbody>
                        <?Php
                        $SELECT = mysqli_query($conn, "SELECT *, COUNT(*) as jumlah FROM login WHERE NOT username='admin' GROUP BY username ORDER BY jumlah DESC LIMIT 10");
                        if(mysqli_num_rows($SELECT) > 0) { $no = 1; while($data = mysqli_fetch_array($SELECT)) { ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= $users['data'][$data['username']]['name'] ?></td>
                                <td><?= $users['data'][$data['username']]['as'] ?></td>
                                <td><?= $data['jumlah'] ?></td>
                            </tr>
                        <?Php $no++; } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/chartist.min.js"></script>
<script>$(document).ready(function(){ demo.initChartist(); });</script>