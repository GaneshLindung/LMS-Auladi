<audio controls style="margin-bottom: 16px;display: none;">
    <source src="<?= base_url("assets/sound/five-second-alert.mp3") ?>" type="audio/mpeg">
</audio>
<div class="container-fluid">
    <?Php if (isset($_GET['hash']) && isset($exam[$_GET['hash']]) && in_array($myclass, $exam[$_GET['hash']]['classes'])) {
        $HASH = $_GET['hash'];
        $exash = $exam[$_GET['hash']]; ?>
        <div class="row">
            <?Php if (!isset($_GET['action'])) { ?>
                <div class="col-md-5">
                    <div class="card">
                        <div class="header d-flex space-between">
                            <h4 class="title">Informasi Ujian</h4>
                            <a href="<?= base_url("exam?hash=" . $HASH . "&action=seeMyRecord") ?>" type="button" class="btn btn-sm btn-primary">Lihat Jawaban</a>
                        </div>
                        <div class="content" style="display: grid;gap: 10px;">
                            <style>
                                .col-md-9:before {
                                    content: ": ";
                                }
                            </style>
                            <style>
                                span.ss {
                                    padding: 4px 6px;
                                    border-radius: 4px;
                                    background: #ffc9c9;
                                }
                            </style>
                            <div class="row">
                                <div class="col-md-3"><b>Judul</b></div>
                                <div class="col-md-9"><?= base64_decode($exash['title']) ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><b>Mata Pelajaran</b></div>
                                <div class="col-md-9"><?= $mapel['detail'][$exash['subject_id']]['title'] ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><b>Durasi</b></div>
                                <div class="col-md-9"><?= $exash['duration'] ?> Menit</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><b>Status</b></div>
                                <div class="col-md-9"><?= !isset($exas[$_GET['hash']][$unme]) ? "<span class='text-danger'>Belum dikerjakan</span>" : "<span class='text-success'>Sudah dikerjakan</span>" ?></div>
                            </div>
                            <?Php $Check = query("exam_s", "WHERE exam_id='$HASH' AND nis='$unme'");
                            if (mysqli_num_rows($Check) == 1) {
                                $DATA = mysqli_fetch_array($Check); ?>
                                <div class="row">
                                    <div class="col-md-3"><b>Nilai</b></div>
                                    <div class="col-md-9"><?= $DATA['cstatus'] == 0 ? "Menunggu.." : $DATA['nilai'] ?></div>
                                </div>
                            <?Php } ?>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="content">
                                            <h4 style="margin: 0px 0px 16px;font-weight: bold;">Deskripsi</h4>
                                            <?= nl2br(base64_decode($exash['description'])) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <?Php if (!isset($exas[$_GET['hash']][$unme])) { ?>
                                    <div class="col-md-12 text-muted mt-3">
                                        <p>* Setelah memulai ujian ini, waktu pengerjaan akan dimulai dan Kamu hanya bisa mengerjakan selama batas waktu itu.</p>
                                    </div>
                                    <div class="col-md-6"><input type="password" class="form-control" placeholder="Password akses" /></div>
                                    <div class="col-md-6"><button class="goToExam pull-right btn btn-success btn-fill">Mulai</button></div>
                                    <script>
                                        var hash = "<?= $_GET['hash'] ?>";
                                        $(".goToExam").on("click", function() {
                                            var password = $("input[type='password']").val();
                                            if (confirm("Apakah Kamu yakin mulai mengerjakan soal ujian ini?")) {
                                                $.ajax({
                                                    url: "<?= base_url("endpoint/index.php") ?>",
                                                    type: "POST",
                                                    data: {
                                                        "action": "auth/exam",
                                                        "hash": hash,
                                                        "password": password
                                                    },
                                                    success: function(data) {
                                                        var response = JSON.parse(data);
                                                        console.log(data);
                                                        if (response.OK == true) {
                                                            window.location.replace("<?= base_url("exam?hash=" . $_GET['hash'] . "&action=do") ?>");
                                                        } else {
                                                            alert("Password yang Kamu input salah.");
                                                            $("input[type='password']").val("");
                                                        }
                                                    },
                                                    error: function(data) {}
                                                });
                                            }
                                        });
                                    </script>
                                <?Php } else { ?>
                                    <div class="col-md-12">
                                        <?php if ($exas[$_GET['hash']][$unme]['deadline'] > $timestamp) {
                                            echo "<a href='" . base_url('exam?hash=' . $_GET['hash'] . '&action=do') . "' class='pull-right btn btn-primary btn-fill'>Lanjutkan</a>";
                                        } else {
                                            echo "<a href='" . base_url('exam?hash=' . $_GET['hash'] . '&action=lookup') . "' class='pull-right btn btn-info btn-fill d-none'>Lihat</a>";
                                        }
                                        ?>
                                    </div>
                                <?Php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?Php } else {
                $act = $_GET['action'];
                if ($act == "do") { ?>
                    <style>
                        .title {
                            border-bottom: 1px solid #DFDFDF;
                            padding: 16px;
                            font-weight: bold;
                        }

                        .contx {
                            width: 100%;
                            background: #FFF;
                            border: 1px solid #DFDFDF;
                            margin: 0px auto;
                        }

                        span#soalNo,
                        span#sisaWaktu {
                            display: inline-block;
                            background: #FF9500;
                            color: #FFF;
                            padding: 2px 7px;
                            min-width: 30px;
                            text-align: center;
                        }

                        .soal .question {
                            margin: 0px 16px 16px;
                            padding: 16px;
                            border: 1px dashed #DFDFDF;
                            min-height: 150px;
                        }

                        .soal .choices {
                            margin: 16px;
                        }

                        .soal .match-question {
                            margin: 0px 0px 16px;
                            padding: 0px 16px;
                            border: 1px dashed #DFDFDF;
                        }

                        label {
                            font-weight: 500;
                            margin-left: 10px;
                        }

                        /* .choices div {display: flex;gap: 10px;align-items: center;} */

                        span.tipesoal {
                            display: inline-block;
                            margin: 16px;
                            padding: 5px 16px;
                            background: #c9ffe1;
                            color: #777;
                            border-radius: 3px;
                            font-weight: bold;
                        }
                    </style>
                    <?Php
                    $HASH = $_GET['hash'];
                    $qs = select("exam_s", "nis='$unme' AND exam_id='$HASH'");
                    if (mysqli_num_rows($qs) == 1) {
                        $qdata = mysqli_fetch_array($qs);
                        if ($qdata["stored"] == 0) {
                            if ($qdata['deadline'] > $timestamp) { ?>
                                <div class="col-md-9">
                                    <div class="contx" style="min-height: 60vh;">
                                        <div class="d-flex space-between title">
                                            <span>SOAL NO. <span id="soalNo" style="border-radius: 3px;">1</span></span>
                                            <span style="background: #EFEFEF;">
                                                <span style="padding: 2px 10px;">SISA WAKTU</span>
                                                <span id="sisaWaktu">00:00</span> <!-- Waktu akan ditampilkan di sini -->
                                            </span>
                                            <!-- <span style="background: #EFEFEF;"><span style="padding: 2px 10px;">SISA WAKTU</span><span id="sisaWaktu">00:00:00</span></span> -->
                                        </div>
                                        <div class="contenx">
                                            <form id="ujian">
                                                <?Php
                                                $pilgan = $exash['pilgan'];
                                                $essay  = $exash['essay'];
                                                $multiple = $exash['multiple'];
                                                $match = $exash['match'];

                                                if ($pilgan != "") {
                                                    $GET = query("examqc", "WHERE package='$pilgan' ORDER BY RAND()");
                                                    if (mysqli_num_rows($GET) > 0) {
                                                        $i = 1;
                                                        while ($GDAT = mysqli_fetch_array($GET)) { ?>
                                                            <div class="soal" data-nomor="<?= $i ?>">
                                                                <span class="tipesoal">PILIHAN GANDA</span>
                                                                <div class="question"><?= base64_decode($GDAT['question']) ?></div>
                                                                <div class="choices">
                                                                    <?Php
                                                                    $answers = array(
                                                                        array("v" => "a", "a" => $GDAT['a']),
                                                                        array("v" => "b", "a" => $GDAT['b']),
                                                                        array("v" => "c", "a" => $GDAT['c']),
                                                                        array("v" => "d", "a" => $GDAT['d']),
                                                                        array("v" => "e", "a" => $GDAT['e'])
                                                                    );

                                                                    shuffle($answers);
                                                                    shuffle($answers);
                                                                    shuffle($answers);

                                                                    foreach ($answers as $ans) {
                                                                        $id = randomtoken(64);
                                                                        $crx = "";
                                                                        $ax = isset($_COOKIE[$GDAT["uniqueKey"]]) ? true : false;
                                                                        if ($ax) {
                                                                            $xa = $_COOKIE[$GDAT["uniqueKey"]];
                                                                            $crx = $xa == $ans["v"] ? "checked" : "";
                                                                        }
                                                                        echo "<div><span class='aradio'><input type='radio' name='answer[pilgan][" . $GDAT['uniqueKey'] . "]' id='" . $id . "' data-no='" . $i . "' data-hash='" . $GDAT['uniqueKey'] . "' value='" . $ans['v'] . "' " . $crx . "/> <label for='" . $id . "'>" . base64_decode($ans['a']) . "</label></span></div>";
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?Php $i++;
                                                        }
                                                    }
                                                }
                                                if ($essay != "") {
                                                    $GET = query("examqe", "WHERE package='$essay' ORDER BY RAND()");
                                                    if (mysqli_num_rows($GET) > 0) {
                                                        while ($GDAT = mysqli_fetch_array($GET)) { ?>
                                                            <div class="soal" data-nomor="<?= $i ?>">
                                                                <span class="tipesoal">ESSAI</span>
                                                                <div class="question"><?= base64_decode($GDAT['question']) ?></div  >
                                                                <div style='padding: 0px 16px;'><textarea name="answer[essay][<?= $GDAT['uniqueKey'] ?>]" class="jawabEssai form-control" data-hash="<?= $GDAT["uniqueKey"] ?>" data-no="<?= $i ?>" rows="4" placeholder="Jawaban Kamu"><?= $_COOKIE[$GDAT['uniqueKey']] ?? "" ?></textarea></div>
                                                            </div>
                                                <?Php $i++;
                                                        }
                                                    }
                                                } ?>
                                                <?Php if ($multiple != "") {
                                                    $GET = query("examqm", "WHERE package='$multiple' ORDER BY RAND()");
                                                    if (mysqli_num_rows($GET) > 0) {
                                                        while ($GDAT = mysqli_fetch_array($GET)) { ?>
                                                            <div class="soal" data-nomor="<?= $i ?>">
                                                                <span class="tipesoal">MULTIPLE CHOICE</span>
                                                                <div class="question"><?= base64_decode($GDAT['question']) ?></div>
                                                                <div class="choices">
                                                                    <?Php
                                                                    $answers = array(
                                                                        array("v" => "a", "a" => $GDAT['a']),
                                                                        array("v" => "b", "a" => $GDAT['b']),
                                                                        array("v" => "c", "a" => $GDAT['c']),
                                                                        array("v" => "d", "a" => $GDAT['d']),
                                                                        array("v" => "e", "a" => $GDAT['e'])
                                                                    );

                                                                    shuffle($answers);
                                                                    shuffle($answers);
                                                                    shuffle($answers);

                                                                    $checkd = [];
                                                                    if (isset($_COOKIE[$GDAT["uniqueKey"]])) {
                                                                        $checkd = explode(",", $_COOKIE[$GDAT["uniqueKey"]]);
                                                                    }

                                                                    foreach ($answers as $ans) {
                                                                        $id = randomtoken(64);
                                                                        $cond = in_array($ans['v'], $checkd) ? "checked" : "";
                                                                        echo "<div><span class='aradio'><input type='checkbox' name='answer[multiple][" . $GDAT['uniqueKey'] . "][]' id='" . $id . "' value='" . $ans['v'] . "' data-no='" . $i . "' data-hash='" . $GDAT['uniqueKey'] . "' " . $cond . "/> <label for='" . $id . "'>" . base64_decode($ans['a']) . "</label></span></div>";
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                <?Php $i++;
                                                        }
                                                    }
                                                } ?>
                                                <?Php if ($match != "") {
                                                    $matchx["start"] = $i;
                                                    $START = $i; ?>
                                                    <div class="soal" data-tipe="match" data-nomor="00">
                                                        <span class="tipesoal">PENCOCOKAN</span>
                                                        <p style="padding: 0px 16px;">Silahkan pilih diantara jawaban-jawaban ini yang cocok dengan pertanyaan.</p>
                                                        <div style="padding: 0px 16px 16px;">
                                                            <?Php $GET = query("examqp", "WHERE package='$match' ORDER BY ID ASC");
                                                            if (mysqli_num_rows($GET) > 0) {
                                                                while ($GDAT = mysqli_fetch_array($GET)) {
                                                                    $answerx[] = array(
                                                                        "answer_id" => $GDAT["answer_id"],
                                                                        "answer_text" => base64_decode($GDAT["answer_text"])
                                                                    );

                                                                    $questionx[] = array(
                                                                        "question_id" => $GDAT["uniqueKey"],
                                                                        "question_text" => base64_decode($GDAT["question"])
                                                                    );
                                                                }

                                                                $no = $i;
                                                                foreach ($questionx as $questio) { ?>
                                                                    <div class="match-question">
                                                                        <span style="display: inline-block; background: rgba(255, 149, 0, .7); color: #FFF; padding: 4px 14px; border-radius: 6px; min-width: 30px; text-align: center;margin: 16px 0px;"><?= $no ?></span>
                                                                        <?= $questio["question_text"] ?>
                                                                        <div>
                                                                            <select class='form-control match' data-hash="<?= $questio["question_id"] ?>" name="answer[match][<?= $questio["question_id"] ?>][]" style="max-width: 350px;margin-bottom: 16px;">
                                                                                <?php
                                                                                $svd = "xab";
                                                                                if (isset($_COOKIE[$questio["question_id"]])) {
                                                                                    $svd = $_COOKIE[$questio["question_id"]];
                                                                                }
                                                                                foreach ($answerx as $answer) {
                                                                                    $crx = "";
                                                                                    if ($svd != "" && $svd == $answer["answer_id"]) {
                                                                                        $crx = "selected";
                                                                                    }
                                                                                    echo "<option value='" . $answer["answer_id"] . "' " . $crx . ">" . $answer["answer_text"] . "</option>";
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                            <?Php $no++;
                                                                }
                                                            } ?>
                                                            <input type="button" class="checkAnswer" data-no="00" value="SUDAH TERJAWAB SEMUA" style="color: #FFFFFF; background-color: #87CB16; opacity: 1; filter: alpha(opacity=100);border: 0px solid transparent;padding: 10px 16px;" />
                                                        </div>
                                                    </div>
                                                <?Php $matchx["end"] = $START + (count($questionx) - 1);
                                                } ?>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="mt-3 d-flex space-between">
                                        <button class="PrevNext btn btn-sm btn-primary btn-fill" data-tipe="prev"><i class="fa fa-angle-left"></i> <span class="d-sm-none">SOAL SEBELUMNYA</span></button>
                                        <button class="soalDoubt btn btn-sm btn-warning btn-fill"><i class="fa fa-square-o"></i> RAGU - RAGU</button>
                                        <button class="PrevNext btn btn-sm btn-primary btn-fill" data-tipe="next"><span class="d-sm-none">SOAL SELANJUTNYA</span> <i class="fa fa-angle-right"></i></button>
                                    </div>
                                    <div class="mt-3"></div>
                                </div>
                                <style>
                                    .toSoal {
                                        width: 100%;
                                        object-fit: cover;
                                    }
                                </style>
                                <div class="col-md-3">
                                    <div class="contx">
                                        <div class="title">
                                            <span style="display: inline-block;padding: 2px 0px;">NOMOR SOAL</span>
                                        </div>
                                        <div style='padding: 16px;display: grid;gap: 10px;grid-template-columns: repeat(5, 1fr);'>
                                            <?php for ($a = 1; $a <= ($i - 1); $a++) {
                                                echo '<button class="toSoal btn btn-fill" data-target="' . $a . '" style="aspect-ratio: 1/1">' . $a . '</button>';
                                            } ?>
                                        </div>
                                        <?Php if (isset($matchx)) {
                                            echo "<div style='padding: 0px 16px;display: grid;gap: 10px;grid-template-columns: repeat(1, 1fr);'>";
                                            echo "<button class='toSoal btn btn-fill match' data-target='00' data-from-to='" . $matchx["start"] . " - " . $matchx["end"] . "'>SOAL PENCOCOKAN ( " . $matchx["start"] . " - " . $matchx["end"] . " )</button>";
                                            echo "</div>";
                                        } ?>
                                        <div style="padding: 16px;">
                                            <div><i class="fa fa-square text-success"></i> = Sudah dijawab</div>
                                            <div><i class="fa fa-square text-warning"></i> = Ragu-ragu</div>
                                            <div><i class="fa fa-square" style="color: #888888;"></i> = Belum dijawab</div>
                                        </div>
                                        <div style="padding: 16px;">
                                            <input id="finishUjian" type="button" class="btn btn-success btn-fill" value="SELESAIKAN UJIAN" />
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    document.addEventListener("DOMContentLoaded", function() { // Pastikan script berjalan setelah halaman dimuat
                                        function startCountdown(minutes) {
                                            let timeInSeconds = minutes * 60; // Konversi menit ke detik
                                            let display = document.getElementById("sisaWaktu"); // Ambil elemen untuk menampilkan waktu

                                            function updateTimer() {
                                                let minutesLeft = Math.floor(timeInSeconds / 60);
                                                let secondsLeft = timeInSeconds % 60;

                                                // Format waktu agar selalu dua digit (misal: 09:05)
                                                let formattedTime =
                                                    (minutesLeft < 10 ? "0" : "") + minutesLeft + ":" +
                                                    (secondsLeft < 10 ? "0" : "") + secondsLeft;

                                                display.textContent = formattedTime; // Update tampilan di halaman

                                                if (timeInSeconds > 0) {
                                                    timeInSeconds--;
                                                    setTimeout(updateTimer, 1000); // Update setiap 1 detik
                                                } else {
                                                    display.textContent = "WAKTU HABIS"; // Teks saat waktu habis
                                                    alert("Waktu telah habis!"); // Notifikasi ke user
                                                    // Tambahkan action lain jika perlu, seperti auto-submit form
                                                    $("#finishUjian").click();
                                                }
                                            }

                                            updateTimer(); // Jalankan fungsi pertama kali
                                        }

                                        // Ambil waktu dari PHP dan jalankan countdown otomatis
                                        <?php
                                        $waktuUjian = $exash['duration']; // Bisa diganti dengan $exash['waktu'] dari database
                                        // $waktuUjian = 0.2; // Bisa diganti dengan $exash['waktu'] dari database
                                        ?>
                                        startCountdown(<?= $waktuUjian ?>); // Jalankan countdown otomatis saat halaman dimuat
                                    });


                                    function setCookie(name, value) {
                                        // console.log(name + ", " + value);
                                        let tanggalKadaluarsa = new Date();
                                        tanggalKadaluarsa.setTime(tanggalKadaluarsa.getTime() + (24 * 60 * 60 * 1000));
                                        let expires = "expires=" + tanggalKadaluarsa.toUTCString();
                                        document.cookie = name + "=" + value + ";" + expires + ";path=/";
                                    } // setCookie("username", "JohnDoe");

                                    $(".soal:not(:first-child)").hide();
                                    $(".soal:first-child").addClass("show");

                                    // $("div.choices div:first-child span.aradio input[type='radio']").attr("checked", true);

                                    $("input[type='radio']").on("click", function() {
                                        var no = $(this).data("no"),
                                            hs = $(this).data("hash"),
                                            vl = $(this).val();

                                        setCookie(hs, vl);

                                        $(`.toSoal[data-target=${no}]`).removeClass("btn-warning").addClass("btn-success");
                                    });

                                    $("textarea.jawabEssai").on("change", function() {
                                        var no = $(this).data("no"),
                                            hs = $(this).data("hash"),
                                            vl = $(`textarea.jawabEssai[data-hash=${hs}]`).val();

                                        setCookie(hs, vl);

                                        if ($(this).val() != "") {
                                            $(`.toSoal[data-target=${no}]`).removeClass("btn-warning").addClass("btn-success");
                                        } else {
                                            $(`.toSoal[data-target=${no}]`).removeClass("btn-success").removeClass("btn-warning");
                                        }

                                    });

                                    $("input[type='checkbox']").on("click", function() {
                                        var limit = 3,
                                            no = $(this).data("no"),
                                            hs = $(this).data("hash"),
                                            vl = $(`input[type='checkbox'][data-hash=${hs}]:checked`).map(function() {
                                                return this.value;
                                            }).get(),
                                            st = $(`input[type='checkbox'][data-hash=${hs}]:checked`).length;

                                        // console.log(st);
                                        if (st >= limit) {
                                            this.checked = false;
                                        } else {
                                            setCookie(hs, vl);
                                            $(`.toSoal[data-target=${no}]`).removeClass("btn-warning").addClass("btn-success");
                                        }
                                    });

                                    $("select.match").on("change", function() {
                                        var hs = $(this).data("hash"),
                                            vl = $(this).val();

                                        setCookie(hs, vl);
                                    });

                                    $(".soalDoubt").on("click", function() {
                                        var no = $(".soal.show").data("nomor");
                                        if (!$(`.toSoal[data-target=${no}]`).hasClass("btn-warning")) {
                                            $(`.toSoal[data-target=${no}]`).removeClass("btn-success").addClass("btn-warning");
                                        } else {
                                            $(`.toSoal[data-target=${no}]`).removeClass("btn-warning").addClass("btn-success");
                                        }
                                    });

                                    $(".PrevNext").on("click", function() {
                                        var now = $(".soal.show").data("nomor");
                                        var act = $(this).data("tipe");

                                        if (act == "prev" && now > 1) {
                                            target = now - 1;
                                            $(".soal").hide().removeClass("show");
                                            $(`.soal[data-nomor=${target}]`).show().addClass("show");
                                            $("#soalNo").html(target);
                                        } else if (act == "next" && now < <?= $i - 1 ?>) {
                                            target = now + 1;
                                            $(".soal").hide().removeClass("show");
                                            $(`.soal[data-nomor=${target}]`).show().addClass("show");
                                            $("#soalNo").html(target);
                                        }
                                    });

                                    $(".toSoal").on("click", function() {
                                        var target = $(this).data("target");
                                        $(".soal").hide().removeClass("show");
                                        $(`.soal[data-nomor=${target}]`).addClass("show").show();

                                        if (!$(this).hasClass("match")) {
                                            $("span#soalNo").html(target);
                                        } else {
                                            var fromto = $(this).data("from-to");
                                            $("span#soalNo").html(fromto);
                                        }
                                    });

                                    $("#finishUjian").on("click", function() {
                                        var form = $("form#ujian")[0],
                                            formData = new FormData(form);
                                        formData.append("action", "process/sendUjian");
                                        formData.append("examid", "<?= $_GET['hash'] ?>");
                                        $.ajax({
                                            url: "<?= base_url("endpoint/index.php") ?>",
                                            type: "POST",
                                            data: formData,
                                            processData: false,
                                            contentType: false,
                                            success: function(data) {
                                                var response = JSON.parse(data);
                                                console.log(data);
                                                if (response.OK == true) {
                                                    window.location.replace("?hash=<?= $_GET['hash'] ?>");
                                                }
                                            },
                                            error: function(data) {}
                                        });
                                    });

                                    $(".checkAnswer").on("click", function() {
                                        number = $(this).data("no");
                                        $(`.toSoal[data-target=${number}]`).removeClass("btn-warning").addClass("btn-success");
                                    });

                                    function countdown(unixTimestamp) {
                                    var now=Math.floor(Date.now() / 1000);
                                    var difference=unixTimestamp - now;

                                    var hours=Math.floor(difference / 3600);
                                    var minutes=Math.floor((difference % 3600) / 60);
                                    var seconds=difference % 60;

                                    $("span#sisaWaktu").html(hours + ":" + minutes + ":" + seconds);

                                    if (difference> 0) {
                                    setTimeout(function() {
                                    countdown(unixTimestamp);
                                    }, 1000);
                                    } else {
                                    $("#finishUjian").click();
                                    }
                                    }

                                    countdown(<?= $qdata['deadline'] ?>);

                                    var audio = $("audio")[0];

                                    setInterval(function(){
                                    if (document.hidden) {
                                    audio.play();
                                    } else {
                                    audio.pause();
                                    audio.currentTime = 0;
                                    }
                                    }, 100);

                                    audio.addEventListener("ended", function () {
                                    $("#finishUjian").click();
                                    window.location.replace("?hash=<?= $_GET['hash'] ?>");
                                    });
                                </script>
                            <?Php
                            }
                        } else { ?>
                            <div class="col-md-12">
                                <div class="card" style="padding: 32px;">
                                    <h2>Sudah dikumpulkan</h2>
                                    <p>Kamu sudah mengumpulkan tugas ini.</p>
                                </div>
                            </div>
                    <?Php }
                    }
                } elseif ($act == "seeMyRecord" && isset($_GET['hash']) && isset($exas[$_GET['hash']][$unme])) { ?>
                    <div style="background-color: white; border-radius: 10px; margin: 10px; padding: 10px 40px;">
                        <div id="pilgan">
                            <div class="d-flex space-between">
                                <h4>Pilgan</h4>

                            </div>
                            <?Php $GET = query("examrec", "WHERE qtype='pilgan' AND exam_id='$HASH' AND nis='$unme'");
                            $no = 1;
                            while ($data = mysqli_fetch_array($GET)) { ?>
                                <div class="pilgan soal">
                                    <p class="question"><?= base64_decode($exac[$data['question_id']]['question']) ?></p>
                                    <?Php foreach ($exac[$data['question_id']]['option'] as $key => $val) { ?>
                                        <div><input type='radio' <?= $data['answer'] == $key ? "checked" : "disabled" ?>> <span class="<?= $exac[$data['question_id']]['true'] == $key ? "right" : "wrong" ?>"><?= base64_decode($val) ?></span></div>
                                    <?Php } ?>
                                </div>
                            <?Php $no++;
                            } ?>
                        </div>
                        <div id="essays">
                            <div class="d-flex space-between">
                                <h4>Essay</h4>

                            </div>
                            <?Php $GET = query("examrec", "WHERE qtype='essay' AND exam_id='$HASH' AND nis='$unme'");
                            while ($data = mysqli_fetch_array($GET)) { ?>
                                <div class="essay soal <?= $data['cstatus'] == "1" ? ($data['evaluation'] == 1 ? "correct" : "wrong") : "" ?>" data-question-id="<?= $data['question_id'] ?>">
                                    <p class="question"><?= base64_decode($exae[$data['question_id']]['question']) ?></p>
                                    <textarea class="form-control" readonly><?= $data['answer'] ?></textarea>
                                </div>
                            <?Php $no++;
                            } ?>
                        </div>
                        <div id="multichoice">
                            <div class="d-flex space-between">
                                <h4>Multiple Choice</h4>

                            </div>
                            <?Php $GET = query("examrec", "WHERE qtype='multiple' AND exam_id='$HASH' AND nis='$unme'");
                            while ($data = mysqli_fetch_array($GET)) { ?>
                                <div class="multiple soal" data-question-id="<?= $data['question_id'] ?>">
                                    <p class="question"><?= base64_decode($exmc[$data['question_id']]['question']) ?></p>
                                    <?Php foreach ($exmc[$data['question_id']]['option'] as $key => $val) {
                                        $true = $exmc[$data['question_id']]['true'];
                                        $anw = json_decode($data['answer']); ?>
                                        <div><input type='checkbox' <?= in_array($key, $anw) ? "checked" : "" ?>> <span class="<?= in_array($key, $true) ? "right" : "wrong" ?>"><?= base64_decode($val) ?></span></div>
                                    <?Php } ?>
                                </div>
                            <?Php $no++;
                            } ?>
                        </div>
                        <div id="match">
                            <div class="d-flex space-between">
                                <h4>Pencocokan</h4>

                            </div>
                            <style>
                                span.result {
                                    font-size: 20px;
                                }

                                span.result.true:before {
                                    content: "";
                                    color: green;
                                }

                                span.result.false:before {
                                    content: "";
                                    color: red;
                                }
                            </style>
                            <?Php $GET = query("examrec", "WHERE qtype='match' AND exam_id='$HASH' AND nis='$unme'");
                            while ($data = mysqli_fetch_array($GET)) { ?>
                                <div class="match soal" data-question-id="<?= $data['question_id'] ?>">
                                    <p class="question"><?= base64_decode($exmm[$data['question_id']]['question']) ?></p>
                                    <p>
                                        <b>Jawaban :</b>
                                        <span><?= base64_decode($excp[$data['answer']]) ?></span>
                                        <span class="result <?= $data['answer'] == $exmm[$data['question_id']]['answer_id'] ? "true" : "false" ?>"></span>
                                    </p>
                                </div>
                            <?Php $no++;
                            } ?>
                        </div>
                    </div>

        <?Php
                }
            }
        } ?>
        </div>
</div>