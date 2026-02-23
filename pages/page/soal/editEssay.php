<style>
    .essays {
        margin-bottom: 15px;
        padding: 10px;
        border: 1px solid #DFDFDF;
        border-radius: 3px;
    }

    .option {
        margin-top: 16px;
        display: grid;
        gap: 15px;
        grid-template-columns: repeat(4, 1fr)
    }

    @media (min-width: 992px) {
        .option {
            margin-top: 0px;
        }

        .option {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
<div class="newEssays essays" data-token="add">
    <h5 style="margin: 0px 0px 16px;">Tambah</h5>
    <textarea name="newQuestion" rows="4" class="form-control"></textarea>
    <div class="form-group">
        <label>BOBOT MAKSIMAL SOAL</label>
        <select name="newValue" class="form-control">
            <?php for ($x = 1; $x <= 100; $x++) {
                echo "<option value='" . $x . "'>" . $x . "</option>";
            } ?>
        </select>
    </div>
    <button class="saveNew btn btn-success btn-fill mt-4">Simpan</button>
</div>
<?Php $no = 1;
while ($data = mysqli_fetch_array($GET)) {
    $rtoken = randomtoken(64); ?>
    <div class="essays" data-token="<?= $rtoken ?>" data-sign="<?= $hash . ":" . $data['ID'] ?>">
        <h3 style="margin: 0px 0px 16px;"># <?= $no ?></h3>
        <div class="row">
            <div class="col-md-10">
                <textarea name="question" class="form-control essayQuestion" data-saved="<?= base64_decode($data['question']) ?>" placeholder="Pertanyaan" rows="4" disabled><?= base64_decode($data['question']) ?></textarea>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>BOBOT MAKSIMAL SOAL</label>
                    <select name="value" class="form-control" data-saved="<?= $data['value'] ?>" disabled>
                        <?php for ($x = 1; $x <= 100; $x++) {
                            $crx = $x == $data["value"] ? "selected" : "";
                            echo "<option value='" . $x . "' " . $crx . ">" . $x . "</option>";
                        } ?>
                    </select>
                </div>
                <div class="option" style="margin-top: 15px;">
                    <button class="btn-edit btn btn-warning btn-fill" data-token="<?= $rtoken ?>" data-id="<?= $data['ID'] ?>"><i class="fa fa-edit"></i></button>
                    <button class="btn-cancel btn btn-danger btn-fill" data-token="<?= $rtoken ?>" data-id="<?= $data['ID'] ?>" disabled><i class="fa fa-close"></i></button>
                    <button class="btn-delete btn btn-danger" data-token="<?= $rtoken ?>" data-id="<?= $data['ID'] ?>" disabled><i class="fa fa-trash"></i></button>
                    <button class="btn-save btn btn-success btn-fill" data-token="<?= $rtoken ?>" data-id="<?= $data['ID'] ?>" disabled><i class="fa fa-save"></i></button>
                </div>
            </div>
        </div>
    </div><?Php $no++;
        } ?>
<script>
    $(document).ready(function() {
        $("textarea").summernote({
            height: 150,
        })
    });

    $(".newEssays").hide();
    $(".btn-add").on("click", function() {
        if (!$(this).hasClass("active")) {
            $(".newEssays").show();
            $(this).addClass("active");
        } else {
            $(".newEssays").hide();
            $(this).removeClass("active");
        }
    });

    $(".saveNew").on("click", function(e) {
        e.preventDefault();
        var hash = "<?= $_GET['hash'] ?>",
            question = $(`textarea[name='newQuestion']`).val(),
            value = $(`select[name='newValue']`).val();

        $.ajax({
            url: "<?= base_url("endpoint/index.php") ?>",
            type: "POST",
            data: {
                "action": "crud/ujian/esoal",
                "activity": "add",
                "hash": hash,
                "question": question,
                "value": value
            },
            success: function(data) {
                var response = JSON.parse(data);
                console.log(data);
                if (response.OK == true) {
                    $(".saveNew").attr("disabled", "disabled");
                    window.location.reload();
                }
            },
            error: function(data) {}
        });
    });

    $("button.btn-edit").on("click", function(e) {
        e.preventDefault();
        var token = $(this).data("token");
        $(`.essays[data-token=${token}]`).addClass("editable");
        $(`.essays[data-token=${token}] textarea, .essays[data-token=${token}] input, .essays[data-token=${token}] button, .essays[data-token=${token}] select`).removeAttr("disabled");
        $(this).attr("disabled", "disabled");
    });

    $("button.btn-cancel").on("click", function(e) {
        e.preventDefault();
        token = $(this).data("token");

        var question = $(`.essays[data-token=${token}] textarea[name='question']`).data("saved");
        $(`.essays[data-token=${token}] textarea[name='question']`).val(question);

        $(`.essays[data-token=${token}] textarea, .essays[data-token=${token}] input, .essays[data-token=${token}] button`).attr("disabled", "disabled");
        $(`.essays[data-token=${token}] button.btn-edit`).removeAttr("disabled");
    });

    $("button.btn-delete").on("click", function(e) {
        e.preventDefault();
        var token = $(this).data("token");
        var sign = $(`.essays[data-token=${token}]`).data("sign");

        if (confirm("Apakah Anda yakin ingin menghapus essai ini?")) {
            $.ajax({
                url: "<?= base_url("endpoint/index.php") ?>",
                type: "POST",
                data: {
                    "action": "crud/ujian/esoal",
                    "activity": "delete",
                    "sign": sign
                },
                success: function(data) {
                    var response = JSON.parse(data);
                    console.log(data);
                    if (response.OK == true) {
                        $(`.essays[data-token=${token}]`).remove();
                    }
                },
                error: function(data) {}
            });
        }
    });

    $(".btn-save").on("click", function(e) {
        e.preventDefault();
        token = $(this).data("token");
        var sign = $(`.essays[data-token=${token}]`).data("sign");

        var question = $(`.essays[data-token=${token}] textarea[name='question']`).val();
        var value = $(`.essays[data-token=${token}] select[name='value']`).val();

        $.ajax({
            url: "<?= base_url("endpoint/index.php") ?>",
            type: "POST",
            data: {
                "action": "crud/ujian/esoal",
                "activity": "update",
                "sign": sign,
                "question": question,
                "value": value
            },
            success: function(data) {
                var response = JSON.parse(data);
                console.log(data);
                if (response.OK == true) {
                    $(`.essays[data-token=${token}] textarea`).data("saved", question);

                    $(`.essays[data-token=${token}] textarea, .essays[data-token=${token}] button`).attr("disabled", "disabled");
                    $(`.essays[data-token=${token}] button.btn-edit`).removeAttr("disabled");
                } else {}
            },
            error: function(data) {}
        });
    });
</script>