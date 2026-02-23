<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title><?= $site['shortname'] ?></title>
    <link rel="shortcut icon" href="static/icon.png" />
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
    <script src="assets/js/jquery.min.js"></script>
    <link href="./dist/css/tabler.min.css?1684106062" rel="stylesheet"/>
    <link href="./dist/css/tabler-flags.min.css?1684106062" rel="stylesheet"/>
    <link href="./dist/css/tabler-payments.min.css?1684106062" rel="stylesheet"/>
    <link href="./dist/css/tabler-vendors.min.css?1684106062" rel="stylesheet"/>
    <link href="./dist/css/demo.min.css?1684106062" rel="stylesheet"/>
    <?php
      $array = ["gedung-auladi-1.jpg","gedung-auladi-2.jpg","gedung-auladi-3.jpg"];
      $bg = array_rand($array,1);
      $bg = $array[$bg];
    ?>
    <style>
      /* @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      } */

      html, body {height: 100%;}

      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
        background : #EFEFEF;
        background-image: url("<?= base_url("static/".$bg) ?>");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
      }
    </style>
  </head>
  <body class="d-flex flex-column">
    <script src="./dist/js/demo-theme.min.js?1684106062"></script>
    <div class="page page-center">
      <?Php if(isset($_GET['login']) || isset($_GET['register'])) { ?>
      <div class="container container-normal">
        <div class="row align-items-center g-4">
          <div class="col-lg">
            <div class="<?= isset($_GET['login']) ? 'container-tight' : 'registform' ?> mx-auto p-5" style="<?= isset($_GET['login']) ? 'background: rgba(255,255,255, .7);' : '' ?>border-radius: 15px;">
            <img src="<?= isset($_GET['login']) ? base_url("static/logo-auladi.png") : base_url("static/logo-auladi-first.png") ?>" <?= isset($_GET['login']) ? 'height="150"' : 'height="100"'  ?> class="d-block mx-auto mb-5" />
            <?= isset($_GET['login']) ? '<h2 class="h2 mb-5 text-center text-muted" style="font-style: italic;">Learning Management System</h2>' : '' ?>
              <div class="card card-md" style="border-radius: 10px;border: 0px solid transparent;">
                <?Php if(isset($_GET['login'])) { ?>
                <div class="card-body">
                  <h1 class="mb-5">Masuk ke LMS</h1>
                  <form method="post" autocomplete="off" novalidate>
                    <div class="mb-3">
                      <label class="form-label">Nama Pengguna</label>
                      <input type="text" class="form-control" name="username" placeholder="Username" autocomplete="off">
                    </div>
                    <div class="mb-2">
                      <label class="form-label">
                        Kata sandi
                        <!-- <span class="form-label-description">
                          <a href="./forgot-password.html">Lupa password</a>
                        </span> -->
                      </label>
                      <div class="input-group input-group-flat">
                        <input type="password" class="form-control input-password" name="password" placeholder="Your password"  autocomplete="off">
                        <span class="input-group-text" id="showPassword">
                          <a href="#" class="link-secondary" data-bs-toggle="tooltip" aria-label="Show password" data-bs-original-title="Show password"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path></svg>
                          </a>
                        </span>
                      </div>
                    </div>
                    <div class="form-footer">
                      <button id="login" class="btn btn-warning w-100">Masuk</button>
                    </div>
                    <div class="text-center text-muted mt-3">
                      Belum memiliki akun? <a href="<?= base_url("?register") ?>" tabindex="-1">Register</a>
                    </div>
                  </form>
                  <script>                    
                    $("button#login").on("click", function(e){
                      e.preventDefault();
                      var form = $("form")[0],
                        formData = new FormData(form);
                      formData.append('action','auth/login');
                      $.ajax({
                        url: "<?= base_url("endpoint/index.php") ?>",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success:function(data){
                          var response = JSON.parse(data);
                          if(response.OK == true) {
                            if(response.AS == "student") {
                              window.location.replace("<?= base_url("profile") ?>");
                            } else {
                              window.location.replace("<?= base_url("") ?>");
                            }
                          } else {
                            alert(response.m);
                          }
                        },
                        error:function(data){}
                      });
                    });
                  </script>
                </div>
                <?Php } elseif(isset($_GET['register'])) { ?>
                <style>
                  .registform {max-width: 900px;}
                </style>
                <div class="card-body">
                  <h1 class="mb-5">Daftar ke LMS</h1>
                  <form method="post" autocomplete="off" novalidate>
                    <div class="row">
                      <div class="col-md-6">
                        <label class="form-label">Nomor Induk Siswa</label>
                        <input type="text" class="form-control" name="nis" placeholder="NIS" autocomplete="off">
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="full_name" placeholder="Nama Lengkap" autocomplete="off">
                      </div>
                    </div>
                    <div class="row mt-3">
                      <div class="col-md-6">
                        <div class="row">
                          <div class="col-6">
                            <label class="form-label">Kelas</label>
                            <select name="kelas" class="form-select">
                              <option value="">- Pilih -</option>
                              <?Php for($i=0;$i <= count($class['array'])-1; $i++) { ?>
                              <option value="<?= $class['array'][$i]['ID'] ?>"><?= $class['array'][$i]['name'] ?></option>
                              <?Php } ?>
                            </select>
                          </div>
                          <div class="col-6">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="gender" class="form-select">
                              <option value="">- Pilih -</option>
                              <option value="L">Laki-Laki</option>
                              <option value="P">Perempuan</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6 mt-3 mt-md-0">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control" name="phone" placeholder="0812-1100-1010" autocomplete="off">
                      </div>
                    </div>
                    <div class="row mt-3">
                      <div class="col-md-6">
                        <label class="form-label">Alamat Email</label>
                        <input type="text" class="form-control" name="email" placeholder="yourname@domain.co" autocomplete="off">
                      </div>
                      <div class="col-md-6 mt-3 mt-md-0">
                        <div class="row">
                          <div class="col-5">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="birthloc" class="form-control" placeholder="Kota Lahir" />
                          </div>
                          <div class="col-7">
                            <labe class="form-label">Tanggal Lahir</labe>
                            <input type="date" name="birthdate" class="form-control" />
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row mt-3">
                      <div class="col-md-6">
                        <label class="form-label">Kata sandi</label>
                        <input type="password" id="pw1" class="form-control" name="password" placeholder="Kata sandi" />
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Ulangi Kata sandi</label>
                        <input type="password" id="pw2" class="form-control" placeholder="Ulangi Kata sandi" />
                      </div>
                    </div>
                    <div class="form-footer">
                      <button id="signUp" class="btn btn-warning w-100">Daftar</button>
                    </div>
                    <div class="text-center text-muted mt-3">
                      Sudah memiliki akun? <a href="<?= base_url("?login") ?>" tabindex="-1">Log-in</a>
                    </div>
                  </form>
                  <script>
                    $("button#signUp").on("click", function(e){
                      e.preventDefault();
                      var p1 = $("input#pw1").val(),
                          p2 = $("input#pw2").val();
                      
                      if (p1 == p2) {
                        var form = $("form")[0],
                          formData = new FormData(form);
                        formData.append('action','auth/regist');
                        $.ajax({
                          url: "<?= base_url("endpoint/index.php") ?>",
                          type: "POST",
                          data: formData,
                          processData: false,
                          contentType: false,
                          success:function(data){
                            console.log(data);
                            var response = JSON.parse(data);
                            if(response.OK == true) {
                              alert("Berhasil daftar! Tunggu Akun kamu dikonfirmasi oleh Admin ya..")
                              setTimeout(function(){
                                window.location.replace("<?= base_url("") ?>");
                              }, 1000);
                            } else {
                              alert(response.m);
                            }
                          },
                          error:function(data){}
                        });
                      } else {
                        alert("Katasandi tidak sama.");
                      }
                    });
                  </script>
                </div>                
                <?Php } ?>
              </div>
            </div>
          </div>
        </div>
        <script>
          $("#showPassword").on("click", function(){
            if($(this).hasClass("active")) {
              $(".input-password").attr("type","password");
              $(this).removeClass("active");
            } else {
              $(".input-password").attr("type","text");
              $(this).addClass("active");
            }
          });
        </script>
      </div>
      <?Php } else { ?>
      <style>
        nav {
          background: rgba(248, 141, 0, .7);
          padding: 8px 16px;
        }
      </style>
      <nav>
        <img src="<?= base_url("static/logo-auladi-first.png") ?>" class="d-block mx-auto" >
      </nav>
      <div class="container">
        <div class="row align-items-center g-4">
          <style>
            span {display: block;color: #FFFFFF;margin-bottom: 15px}
            span:first-child {font-size: 20px;}
            img {width: 100%;max-width: 450px;}

            @media (min-width: 768px) {
              span:first-child {font-size: 35px;}
              a.btn {padding: 10px 35px;font-size: 17px;}
            }
          </style>
          <div class="col-lg text-center">
            <span>Learning Management System</span>
            <div style="display: flex;gap: 15px;justify-content: center;">
              <a href="<?= base_url("?login") ?>" class="btn btn-info btn-fill">LOG-IN</a>
              <a href="<?= base_url("?register") ?>" class="btn btn-warning btn-fill">REGISTER</a>
            </div>
          </div>
        </div>
      </div>
      <?Php } ?>
    </div>
  </body>
</html>