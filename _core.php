<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
$timestamp = $_SERVER['REQUEST_TIME'];

if (!isset($_GET['logout'])) {
    $conn = mysqli_connect("localhost", "root", "", "sitaulad_lms_jakabaring");

    $site = array(
        "year" => "2024/2025",
        "base_url" => "http://localhost/auladi-lms-testing/",
        "long_name" => "Pembelajaran Digital SIT Auladi Palembang",
        "shortname" => "SIT Auladi Palembang"
    );

    function base_url($subdir)
    {
        global $site;
        return $site['base_url'] . $subdir;
    }
    function  query($table, $filter)
    {
        global $conn;
        return mysqli_query($conn, "SELECT * FROM $table $filter");
    }
    function select($table, $filter)
    {
        global $conn;
        return mysqli_query($conn, "SELECT * FROM $table WHERE $filter");
    }
    function insert($table, $new)
    {
        global $conn;
        return mysqli_query($conn, "INSERT INTO $table SET $new");
    }
    function update($table, $new, $filter)
    {
        global $conn;
        return mysqli_query($conn, "UPDATE $table SET $new WHERE $filter");
    }
    function delete($table, $filter)
    {
        global $conn;
        return mysqli_query($conn, "DELETE FROM $table WHERE $filter");
    }

    function upload($source, $target)
    {
        global $upload;
        if (!file_exists($target)) {
            $tmp_name = $source['tmp_name'];
            $exs = pathinfo($source['name'], PATHINFO_EXTENSION);
            if (move_uploaded_file($tmp_name, $target)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function decodeHtml($string)
    {
        $first = str_replace("<", "&lt;", $string);
        $twist = str_replace(">", "&gt;", $first);
        return $twist;
    }

    $hashkey = "OIHOHIOoihOIH989H8HN9UAN9IH9hnoij9986WQ8";
    function h4sh($str)
    {
        global $hashkey;
        return md5($hashkey . $str . $hashkey);
    }
    function ujian($str)
    {
        return md5("iohPOUIH3PO4UH24PO2HQJPOAIHPOUEHOhnuohar9supioahKMIOH8giuoh" . $str . "iohPOUIH3PO4UH24PO2HQJPOAIHPOUEHOhnuohar9supioahKMIOH8giuoh");
    }
    function toidr($int)
    {
        return number_format($int, 0, '', '.');
    }
    function fjoin($str1, $ext)
    {
        return $str1 . "." . $ext;
    }
    function randomtoken($n)
    {
        $n = $n / 2;
        $token = bin2hex(random_bytes($n));
        return $token;
    }

    $defaultDP = base_url("static/image/display_picture/1HL3PQ.png");

    $class = [];
    $mapel = [];
    $siswa = [];
    $guru  = [];

    $qclass = query("class", "ORDER BY ID ASC");
    $class['info']['0'] = "Ekskul";
    while ($csdata = mysqli_fetch_array($qclass)) {
        $class['info'][$csdata['ID']] = $csdata['class_name'];
        $class['array'][] = array(
            "ID" => $csdata['ID'],
            "name" => $csdata['class_name']
        );

        $class['detail'][$csdata['ID']] = $csdata['class_name'];
    }

    $gender = array("L" => "Laki-laki", "P" => "Perempuan");

    $gmapel = query("subject", "ORDER BY ID ASC");
    while ($dst = mysqli_fetch_array($gmapel)) {
        if ($dst['tipesubject'] == "umum") {
            $mapel["tipe"]["umum"][] = array(
                "ID" => (int)$dst['ID'],
                "NAME" => $dst['subject_title']
            );
        } elseif ($dst['tipesubject'] == "ekskul") {
            $mapel['tipe']['ekskul'][] = array(
                "ID" => (int)$dst['ID'],
                "NAME" => $dst['subject_title']
            );
        }

        $mapel["detail"][$dst['ID']] = array(
            "tipe" => $dst['tipesubject'],
            "title" => $dst['subject_title']
        );

        $mapel['array'][] = array(
            "ID" => $dst['ID'],
            "tipe" => $dst['tipesubject'],
            "title" => $dst['subject_title']
        );

        $mapel['ids'][$dst['tipesubject']][] = $dst['ID'];
    }

    $users['data']['admin'] = array("sign" => "Admin", "name" => "Admin", "dp" => "admin.png");

    $gguru = query("teacher", "ORDER BY ID ASC");
    while ($dsg = mysqli_fetch_array($gguru)) {
        $guru['array'][] = array(
            "ID" => (int)$dsg['ID'],
            "nip" => $dsg['nip'],
            "name" => $dsg['full_name']
        );

        $guru[$dsg['nip']] = array(
            "ID" => (int)$dsg['ID'],
            "nip" => $dsg['nip'],
            "name" => $dsg['full_name'],
            "gender" => $dsg['gender'],
            "birthloc" => $dsg['birthloc'],
            "birthdate" => $dsg['birthdate'],
            "contact" => array(
                "phone" => $dsg['phone'],
                "email" => $dsg['email']
            ),
            "address" => $dsg['address'],
            "classes" => [],
            "subject" => [],
            "sclass" => [],
            "dp" => $dsg['photo']
        );

        $users['data'][$dsg['nip']] = array(
            "sign" => $dsg['sign'] == "" ? "Guru" : $dsg['sign'],
            "as" => "Guru",
            "name" => $dsg['full_name'],
            "dp" => $dsg['photo']
        );
    }

    $subjetx = query("subjetc", "ORDER BY ID ASC");
    while ($dsx = mysqli_fetch_array($subjetx)) {
        $subjetc[] = $dsx['class'] . ":" . $dsx['subject_id'] . ":" . $dsx['nip'];
        $dsx['nip'] != "-" && in_array($dsx['class'], $guru[$dsx['nip']]['classes']) ? $hello = true : $guru[$dsx['nip']]['classes'][] = $dsx['class'];
        $dsx['nip'] != "-" && in_array($dsx['subject_id'], $guru[$dsx['nip']]['subject']) ? $hello = true : $guru[$dsx['nip']]['subject'][] = $dsx['subject_id'];

        $classes['teacher'][$dsx['nip']]["x" . $dsx['class']][] = $dsx['subject_id'];
        $classes['class']["x" . $dsx['class']][$dsx['subject_id']][] = $dsx['nip'];

        $mapel['detail'][$dsx['subject_id']]['teachers'][$dsx['class']] = $dsx['nip'];

        $class['more_info'][$dsx['class']]['mapel'][] = $dsx['subject_id'];

        $guru[$dsx['nip']]['sclass'][$dsx['subject_id']][] = $dsx['class'];
    }

    $class['info']['99999'] = "<span class='text-danger'>undefined</span>";
    $class['detail']['99999'] = "<span class='text-danger'>undefined</span>";
    $class['more_info']['99999']['mapel'] = [99999];

    $student = query("siswa", "ORDER BY nis ASC");
    while ($dst = mysqli_fetch_array($student)) {
        $siswa[$dst['nis']] = array(
            "ID" => $dst['ID'],
            "name" => $dst['full_name'],
            "class" => $dst['class'],
            "gender" => $dst['gender'],
            "birthloc" => $dst['birthloc'],
            "birthdate" => $dst['birthdate'],
            "contact" => array(
                "phone" => $dst['phone'],
                "email" => $dst['email']
            ),
            "address" => $dst['address'],
            "birth" => array(
                "location" => $dst['birthloc'],
                "date" => $dst['birthdate']
            ),
            "dp" => $dst['photo'] != "" ? $dst['photo'] : "1HL3PQ.png",
            "ekskul" => [99999999999]
        );

        $users['data'][$dst['nis']] = array(
            "sign" => "Siswa",
            "as" => "Siswa",
            "name" => $dst['full_name'],
            "dp" => $dst['photo']
        );

        $classes['student'][$dst['nis']]['class'] = $dst['class'];
    }

    $gsubsjs = query("subjecs", "ORDER BY ID ASC");
    while ($dss = mysqli_fetch_array($gsubsjs)) {
        $siswa[$dss['nis']]['ekskul'][] = $dss['subject_id'];
    }

    $users['deactivated'] = [0];

    $gusers  = query("user", "WHERE status='0' ORDER BY ID ASC");
    while ($du = mysqli_fetch_array($gusers)) {
        $users['deactivated'][] = $du['username'];
    }

    $deactivatedU = implode(",", $users['deactivated']);

    $chat = [];
    $gchat = query("chat", "ORDER BY ID ASC");
    while ($ch = mysqli_fetch_array($gchat)) {
        $chat[$ch['ID']] = array(
            "star" => (int)$ch['startime'],
            "last" => (int)$ch['startime'],
            "lastChat" => array(
                "from" => $ch['sender'],
                "text" => $ch['text']
            )
        );
    }

    $gchat = query("chattalk", "ORDER BY ID ASC");
    if (mysqli_num_rows($gchat) > 0) {
        while ($ch = mysqli_fetch_array($gchat)) {
            $chat[$ch['chat_id']]['last'] = $ch['time'];
            $chat[$ch['chat_id']]["lastChat"] = array(
                "from" => $ch['sender'],
                "text" => $ch['text']
            );
        }
    }

    $materi = [];
    $gmat = query("materi", "ORDER BY ID DESC");
    if (mysqli_num_rows($gmat) > 0) {
        while ($mt = mysqli_fetch_array($gmat)) {
            $materi['data'][$mt['ID']] = array(
                "title" => $mt['title'],
                "file" => $mt['file_name'],
                "subject_id" => $mt['subject_id'],
                "bab" => $mt['bab'],
                "classes" => []
            );
        }
    }

    $gmtd = query("materi_d", "");
    if (mysqli_num_rows($gmtd)) {
        while ($md = mysqli_fetch_array($gmtd)) {
            $materi['data'][$md['materi_id']]['classes'][] = $md['class'];
        }
    }

    $video = [];
    $gvid = query("video", "ORDER BY ID DESC");
    if (mysqli_num_rows($gmat) > 0) {
        while ($mt = mysqli_fetch_array($gvid)) {
            $video['data'][$mt['ID']] = array(
                "title" => $mt['title'],
                "url" => $mt['url'],
                "subject_id" => $mt['subject_id'],
                "bab" => $mt['bab'],
                "classes" => [],
                "uploader" => $mt['uploader']
            );

            $video['mapel'][$mt['subject_id']][] = $mt['ID'];
        }
    }

    $gvde = query("video_d", "");
    if (mysqli_num_rows($gvde)) {
        while ($vd = mysqli_fetch_array($gvde)) {
            $video['data'][$vd['video_id']]['classes'][] = $vd['class'];

            $video['kelas'][$vd['class']][] = [];
        }
    }

    $exam = [];
    $gexm = query("examl", "");
    if (mysqli_num_rows($gexm) > 0) {
        while ($ex = mysqli_fetch_array($gexm)) {
            $exam[$ex['exam_id']] = array(
                "title" => $ex['title'],
                "uploader" => $ex['uploader'],
                "subject_id" => $ex['subject_id'],
                "publish_status" => $ex['publish_status'],
                "duration" => $ex['duration'],
                "description" => $ex['description'],
                "classes" => [],
                "ppctg" => $ex['percent_p'],
                "pilgan" => $ex['pilgan'],
                "epctg" => $ex['percent_e'],
                "essay" => $ex['essay'],
                "mpctg" => $ex['percent_m'],
                "multiple" => $ex['multiple'],
                "ppctg" => $ex['percent_c'],
                "match" => $ex['cocok']
            );
        }
    }

    $exva = array();

    $exad = query("exam_d", "");
    if (mysqli_num_rows($exad) > 0) {
        while ($ex = mysqli_fetch_array($exad)) {
            $exam[$ex['exam_id']]['classes'][] = $ex['class'];
        }
    }

    $soal = [];
    $gsal = query("exam_p", "");
    if (mysqli_num_rows($gsal) > 0) {
        while ($sd = mysqli_fetch_array($gsal)) {
            $soal[$sd['hash']] = array(
                "uploader" => $sd['uploader'],
                "title" => $sd['title'],
                "subject_id" => $sd['subject_id'],
                "tipe" => $sd['type']
            );
        }
    }

    $exac = [];
    $excc = query("examqc", "ORDER BY ID ASC");
    if (mysqli_num_rows($excc) > 0) {
        while ($dq = mysqli_fetch_array($excc)) {
            $exac[$dq['uniqueKey']] = array(
                "question" => $dq['question'],
                "option" => array(
                    "a" => $dq['a'],
                    "b" => $dq['b'],
                    "c" => $dq['c'],
                    "d" => $dq['d'],
                    "e" => $dq['e']
                ),
                "true" => $dq['va']
            );

            $exva[$dq['uniqueKey']] = (int)$dq['value'];
        }
    }

    $exae = [];
    $exee = query("examqe", "ORDER BY ID ASC");
    if (mysqli_num_rows($excc) > 0) {
        while ($dq = mysqli_fetch_array($exee)) {
            $exae[$dq['uniqueKey']] = array(
                "question" => $dq['question'],
                "max_value" => $dq['value']
            );

            $exva[$dq['uniqueKey']] = (int)$dq['value'];
        }
    }

    $exmc = [];
    $excc = query("examqm", "ORDER BY ID ASC");
    if (mysqli_num_rows($excc) > 0) {
        while ($dq = mysqli_fetch_array($excc)) {
            $exmc[$dq['uniqueKey']] = array(
                "question" => $dq['question'],
                "option" => array(
                    "a" => $dq['a'],
                    "b" => $dq['b'],
                    "c" => $dq['c'],
                    "d" => $dq['d'],
                    "e" => $dq['e']
                ),
                "weight" => $dq['value'],
                "true" => json_decode($dq['va'])
            );

            $exva[$dq['uniqueKey']] = (int)$dq['value'];
        }
    }

    $exmm = [];
    $excc = query("examqp", "ORDER BY ID ASC");
    $excp = array();
    if (mysqli_num_rows($excc) > 0) {
        while ($dq = mysqli_fetch_array($excc)) {
            $exmm[$dq['uniqueKey']] = array(
                "question" => $dq['question'],
                "answer_text" => $dq['answer_text'],
                "answer_id" => $dq['answer_id'],
                "value" => (int)$dq['value']
            );

            $excp[$dq['answer_id']] = $dq['answer_text'];

            $exva[$dq['uniqueKey']] = (int)$dq['value'];
        }
    }

    $exas = [];
    $exaq = query("exam_s", "ORDER BY ID ASC");
    if (mysqli_num_rows($exaq) > 0) {
        while ($dq = mysqli_fetch_array($exaq)) {
            $exas[$dq['exam_id']][$dq['nis']] = array(
                "cstatus" => (int)$dq['cstatus'],
                "nilai" => $dq['nilai'],
                "deadline" => (int)$dq['deadline'],
                "stored" => (int)$dq['stored']
            );
        }
    }

    $exrc = [];
    $exar = query("examrec", "ORDER BY exam_id ASC, nis ASC");
    if (mysqli_num_rows($exar) > 0) {
        while ($dr = mysqli_fetch_array($exar)) {
            $exrc[$dr['exam_id']][$dr['nis']][] = array(
                "question_id" => $dr['question_id'],
                "answer" => $dr['answer'],
                "result" => $dr['evaluation']
            );
        }
    }

    $qqq = query("examrec", "WHERE qtype='pilgan' AND cstatus='0'");
    while ($qdata = mysqli_fetch_array($qqq)) {
        $ID = $qdata['ID'];
        $QI = $qdata['question_id'];
        $AN = $qdata['answer'];

        if ($exac[$QI]['true'] == $AN) {
            $NL = "1";
            $value = $exva[$QI];
            $maxvalue = $exva[$QI];
        } else {
            $NL = "0";
            $value = "0";
            $maxvalue = "$exva[$QI]";
        }
        // var_dump($value, $NL, $maxvalue);
        // die();
        update("examrec", "cstatus='1', evaluation='$maxvalue', value='$value'", "ID='$ID'");
    }

    $qqq = query("examrec", "WHERE qtype='match' AND cstatus='0'");
    while ($qdata = mysqli_fetch_array($qqq)) {
        $ID = $qdata['ID'];
        $QI = $qdata['question_id'];
        $AN = $qdata['answer'];

        // Query untuk mengambil jawaban yang benar dari tabel examqp
        $matchingAnswerQuery = query("examqp", "WHERE uniqueKey='$QI'");
        if ($matchingAnswerQuery) {
            $matchingAnswer = mysqli_fetch_array($matchingAnswerQuery); // Ambil data dari hasil query
            $max = $matchingAnswer['value'];
            
            if ($matchingAnswer['answer_id'] == $AN) {
                $NL = "1";
                $value = $matchingAnswer['value'];
            } else {
                $NL = "0";
                $value = "0";
            }
            
            // Update kolom cstatus, evaluation, dan value di examrec
            update("examrec", "cstatus='1', evaluation='$max', value='$value'", "ID='$ID'");
        } else {
            // Jika tidak ada data yang ditemukan, set nilai default
            $max = $matchingAnswer['value'];
            $NL = "0";
            $value = "0";
            update("examrec", "cstatus='1', evaluation='$max', value='$value'", "ID='$ID'");
        }
    }

    $qqq = query("examrec", "WHERE qtype='multiple' AND cstatus='0'");
    while ($qdata = mysqli_fetch_array($qqq)) {
        $ID = $qdata['ID'];
        $QI = $qdata['question_id'];
        $AN = json_decode($qdata['answer']);

        if (count($AN) == 2) {
            $trx = 0;
            $NL = 0;
            foreach ($AN as $answ) {
                if (in_array($answ, $exmc[$QI]['true'])) {
                    $trx++;
                    $NL = 1;
                }
            }
            // Menentukan nilai value berdasarkan $trx
            if ($trx == 2) {
                $value = $exva[$QI]; // Jika $trx = 2, isi value dengan $exva[$QI]
            } elseif ($trx == 1) {
                $value = $exva[$QI] / 2; // Jika $trx = 1, isi value dengan $exva[$QI] / 2
            } else {
                $value = 0; // Jika $trx = 0, isi value dengan 0
            }

            update("examrec", "cstatus='1', evaluation='$exva[$QI]', value='$value'", "ID='$ID'");
        }
    }

    $qqqa = query("examrec", "WHERE qtype='essay' AND cstatus='0'");
    while ($qdata = mysqli_fetch_array($qqqa)) {
        $ID = $qdata['ID'];
        $QI = $qdata['question_id'];

        $esss = query("examqe", "WHERE uniqueKey='$QI'");
        if ($esss_data = mysqli_fetch_array($esss)) {
            $value = $esss_data['value'];
            update("examrec", "cstatus='0', evaluation='$value'", "ID='$ID'");
        }
    }

    $frm = query("forum", "ORDER BY hash ASC");
    while ($fdata = mysqli_fetch_array($frm)) {
        $forum[$fdata['hash']] = array(
            "ID" => $fdata['ID'],
            "title" => $fdata['title'],
            "starter" => $fdata['thread_starter']
        );
    }

    $alldata = array(
        "class" => $class,
        "siswa" => $siswa,
        "teacher" => $guru,
        "mapel" => $mapel,
        "subjetc" => $subjetc,
        "users" => $users,
        "classes" => $classes,
        "chat" => $chat,
        "materi" => $materi,
        "video" => $video,
        "exam" => $exam,
        "exas" => $exas,
        "exrc" => $exrc,
        "excc" => $exac,
        "exae" => $exae,
        "exmc" => $exmc,
        "exmp" => $exmm,
        "excp" => $excp,
        "exvl" => $exva,
        "soal" => $soal,
        "forum" => $forum
    );

    function rcheck($type, $target)
    {
        if (in_array($type, ["POST", "GET"])) {
            $result = true;
            if ($type == "GET") {
                for ($i = 0; $i <= count($target) - 1; $i++) {
                    if (!isset($_GET[$target[$i]])) {
                        $result = false;
                        break;
                    }
                }
            } else {
                for ($i = 0; $i <= count($target) - 1; $i++) {
                    if (!isset($_GET[$target[$i]])) {
                        $result = false;
                        break;
                    }
                }
            }
        } else {
            $result = false;
        }
        return $result;
    }

    function API($target, $method, $data)
    {
        global $config;
        $data = json_encode($data);
        return file_get_contents($config['base_url'] . "API/index.php?target=$target&method=$method&data=$data");
    }

    if (isset($_SESSION['auladi_user_id'])) {
        $aui = $_SESSION['auladi_user_id'];
        $gui = select("user", "ID='$aui'");
        if (mysqli_num_rows($gui) == 1) {
            $user = mysqli_fetch_array($gui);
            $unme = $user['username'];
            $user['stable'] = True;
            switch ($user['role']) {
                case "admin":
                    $user['full_name']  = "Administrator";
                    break;
                case "teacher":
                    $gstd = query("subjetc", "WHERE nip='$unme'");
                    if (mysqli_num_rows($gstd) > 0) {
                        while ($datac = mysqli_fetch_array($gstd)) {
                            $classesx[] = $datac['class'];
                        }
                    }
                    $mclasses = implode(",", $classesx ?? ["9999999998", "9999999999"]);
                    $mekskul  = implode(",", $guru[$unme]['subject'] ?? "0");
                    $user['full_name']  = $guru[$user['username']]['name'];
                    break;
                case "student":
                    $user['full_name']  = $siswa[$user['username']]['name'];
                    $myclass            = $siswa[$user['username']]['class'];
                    $myekskul           = isset($siswa[$user['username']]['ekskul']) ? implode(",", $siswa[$user['username']]['ekskul']) : 0;
                    break;
                default:
                    $user['stable'] = False;
                    break;
            }
        } else {
            session_destroy();
        }
    }

    function abtn($link, $class, $content)
    {
        return "<a href='" . $link . "' class='btn " . $class . "'>" . $content . "</a>";
    }

    $KunciKemanaSaja = "c8d9459f4";
} else {
    session_destroy();
    echo '<script>window.location.replace("./");</script>';
}
