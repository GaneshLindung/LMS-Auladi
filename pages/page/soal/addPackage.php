<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="header">
                <h4 class="title">Tambah Paket Soal</h4>
            </div>
            <div class="content">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>Judul</label>
                        <input type="text" name="packageTitle" class="form-control" placeholder="Judul Paket Ujian" />
                    </div>
                    <div class="col-md-12 form-group">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Mata Pelajaran</label>
                                <select name="mapel" class="form-control">
                                    <option value="">- Pilih -</option>
                                    <?Php for($i=0;$i<=count($guru[$unme]['subject'])-1;$i++) { if($mapel['detail'][$guru[$unme]['subject'][$i]]['tipe'] != "ekskul") { ?>
                                        <option value="<?= $guru[$unme]['subject'][$i] ?>"><?= $mapel['detail'][$guru[$unme]['subject'][$i]]['title'] ?> <?= $mapel['detail'][$guru[$unme]['subject'][$i]]['tipe'] == "ekskul" ? "( Ekskul )" : "" ?></option>
                                    <?Php } } ?>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Tipe Soal</label>
                                <select name="tipe" class="form-control">
                                    <option value="choice">Pilihan Ganda</option>
                                    <option value="essay">Essay</option>
                                    <option value="multiple">Multiple Choice</option>
                                    <option value="match">Pencocokan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input type="button" id="saveInfo" class="pull-right btn btn-success btn-fill" value="Simpan" />
                    </div>
                </div>
            </div>
            <script>
                $("#saveInfo").on("click", function(){
                    var title = $("input[name='packageTitle']").val();
                    var tipes = $("select[name='tipe']").val();
                    var mapel = $("select[name='mapel']").val();

                    $.ajax({
                        url: "<?= base_url("endpoint/index.php") ?>",
                        type: "POST",
                        data: {"action":"crud/ujian/create","activity":"add_soal","type":tipes,"title":title,"mapel":mapel},
                        success:function(data){
                            var response = JSON.parse(data);
                            console.log(data);
                            if(response.OK == true && response.hash != "") {
                                window.location.replace("<?= base_url("soal?action=edit&hash=") ?>"+response.hash);
                            }
                        }, error:function(data){}
                    });
                });
            </script>
        </div>
    </div>
</div>