<div class="col-md-6">
    <div class="card">
        <div class="header"><h4 class="title">Informasi Paket Soal</h4></div>
        <div class="content">
            <div class="row"><div class="col-md-12 form-group"><label>Judul</label><input type="text" name="packageTitle" class="form-control" value="<?= $soal[$hash]['title'] ?? "ERROR" ?>" /></div></div>
            <div class="row">
                <div class="col-md-12">
                    <input type="button" class="btn btn-danger" value="Delete" onclick="deletePackage()" />
                    <input type="button" id="saveInfo" class="pull-right btn btn-success btn-fill" value="Update" />
                </div>
            </div>
        </div>
    </div>
    <script>
        function deletePackage() {
            var convirm = prompt("Ketik 'confirm' untuk melanjutkan tindakan ini");
            if (convirm == "confirm") {
                $.ajax({
                    url: "<?= base_url("endpoint/index.php") ?>",
                    type: "POST",
                    data: {
                        "action":"crud/ujian/soal",
                        "activity":"delete",
                        "hash":"<?= $_GET['hash'] ?>"
                    },
                    success:function(data){
                        var response = JSON.parse(data);
                        console.log(data);
                        if(response.OK == true) {
                            window.location.replace("<?= base_url("soal") ?>");
                        } else {
                            alert("Gagal");
                        }
                    }, error:function(data){}
                });
            }
        }

        $("#saveInfo").on("click", function(){
            var title = $("input[name='packageTitle']").val();

            $.ajax({
                url: "<?= base_url("endpoint/index.php") ?>",
                type: "POST",
                data: {"action":"crud/ujian/csoal","activity":"update_title","hash":"<?= $hash; ?>","title":title},
                success:function(data){
                    var response = JSON.parse(data);
                    console.log(data);
                    if(response.OK == true) {
                        alert("Berhasil memperbarui nama soal");
                    }
                }, error:function(data){}
            });
        });
    </script>
</div>