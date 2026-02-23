<div class="sidebar" data-color="orange">
    <!-- data-image="https://ppdb.sitauladi.sch.id/wp-content/uploads/2021/10/talent-sd-it-auladi-2.png" -->
    <div class="sidebar-wrapper">
        <div class="logo" >
            <div style="display: grid;grid-template-columns: 1.1fr 5fr;gap: 16px;">
                <img src="static/computer.png" style="width: 100%;" />
                <span class="simple-text">BELAJAR ONLINE</span>
            </div>
        </div>

        <ul class="nav">
            <?php // $user['role'] = "admin"; ?>
            <?php if(in_array($user['role'], ["admin","teacher"])) { ?>
            <li data-slug=""><a href="<?= base_url("") ?>"><i class="pe-7s-graph"></i><p>Dashboard</p></a></li>
            <?php } ?>
            <li data-slug="ujian" data-second-slug="soal"><a href="<?= base_url("ujian") ?>"><i class="pe-7s-user"></i><p>Ujian</p></a></li>
            <li data-slug="materi"><a href="<?= base_url("materi") ?>"><i class="pe-7s-notebook"></i><p>Materi</p></a></li>
            <li data-slug="video"><a href="<?= base_url("video") ?>"><i class="pe-7s-video"></i><p>Video Interaktif</p></a></li>
            <li data-slug="forum"><a href="<?= base_url("forum") ?>"><i class="pe-7s-users"></i><p>Forum Diskusi</p></a></li>
            <?php if($user['role'] == "admin") { ?>
            <li data-slug="siswa"><a href="<?= base_url("siswa") ?>"><i class="pe-7s-study"></i><p>Data Siswa</p></a></li>
            <?php } if($user['role'] == "admin") { ?>
            <li data-slug="guru"><a href="<?= base_url("guru") ?>"><i class="fa fa-user-o"></i><p>Data Guru</p></a></li>
            <li data-slug="mapel"><a href="<?= base_url("mapel") ?>"><i class="pe-7s-note2"></i><p>Master Mapel</p></a></li>
            <!-- <li><a href="" data-slug="table"><i class="pe-7s-note2"></i><p>Table List</p></a></li> -->
            <li data-slug="kelas"><a href="<?= base_url("kelas") ?>"><i class="pe-7s-science"></i><p>Kelas</p></a></li>
            <li data-slug="user"><a href="<?= base_url("user") ?>"><i class="pe-7s-user"></i><p>Pengguna</p></a></li>
            <!-- <li class="active-pro"><a href="<?= base_url("setting") ?>"><i class="fa fa-gear"></i><p>Pengaturan Website</p></a></li> -->
            <?php } if(in_array($user['role'], ["student","teacher"])) { ?>
            <li class="active-pro"><a href="<?= base_url("profile?action=update") ?>"><i class="pe-7s-id"></i><p>Pengaturan Akun</p></a></li>
            <?php } ?>
        </ul>
    </div>
</div>