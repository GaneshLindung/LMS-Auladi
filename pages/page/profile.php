<?Php if($user['role'] != "admin") { ?>
<div class="container-fluid">
    <div class="row">
        <?Php if(!isset($_GET['action'])) { ?>
        <div class="col-md-3">
            <div class="card">
                <div class="header">
                    <h4 class="title">Foto Profil</h4>
                </div>
                <div class="content">
                    <img src="<?= $user['role'] == "student" ? base_url("static/image/display_picture/".$siswa[$unme]['dp']) : base_url("static/image/display_picture/".$guru[$user['username']]['dp']) ?>" style="object-fit: cover;aspect-ratio: 1/1;border-radius: 5px;" />
                    <h4 class="mt-5 text-center"><?= $users['data'][$user['username']]['name'] ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="header d-flex space-between">
                    <h4 class="title">Detail Informasi</h4>
                    <a href="<?= base_url("profile?action=update") ?>" class="btn btn-sm btn-fill btn-warning"><i class="fa fa-edit"></i> Edit Data</a>
                </div>
                <div class="content">
                    <div class="row">
                        <div class="col-md-3"><b><?= $user['role'] == "student" ? "NISN" : ($user['role'] == "teacher" ? "NIP" : "USERNAME" ) ?></b></div>
                        <div class="col-md-8">: <?= $user['username'] ?></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3"><b>Nama Lengkap</b></div>
                        <div class="col-md-8">: <?= $users['data'][$user['username']]['name'] ?></div>
                    </div>
                    <?Php if(in_array($user['role'], ["student","teacher"])) { ?>
                    <div class="row mt-3">
                        <div class="col-md-3"><b>Jenis Kelamin</b></div>
                        <div class="col-md-8">: <?= $user['role'] == "student" ? $gender[$siswa[$user['username']]['gender']] ?? "Null" : ($user['role'] == "teacher" ? $gender[$guru[$user['username']]['gender']] : "") ?></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3"><b>Tempat, Tanggal Lahir</b></div>
                        <div class="col-md-8">: <?= $user['role'] == "student" ? $siswa[$user['username']]['birthloc'] . ", " . date('d F Y', strtotime($siswa[$user['username']]['birthdate'])) : ($user['role'] == "teacher" ? $guru[$user['username']]['birthloc']. ", " . date('d F Y', strtotime($guru[$user['username']]['birthdate'])) : "") ?></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3"><b>Alamat</b></div>
                        <div class="col-md-8">: <?= $user['role'] == "student" ? $siswa[$user['username']]['address'] : ($user['role'] == "teacher" ? $guru[$user['username']]['address'] : "") ?></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3"><b>No Telp ( HP / WA )</b></div>
                        <div class="col-md-8">: <?= $user['role'] == "student" ? $siswa[$user['username']]['contact']['phone'] : ($user['role'] == "teacher" ? $guru[$user['username']]['contact']['phone'] : "") ?></div>
                    </div>
                    <?Php } ?>
                    <?Php if($user['role'] == "student") { ?>
                    <div class="row mt-3">
                        <div class="col-md-3"><b>Kelas</b></div>
                        <div class="col-md-8">: <?= $class['detail'][$siswa[$user['username']]['class']] ?></div>
                    </div>
                    <?Php } ?>
                    <div class="mt-3"></div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="header">
                    <h4 class="title">Ubah Kata sandi</h4>
                </div>
                <div class="content">
                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label>Password Baru</label>
                            <input type="password" name="newPassword" class="form-control input-password" placeholder="Password Baru" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Ulangi Password Baru</label>
                            <input type="password" name="retypeNewPassword" class="form-control input-password" placeholder="Ketik ulang password Baru" />
                        </div>
                    </div>
                    <span id="showPassword" class="cursor-pointer">
                        <span class="p-show"><i class="fa fa-eye"></i> Show password</span>
                        <span class="p-hide hide"><i class="fa fa-eye-slash"></i> Hide password</span>
                    </span>
                    <input type="button" id="updatePassword" class="btn btn-danger btn-fill form-control mt-3" value="S I M P A N" />
                    <div class="mt-3"><span class="text-warning mt-3 d-block"></span></div>
                    <script>
                        $("#showPassword").on("click", function(){
                            if($(this).hasClass("active")) {
                                $(".p-show").removeClass("hide");
                                $(".p-hide").addClass("hide");
                                $(".input-password").attr("type","password");
                                $(this).removeClass("active");
                            } else {
                                $(".p-show").addClass("hide");
                                $(".p-hide").removeClass("hide");
                                $(".input-password").attr("type","text");
                                $(this).addClass("active");
                            }
                        });
                    </script>
                    <script>
                        $("input#updatePassword").on("click", function(){
                            $("span.text-warning").html("");
                            var newPassword = $("input[name='newPassword']").val(),
                                retypeNewPassword = $("input[name='retypeNewPassword']").val();
                            
                            if(newPassword == retypeNewPassword) {
                                setTimeout(function(){
                                    $.ajax({
                                        url: "<?= base_url("endpoint/index.php") ?>",
                                        type: "POST",
                                        data: {
                                            "action":"crud/profile",
                                            "activity":"updatePassword",
                                            "newPassword":newPassword
                                        },
                                        success:function(data){
                                            var response = JSON.parse(data);
                                            console.log(data);
                                            if(response.OK == true) {
                                                $("span.text-warning").html("Berhasil memperbarui password.");
                                            } else {
                                                $("span.text-warning").html("Gagal memperbarui password.");
                                            }
                                        }, error:function(data){}
                                    });
                                }, 500);
                            } else {
                                $("span.text-warning").html("Password baru tidak sama!");
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
        <?Php } else { $me = $user['role'] == "student" ? $siswa[$user['username']] : $guru[$user['username']] ?>
        <div class="col-md-6">
            <div class="card">
                <div class="header"><h4 class="title">Update Informasi</h4></div>
                <div class="content">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <img id="profilePic" src="<?= $me['dp'] != "" ? base_url("static/image/display_picture/".$me['dp']) : $defaultDP ?>" style="object-fit: cover;aspect-ratio: 1/1;border-radius: 5px;" />
                                <input type="file" class="form-control mt-3" name="profile_photo" accept="image/png, image/gif, image/jpeg" />
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6 form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" name="full_name" class="form-control" value="<?= $me['name'] ?>" />
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Jenis Kelamin</label>
                                <select name="gender" class="form-control">
                                    <option value="L" <?= $me['gender'] == "L" ? "selected" : "" ?>>Laki-Laki</option>
                                    <option value="P" <?= $me['gender'] == "P" ? "selected" : "" ?>>Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <?Php if(in_array($user['role'], ["student","teacher"])) { ?>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Nomor Telepon</label>
                                <input type="text" name="phone" class="form-control" value="<?= $me['contact']['phone'] ?>" />
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Alamat E-Mail</label>
                                <input type="text" name="email" class="form-control" value="<?= $me['contact']['email'] ?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Tempat Lahir</label>
                                <input type="text" name="birthloc" class="form-control" value="<?= $me['birthloc'] ?>" />
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Tanggal Lahir</label>
                                <input type="date" name="birthdate" class="form-control" value="<?= $me['birthdate'] ?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Alamat Tinggal</label>
                                <textarea name="address" rows="4" class="form-control"><?= $me['address'] ?></textarea>
                            </div>
                        </div>
                        <?Php } ?>
                        <div class="d-flex space-between mt-3" style="align-items: center;">
                            <span class="text-warning"></span>
                            <input type="button" id="update" class="btn btn-fill btn-info" value="SIMPAN" />
                        </div>
                    </form>
                    <div class="mt-3"></div>
                    <script>
                        $("input[name='profile_photo']").on("change", function(){
                            file = this.files[0];
                            if (file) {
                                let reader = new FileReader();
                                reader.onload = function (event) {
                                    $("#profilePic").attr("src", event.target.result);
                                };
                                reader.readAsDataURL(file);
                            }
                        });

                        $("input#update").on("click", function(){
                            $("span.text-warning").html("");

                            var form = $("form")[0],
                                formData = new FormData(form);
                            formData.append("action","crud/profile");
                            formData.append("activity","update");

                            setTimeout(function(){
                                $.ajax({
                                    url: "<?= base_url("endpoint/index.php") ?>",
                                    type: "POST",
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success:function(data){
                                        var response = JSON.parse(data);
                                        console.log(data);
                                        if(response.OK == true) {
                                            $("span.text-warning").html("Berhasil memperbarui informasi.");
                                        } else {
                                            $("span.text-warning").html("Gagal memperbarui informasi.");
                                        }
                                    }, error:function(data){}
                                });
                            }, 500);
                        });
                    </script>
                </div>
            </div>
        </div>
        <?Php } ?>
    </div>
</div>
<?Php } ?>