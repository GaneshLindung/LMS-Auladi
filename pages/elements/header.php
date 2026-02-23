<nav class="navbar navbar-default navbar-fixed">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="<?= base_url("") ?>" class="navbar-brand" style="padding: 0px 15px;">
                <img src="<?= base_url("static/logo-auladi.png") ?>" style="width: 105px;" />
            </a>
            <ul class="nav navbar-nav navbar-left" style="display: none;">
                <li>
                    <a href="">
                        <i class="fa fa-bell-o"></i>
                        <p class="hidden-lg hidden-md">Search</p>
                        <span class="notification hidden-sm hidden-xs">5</span>
                        <p class="hidden-lg hidden-md">
                            5 Notifications
                            <b class="caret"></b>
                        </p>
                    </a>
                </li>
            </ul>
        </div>
        <style>
            .notification.read {
                background-color: var(--green) !important;
            }
        </style>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <p><?= $user['full_name'] ?> <b class="caret"></b></p>
                    </a>
                    <ul class="dropdown-menu">
                        <?= $user['role'] != "admin" ? '<li><a href="'.base_url("profile").'">Profil Saya</a></li>' : '' ?>
                        <?= $user['role'] != "admin" ? '<li class="divider"></li>' : '' ?>
                        <li><a href="?logout">Keluar</a></li>
                    </ul>
                </li>
                <?Php if($user['role'] != "admin") { ?>
                <li>
                    <a href="<?= base_url("chat") ?>">
                        <i class="fa fa-envelope-o"></i>
                        <p class="hidden-lg hidden-md d-flex space-between"><span>Pesan</span><span>( 5 )</span></p>
                        <!-- <span class="notification hidden-sm hidden-xs">2</span> -->
                    </a>
                </li>
                <?Php if($user['role'] != "teacher") { ?>
                <li>
                    <a href="<?= base_url("notifikasi") ?>">
                        <?Php $jumlahh = mysqli_num_rows(query("notification","WHERE peer='$unme' AND status='0'")); ?>
                        <i class="fa fa-bell-o"></i>
                        <p class="hidden-lg hidden-md d-flex space-between"><span>Notifikasi</span><span>( <?= $jumlahh ?> )</span></p>
                        <span class="notification hidden-sm hidden-xs <?= $jumlahh < 1 ? "read" : "" ?>"><?= $jumlahh  ?></span>
                    </a>
                </li>
                <?Php } ?>
                <?Php } ?>
                <li class="separator hidden-lg"></li>
            </ul>
        </div>
    </div>
</nav>