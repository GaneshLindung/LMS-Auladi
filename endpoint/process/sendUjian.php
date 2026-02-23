<?Php
if(isset($_POST['answer'])) {
    $answer = $_POST['answer'];
    $examid = $_POST['examid'];
    
    if(isset($answer['pilgan'])) {
        foreach ($answer['pilgan'] as $key => $val) {
            $check = select("examrec","nis='$unme' AND exam_id='$examid' AND qtype='pilgan' AND question_id='$key'");
            if (mysqli_num_rows($check) < 1) {
                insert("examrec","nis='$unme', exam_id='$examid', qtype='pilgan', question_id='$key', answer='$val'");
            } else {
                update("examrec","answer='$val'","nis='$unme' AND exam_id='$examid' AND qtype='pilgan' AND question_id='$key'");
            }
        }
    }

    if(isset($answer['essay'])) {
        foreach ($answer['essay'] as $key => $val) {
            $check = select("examrec","nis='$unme' AND exam_id='$examid' AND qtype='essay' AND question_id='$key'");
            if (mysqli_num_rows($check) < 1) {
                insert("examrec","nis='$unme', exam_id='$examid', qtype='essay', question_id='$key', answer='$val', cstatus='0'");
            } else {
                update("examrec","answer='$val'","nis='$unme' AND exam_id='$examid' AND qtype='essay' AND question_id='$key'");
            }
        }
    }

    if(isset($answer['multiple'])) {
        foreach ($answer['multiple'] as $key => $val) {
            $val = json_encode($val);
            $check = select("examrec","nis='$unme' AND exam_id='$examid' AND qtype='multiple' AND question_id='$key'");
            if (mysqli_num_rows($check) < 1) {
                insert("examrec","nis='$unme', exam_id='$examid', qtype='multiple', question_id='$key', answer='$val', cstatus='0'");
            } else {
                update("examrec","answer='$val'","nis='$unme' AND exam_id='$examid' AND qtype='multiple' AND question_id='$key'");
            }
        }
    }

    if(isset($answer['match'])) {
        foreach ($answer['match'] as $key => $val) {
            $val = $val[0];
            $check = select("examrec","nis='$unme' AND exam_id='$examid' AND qtype='match' AND question_id='$key'");
            if (mysqli_num_rows($check) < 1) {
                insert("examrec","nis='$unme', exam_id='$examid', qtype='match', question_id='$key', answer='$val', cstatus='0'");
            } else {
                update("examrec","answer='$val'","nis='$unme' AND exam_id='$examid' AND qtype='match' AND question_id='$key'");
            }
        }
    }

    update("exam_s","stored='$timestamp'","nis='$unme' AND exam_id='$examid'");

    $r['OK'] = true;
}
?>