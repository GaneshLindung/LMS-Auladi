<div class="pilgand add" data-token="add" style="background: #f7fff5;">
    <form data-token="add">
        <div class="row">
            <div class="col-md-12 d-flex space-between" style="align-items: center;">
                <h5 style="margin: 0px;">Tambah</h5>
                <div class="pull-right">
                    <input type="button" class="btn btn-sm btn-success btn-fill" value="Simpan" />
                </div>
            </div>
        </div>
        <div class="mt-3" style="display: none;">
            <input type="file" name="image" class="form-control" data-token="add" accept="image/png, image/gif, image/jpeg" />
            <img src="" class="taskImage" data-token="add" />
        </div>
        <div class="row" style="margin-top: 10px;">
            <div class="col-md-6">
                <textarea class="form-control" rows="4" name="question" placeholder="Pertanyaan"></textarea>
            </div>
            <div class="col-md-6 answers">
                <div class="choices">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <input type="checkbox" name="va[]" value="a" />
                        </span>
                        <input type="text" class="form-control" name="choice_a" placeholder="Jawaban" />
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <input type="checkbox" name="va[]" value="b" />
                        </span>
                        <input type="text" class="form-control" name="choice_b" placeholder="Jawaban" />
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <input type="checkbox" name="va[]" value="c" />
                        </span>
                        <input type="text" class="form-control" name="choice_c" placeholder="Jawaban" />
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <input type="checkbox" name="va[]" value="d" />
                        </span>
                        <input type="text" class="form-control" name="choice_d" placeholder="Jawaban" />
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <input type="checkbox" name="va[]" value="e" />
                        </span>
                        <input type="text" class="form-control" name="choice_e" placeholder="Jawaban" />
                    </div>
                    <select name="newValue" class="form-control">
                        <option value="0">- PILIH BOBOT SOAL -</option>
                        <?php for($x = 1;$x <= 100;$x++) { echo "<option value='".$x."'>".$x."</option>"; } ?>
                    </select>
                </div>
                <span style="display: block;margin-top: 15px;">* Centang pada jawaban-jawaban yang benar</span>
            </div>
        </div>
    </form>
</div>
<?Php if (mysqli_num_rows($GET) > 0) { $no = 1; while ($data = mysqli_fetch_array($GET)) { $rtoken = randomtoken(64); $carx = json_decode($data['va'], TRUE); ?>
<div class="pilgand" data-token="<?= $rtoken ?>">
    <form data-token="<?= $rtoken ?>" data-sign="<?= $hash.":".$data['ID'] ?>">
        <div class="row">
            <div class="col-md-12 d-flex space-between" style="align-items: center;">
                <h5 style="margin: 0px;"># <?= $no ?></h5>
                <div class="pull-right">
                    <button class="btn-edit btn btn-sm btn-warning btn-fill" data-token="<?= $rtoken ?>"><i class="fa fa-edit"></i></button>
                    <!-- <button class="btn-cancel btn btn-sm btn-danger btn-fill" data-token="<?= $rtoken ?>" disabled><i class="fa fa-close"></i></button> -->
                    <button class="btn-delete btn btn-sm btn-danger" data-token="<?= $rtoken ?>" disabled><i class="fa fa-trash"></i></button>
                    <button class="btn-save btn btn-sm btn-success btn-fill" data-token="<?= $rtoken ?>" disabled><i class="fa fa-save"></i></button>
                </div>
            </div>
        </div>
        <div>
            <input type="file" name="image" data-token="<?= $rtoken ?>" style="display: none;" accept="image/png, image/gif, image/jpeg" />
            <img src="<?= $data['image'] != "" ? base_url("static/image/soal/".$data['image']) : "" ?>" class="taskImage" data-saved="<?= $data['image'] != "" ? base_url("static/image/soal/".$data['image']) : "" ?>" />
        </div>
        <div><?= $data['image'] != "" ? "<span class='text-danger deleteImage' style='cursor: pointer;display: inline-block;border-bottom: 1px solid red;margin-top: 10px;' data-token='".$rtoken."'>Hapus Gambar</span>" : "" ?></div>
        <div class="row" style="margin-top: 10px;">
            <div class="col-md-6">
                <textarea class="form-control created" rows="4" name="question" data-saved="<?= $data['question'] ?>" placeholder="Pertanyaan" disabled><?= base64_decode($data['question']) ?></textarea>
            </div>
            <div class="col-md-6 answers">
                <div class="choices">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <input type="checkbox" name="va[]" value="a" <?= in_array("a", $carx) ? "checked" : "" ?> />
                        </span>
                        <input type="text" class="form-control" name="choice_a" placeholder='Jawaban' value="<?= base64_decode($data['a']) ?>" disabled />
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <input type="checkbox" name="va[]" value="b" <?= in_array("b", $carx) ? "checked" : "" ?> />
                        </span>
                        <input type="text" class="form-control" name="choice_b" placeholder='Jawaban' value="<?= base64_decode($data['b']) ?>" disabled />
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <input type="checkbox" name="va[]" value="c" <?= in_array("c", $carx) ? "checked" : "" ?> />
                        </span>
                        <input type="text" class="form-control" name="choice_c" placeholder='Jawaban' value="<?= base64_decode($data['c']) ?>" disabled />
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <input type="checkbox" name="va[]" value="d" <?= in_array("d", $carx) ? "checked" : "" ?> />
                        </span>
                        <input type="text" class="form-control" name="choice_d" placeholder='Jawaban' value="<?= base64_decode($data['d']) ?>" disabled />
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <input type="checkbox" name="va[]" value="e" <?= in_array("e", $carx) ? "checked" : "" ?> />
                        </span>
                        <input type="text" class="form-control" name="choice_e" placeholder='Jawaban' value="<?= base64_decode($data['e']) ?>" disabled />
                    </div>
                    <select name="value" class="form-control" data-saved="<?= $data['value'] ?>" disabled>
                        <option value="0">- PILIH BOBOT PER SOAL -</option>
                        <?php for($x = 1;$x <= 100;$x++) {
                            $crx = $x == $data["value"] ? "selected" : "";
                            echo "<option value='".$x."' ".$crx.">".$x."</option>";
                        } ?>
                    </select>
                </div>
            </div>
        </div>
    </form>
</div>
<?Php $no++; } } ?>
<script>
    $(document).ready(function(){
        $("textarea").summernote({
            height: 150,
        })
    });

    $(".pilgand.add").hide();

    $(".pilgand.add input[type='button']").on("click", function(){
        var question = $(`.pilgand.add textarea[name='question']`).val(),
            pil_a    = $(`.pilgand.add input[name='choice_a']`).val(),
            pil_b    = $(`.pilgand.add input[name='choice_b']`).val(),
            pil_c    = $(`.pilgand.add input[name='choice_c']`).val(),
            pil_d    = $(`.pilgand.add input[name='choice_d']`).val(),
            pil_e    = $(`.pilgand.add input[name='choice_e']`).val(),
            va       = $(".pilgand.add select[name='va']").val();

        var form = $(".pilgand.add form")[0],
            formData = new FormData(form);

        formData.append("action","crud/ujian/msoal");
        formData.append("activity","add");
        formData.append("hash","<?= $_GET['hash'] ?>");
        formData.append("multiple", true);
        
        if (question != "" && pil_a != "" && pil_b != "" && pil_c != "" && pil_d != "" && pil_e != "" && va != "") {
            $(".pilgand.add input[type='button']").attr("disabled","disabled");
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
                        window.location.reload();
                    } else {
                        alert("Gagal menambahkan");
                        $(".pilgand.add input[type='button']").removeAttr("disabled");
                    }
                }, error:function(data){}
            });
        } else { alert("Lengkapi semua isian terlebih dahulu."); }
    });

    $(".btn-add").on("click", function(){
        if(!$(this).hasClass("active")) {
            $(".pilgand.add").show();
            $(this).addClass("active");
        } else {
            $(".pilgand.add").hide();
            $(this).removeClass("active");
        }
    });

    $("button.btn-edit").on("click", function(e){
        e.preventDefault();
        var token = $(this).data("token");
        $(`.pilgand[data-token=${token}]`).addClass("editable");
        $(`.pilgand[data-token=${token}] textarea, .pilgand[data-token=${token}] input, .pilgand[data-token=${token}] button, .pilgand[data-token=${token}] select`).removeAttr("disabled");

        $(this).attr("disabled","disabled");
    });

    $("button.btn-delete").on("click", function(e){
        e.preventDefault();
        var token = $(this).data("token");
        var sign = $(`.pilgand form[data-token=${token}]`).data("sign");
        
        if(confirm("Apakah Anda yakin ingin menghapus pilihan ganda ini?")) {
            $.ajax({
                url: "<?= base_url("endpoint/index.php") ?>",
                type: "POST",
                data: {"action":"crud/ujian/msoal","activity":"delete","sign":sign},
                success:function(data){
                    var response = JSON.parse(data);
                    console.log(data);
                    if(response.OK == true) {
                        $(`.pilgand[data-token=${token}]`).remove();
                    }
                }, error:function(data){}
            });
        }
    });

    $("button.btn-upload").on("click", function(e){
        e.preventDefault();
        var token = $(this).data("token");
        $(`.pilgand[data-token=${token}] input[type='file']`).click();
    });

    $(".pilgand input[type='file']").on("change", function(e){
        var token = $(this).data("token");
        file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function (event) {
                $(`.pilgand[data-token=${token}] img`).attr("src", event.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    $(".deleteImage").on("click", function(){
        var token = $(this).data("token");
        var sign  = $(`.pilgand[data-token=${token}] form`).data("sign");
        
        if(confirm("Yakin menghapus foto ini?")) {
            $.ajax({
                url: "<?= base_url("endpoint/index.php") ?>",
                type: "POST",
                data: {"action":"crud/ujian/msoal","activity":"imgDelete","sign":sign},
                success:function(data){
                    var response = JSON.parse(data);
                    console.log(data);
                    if(response.OK == true) {
                        $(`.pilgand[data-token=${token}] form img`).attr("src","");
                        $(`.pilgand[data-token=${token}] form span`).hide();
                    } else {
                    }
                }, error:function(data){}
            });
        }
    });

    $("button.btn-cancel").on("click", function(e) {
        e.preventDefault();
        token = $(this).data("token");

        var image    = $(`.pilgand[data-token=${token}] img`).data("saved"),
            question = $(`.pilgand[data-token=${token}] textarea[name='question']`).data("saved"),
            pil_a    = $(`.pilgand[data-token=${token}] input[name='choice_a']`).data("saved"),
            pil_b    = $(`.pilgand[data-token=${token}] input[name='choice_b']`).data("saved"),
            pil_c    = $(`.pilgand[data-token=${token}] input[name='choice_c']`).data("saved"),
            pil_d    = $(`.pilgand[data-token=${token}] input[name='choice_d']`).data("saved"),
            va       = $(`.pilgand[data-token=${token}] select[name='va']`).data("saved");

        $(`.pilgand[data-token=${token}] img`).attr("src", image);
        $(`.pilgand[data-token=${token}] input[name='file']`).val('');
        $(`.pilgand[data-token=${token}] textarea[name='question']`).val(question);
        $(`.pilgand[data-token=${token}] input[name='choice_a']`).val(pil_a);
        $(`.pilgand[data-token=${token}] input[name='choice_b']`).val(pil_b);
        $(`.pilgand[data-token=${token}] input[name='choice_c']`).val(pil_c);
        $(`.pilgand[data-token=${token}] input[name='choice_d']`).val(pil_d);
        $(`.pilgand[data-token=${token}] select[name='va']`).val(va)

        $(`.pilgand[data-token=${token}] textarea, .pilgand[data-token=${token}] input, .pilgand[data-token=${token}] button, .pilgand[data-token=${token}] select`).attr("disabled","disabled");
        $(`.pilgand[data-token=${token}] button.btn-edit`).removeAttr("disabled");
    });

    $("button.btn-save").on("click", function(e){
        e.preventDefault();
        token = $(this).data("token");

            var question = $(`.pilgand[data-token=${token}] textarea[name='question']`).val(),
                pil_a    = $(`.pilgand[data-token=${token}] input[name='choice_a']`).val(),
                pil_b    = $(`.pilgand[data-token=${token}] input[name='choice_b']`).val(),
                pil_c    = $(`.pilgand[data-token=${token}] input[name='choice_c']`).val(),
                pil_d    = $(`.pilgand[data-token=${token}] input[name='choice_d']`).val();

        var form = $(`form[data-token=${token}]`)[0],
            formData = new FormData(form),
            sign = $(`form[data-token=${token}]`).data("sign");

        formData.append("action","crud/ujian/msoal");
        formData.append("activity","update");
        formData.append("sign",sign);

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
                    $(`.pilgand[data-token=${token}] textarea`).data("saved", question);
                    $(`.pilgand[data-token=${token}] input[name='choice_a']`).data("saved", pil_a);
                    $(`.pilgand[data-token=${token}] input[name='choice_b']`).data("saved", pil_b);
                    $(`.pilgand[data-token=${token}] input[name='choice_c']`).data("saved", pil_c);
                    $(`.pilgand[data-token=${token}] input[name='choice_d']`).data("saved", pil_d);

                    $(`.pilgand[data-token=${token}] textarea, .pilgand[data-token=${token}] input, .pilgand[data-token=${token}] button, .pilgand[data-token] select`).attr("disabled","disabled");
                    $(`.pilgand[data-token=${token}] button.btn-edit`).removeAttr("disabled");
                } else {
                }
            }, error:function(data){}
        });
    });
</script>