<?php

require 'connector.php';

class ExamService
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new MyDbConn())->get_connection();
    }

    // -----------------------------------------------------------------------------------------------
    //                                   Teacher part
    // -----------------------------------------------------------------------------------------------

    public function create_exam($data)
    {

        if(isset($data['code'])){
            if(!empty($data['code'])){
                $code= $data['code'];
            }
        }
        if(isset($data['creator'])){
            if(!empty($data['creator'])) {
                $id_creator = $data['creator'];
            }
        }
        if(isset($data['$start'])){
            if(!empty($data['start'])) {
                $start = $data['$start'];
            }
        }

        if(isset($data['end'])){
            if(!empty($data['end'])) {
                $end = $data['end'];
            }
        }
        if(isset($data['description'])) {
            if (!empty($data['name'])) {
                $name = $data['description'];
            }
        }


        $stmt = $this->conn->prepare('insert into exams (id_creator, code, name, start, end) values (:id_creator, :code, :name, :start, :end)');
        $stmt->bindParam('id_creator', $id_creator);
        $stmt->bindParam('code', $code);
        $stmt->bindParam('name', $name);
        $stmt->bindParam('start', $start);
        $stmt->bindParam('end', $end);

        if(!$stmt->execute()){
            $resp = ['status' => 'FAIL', 'message' => 'create_exam'];
        }
        else{
            $id_exam = $this->conn->lastInsertId();
        }

        if(isset($data['qShort'])){
        if (!empty($data['qShort'])) {
            foreach ($data['qShort'] as $item) {
                if(isset($item['description']) && isset($item['score']) && isset($item['answer'])) {
                    if(!empty($item['description']) && !empty($item['score']) && !empty($item['answer'])) {
                        $name = $item['description'];
                        $score = $item['score'];
                        $answer = $item['answer'];
                        $id_type = 2;
                        $stmt_qShort = $this->conn->prepare('insert into questions (id_exam, id_type, name, score) values (:id_exam, :id_type, :name, :score)');
                        $stmt_qShort->bindParam('id_exam', $id_exam);
                        $stmt_qShort->bindParam('id_type', $id_type);
                        $stmt_qShort->bindParam('name', $name);
                        $stmt_qShort->bindParam('score', $score);
                        if (!$stmt_qShort->execute()) {
                            $resp = ['status' => 'FAIL', 'message' => 'create_exam'];
                            break;
                        } else {
                            $id_question = $this->conn->lastInsertId();
                        }
                        $stmt_qShort_answer = $this->conn->prepare('insert into questions_short(id_question, answer) values (:id_question, :answer)');
                        $stmt_qShort_answer->bindParam('id_question', $id_question);
                        $stmt_qShort_answer->bindParam('answer', $answer);
                        if (!$stmt_qShort_answer->execute()) {
                            $resp = ['status' => 'FAIL', 'message' => 'create_exam'];
                            break;
                        }
                    }
                }
            }
        }
    }

        if(isset($data['qSelect'])){
            if(!empty($data['qSelect'])){
                foreach ($data['qSelect'] as $item) {
                    if(isset($item['description']) && isset($item['score']) && isset($item['correctAnswer']) && isset($item['possibilities'])) {
                        if(!empty($item['description']) && !empty($item['score']) && !empty($item['correctAnswer']) && !empty($item['possibilities'])) {
                            $name = $item['description'];
                            $score = $item['score'];
                            $correctAnswer = $item['correctAnswer'];
                            $id_type = 1;
                            $stmt_qSelect = $this->conn->prepare('insert into questions (id_exam, id_type, name, score) values (:id_exam, :id_type, :name, :score)');
                            $stmt_qSelect->bindParam('id_exam', $id_exam);
                            $stmt_qSelect->bindParam('id_type', $id_type);
                            $stmt_qSelect->bindParam('name', $name);
                            $stmt_qSelect->bindParam('score', $score);
                            if (!$stmt_qSelect->execute()) {
                                $resp = ['status' => 'FAIL', 'message' => 'create_exam'];
                                break;
                            } else {
                                $id_question = $this->conn->lastInsertId();
                            }
                            foreach ($item['possibilities'] as $value) {
                                if(isset($value['answer'])){
                                    if(!empty($value['answer'])){
                                        $answer = $value['answer'];
                                        $stmt_qSelect_answer = $this->conn->prepare('insert into questions_short(id_question, answer, correct) values (:id_question, :answer, :correct)');
                                        $stmt_qSelect_answer->bindParam('id_question', $id_question);
                                        $stmt_qSelect_answer->bindParam('answer', $answer);
                                        $stmt_qSelect_answer->bindParam('correct', $correctAnswer);
                                        if (!$stmt_qSelect_answer->execute()) {
                                            $resp = ['status' => 'FAIL', 'message' => 'create_exam'];
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if(isset($data['qImage'])){
            if(!empty($data['qImage'])){
                foreach ($data['qImage'] as $item){
                    if(isset($data['description']) && isset($data['score'])){
                        if(!empty($data['description']) && !empty($data['score'])){
                            $name = $item['description'];
                            $score = $item['score'];
                            $id_type = 3;
                            $stmt_qImage = $this->conn->prepare('insert into questions  (id_exam, id_type, name, score) values (:id_exam, :id_type, :name, :score)');
                            $stmt_qImage->bindParam('id_exam', $id_exam);
                            $stmt_qImage->bindParam('id_type', $id_type);
                            $stmt_qImage->bindParam('name', $name);
                            $stmt_qImage->bindParam('score', $score);
                            if (!$stmt_qImage->execute()) {
                                $resp = ['status' => 'FAIL', 'message' => 'create_exam'];
                                break;
                            }
                        }
                    }
                }
            }
        }

        if(isset($data['qEquation'])){
            if(!empty($data['qEquation'])){
                foreach ($data['qEquation'] as $item){
                    if(isset($item['description']) && isset($item['score'])){
                        if(!empty($item['description']) && !empty($item['score'])){
                            $name = $item['description'];
                            $score = $item['score'];
                            $id_type = 4;
                            $stmt_qEquation = $this->conn->prepare('insert into questions  (id_exam, id_type, name, score) values (:id_exam, :id_type, :name, :score)');
                            $stmt_qEquation->bindParam('id_exam', $id_exam);
                            $stmt_qEquation->bindParam('id_type', $id_type);
                            $stmt_qEquation->bindParam('name', $name);
                            $stmt_qEquation->bindParam('score', $score);
                            if (!$stmt_qEquation->execute()) {
                                $resp = ['status' => 'FAIL', 'message' => 'create_exam'];
                                break;
                            }
                        }
                    }
                }
            }
        }

        if(isset($data['qPairs'])){
            if(!empty($data['qPairs'])){
                foreach ($data['qPairs'] as $item){
                    if(isset($item['description']) && isset($item['score'])){
                        if(!empty($item['description']) && !empty($item['score'])){
                            $name = $item['description'];
                            $score = $item['score'];
                            $id_type = 5;
                            $stmt_qPairs = $this->conn->prepare('insert into questions (id_exam, id_type, name, score) values (:id_exam, :id_type, :name, :score)');
                            $stmt_qPairs->bindParam('id_exam', $id_exam);
                            $stmt_qPairs->bindParam('id_type', $id_type);
                            $stmt_qPairs->bindParam('name', $name);
                            $stmt_qPairs->bindParam('score', $score);
                            if (!$stmt_qPairs->execute()) {
                                $resp = ['status' => 'FAIL', 'message' => 'create_exam'];
                                break;
                            } else {
                                $id_question = $this->conn->lastInsertId();
                            }
                            if(isset($item['answers'])){
                                if(!empty($item['answers'])){
                                    foreach ($item['answers'] as $value){
                                        if(isset($value['left']) && isset($value['right'])) {
                                            if(!empty($value['left']) && !empty($value['right'])) {
                                                $answer_left = $value['left'];
                                                $answer_right = $value['right'];
                                                $stmt = $this->conn->prepare('insert into questions_pairing(id_question, answer_left, answer_right) values (:id_question, :answer_left, :answer_right)');
                                                $stmt->bindParam('"id_question', $id_question);
                                                $stmt->bindParam("answer_left", $answer_left);
                                                $stmt->bindParam('answer_right', $answer_right);
                                                if(!$stmt->execute()){
                                                    $resp = ['status' => 'FAIL', 'message' => 'create_exam'];
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if(isset($resp['status'])){
            if($resp['status'] == 'FAIL'){
                $resp = ['status' => 'FAIL', 'message' => 'create_exam'];
                return json_encode($resp);
            }
        }
        else{
            $resp = ['status' => 'OK', 'message' => 'create_exam'];
            return json_encode($resp);
        }
    }

    public function get_exam()
    {
        $resp = ['status' => 'FAIL', 'message' => 'get_exam'];
        return json_encode($resp);
    }

    public function set_score()
    {
        $resp = ['status' => 'FAIL', 'message' => 'set_score'];
        return json_encode($resp);
    }

    // -----------------------------------------------------------------------------------------------
    //                                   Student part
    // -----------------------------------------------------------------------------------------------

    public function get_all_exams_for_creator($id_creator)
    {
        $stmt = $this->conn->prepare("SELECT id, code, name, start, end, status FROM exams WHERE id_creator=:id_creator");
        $stmt->bindParam(":id_creator", $id_creator);
        $stmt->execute();
        $output = $stmt->fetchAll();

        $tests = [];

        if ($output) {
            foreach ($output as $index=>$out) {
                $tests[$index]['id']     = $out['id'];
                $tests[$index]['code']   = $out['code'];
                $tests[$index]['name']   = $out['name'];
                $tests[$index]['start']  = $out['start'];
                $tests[$index]['end']    = $out['end'];
                $tests[$index]['status'] = $out['status'];
            }
            $resp = ['status' => 'OK', 'tests' => $tests];
        } else {
            $resp = ['status' => 'FAIL', 'message' => 'No tests for this teacher.'];
        }
        return json_encode($resp);
    }

<<<<<<< Updated upstream
    public function submit_exam()
    {
        $resp = ['status' => 'FAIL', 'message' => 'submit_exam'];
        return json_encode($resp);
=======
    public function submit_exam($data)
    {
        function stripAccents($str)
        {
            return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
        }

        if (isset($data['id_user'])) {
            if (!empty($data['id_user'])) {
                $id_user = $data['id_user'];


            }
        }
        if (isset($data['exam'])) {

            if (!empty($data['exam'])) {

                foreach ($data['exam'] as $item) {
                    if (isset($item['id'])) {

                        if (!empty($item['id'])) {
                            $id_exam = $item['id'];

                        }
                    }
                    if (isset($item['qShort'])) {
                        if (!empty($item['qShort'])) {

                            foreach ($item['qShort'] as $value) {

                                if (isset($value['id'])) {
                                    if (!empty($value['id'])) {
                                        $id_question = $value['id'];

                                    }
                                }
                                if (isset($value['answer'])) {
                                    if (!empty($value['answer'])) {
                                        $answer = $value['answer'];
                                        $answer = stripAccents($answer);
                                        $answer = strtolower($answer);

                                    }
                                }

                                $id_type = "2";

                                $stmt = $this->conn->prepare('Select * from questions where id_exam=:id_exam and id_type=:id_type and id=:id_question');
                                $stmt->bindParam('id_exam', $id_exam);
                                $stmt->bindParam('id_question', $id_question);
                                $stmt->bindParam('id_type', $id_type);
                                $stmt->execute();

                                $question = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if ($question) {

                                    $stmt = $this->conn->prepare('Select * from questions_short where id_question=:id_question');
                                    $stmt->bindParam('id_question', $id_question);
                                    $stmt->execute();
                                    $question_short = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    if ($question_short) {
                                        $answer_right = $question_short[0]['answer'];
                                        $answer_right = stripAccents($answer_right);
                                        $answer_right = strtolower($answer_right);
                                        if ($answer_right == $answer) {
                                            $correct = 1;
                                            $score = $question[0]['score'];
                                            $stmt = $this->conn->prepare('insert into answers (id_user, id_question, correct, score) values (:id_user, :id_question, :correct, :score)');
                                            $stmt->bindParam('id_user', $id_user);
                                            $stmt->bindParam('id_question', $id_question);
                                            $stmt->bindParam('correct', $correct);
                                            $stmt->bindParam('score', $score);
                                            $stmt->execute();
                                            if ($stmt->rowCount()) {
                                                $id_answers = $this->conn->lastInsertId();
                                                $answer = $value['answer'];
                                                $stmt = $this->conn->prepare('insert into answers_short (id_answer, answer) values (:id_answer, :answer)');
                                                $stmt->bindParam('id_answer', $id_answers);
                                                $stmt->bindParam('answer', $answer);
                                                $stmt->execute();
                                                if ($stmt->rowCount()) {
                                                } else {
                                                    $resp = ['status' => 'FAIL', 'message' => 'submit_exam'];
                                                    echo json_encode($resp);
                                                    return json_encode($resp);
                                                }
                                            }
                                        } else {
                                            $correct = 0;
                                            $score = 0;
                                            $stmt = $this->conn->prepare('insert into answers (id_user, id_question, correct, score) values (:id_user, :id_question, :correct, :score)');
                                            $stmt->bindParam('id_user', $id_user);
                                            $stmt->bindParam('id_question', $id_question);
                                            $stmt->bindParam('correct', $correct);
                                            $stmt->bindParam('score', $score);
                                            $stmt->execute();
                                            if ($stmt->rowCount()) {
                                                $id_answers = $this->conn->lastInsertId();
                                                $answer = $value['answer'];
                                                $stmt = $this->conn->prepare('insert into answers_short (id_answer, answer) values (:id_answer, :answer)');
                                                $stmt->bindParam('id_answer', $id_answers);
                                                $stmt->bindParam('answer', $answer);
                                                $stmt->execute();
                                                if ($stmt->rowCount()) {
                                                } else {
                                                    $resp = ['status' => 'FAIL', 'message' => 'submit_exam'];
                                                    echo json_encode($resp);
                                                    return json_encode($resp);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (isset($item['qSelect'])) {
                        if (!empty($item['qSelect'])) {
                            foreach ($item['qSelect'] as $value) {
                                if (isset($value['id']) && isset($value['id_answer'])) {
                                    if (!empty($value['id']) && !empty($value['id_answer'])) {
                                        $id_question = $value['id'];
                                        $id_answer = $value['id_answer'];
                                    } else {
                                        $resp = ['status' => 'FAIL', 'message' => 'submit_exam'];
                                        echo json_encode($resp);
                                        return json_encode($resp);
                                    }
                                }
                                $id_type = 1;
                                $stmt = $this->conn->prepare('Select * from questions where id_exam=:id_exam and id_type=:id_type and id=:id_question');
                                $stmt->bindParam('id_exam', $id_exam);
                                $stmt->bindParam('id_question', $id_question);
                                $stmt->bindParam('id_type', $id_type);
                                $stmt->execute();
                                $question = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                if ($question) {
                                    $stmt = $this->conn->prepare('Select * from questions_select where id_question=:id_question');
                                    $stmt->bindParam('id_question', $id_question);
                                    $stmt->execute();
                                    $question_select = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    if ($question_select) {
                                        $id_question_select = $question_select[0]['id'];
                                        if ($question_select[0]['correct'] == $id_answer) {
                                            $correct = 1;
                                            $score = $question[0]['score'];
                                            $stmt = $this->conn->prepare('insert into answers (id_user, id_question, correct, score) values (:id_user, :id_question, :correct, :score)');
                                            $stmt->bindParam('id_user', $id_user);
                                            $stmt->bindParam('id_question', $id_question);
                                            $stmt->bindParam('correct', $correct);
                                            $stmt->bindParam('score', $score);
                                            $stmt->execute();
                                            if ($stmt->rowCount()) {
                                                $id_answers = $this->conn->lastInsertId();
                                                $answer = $value['id_answer'];
                                                $stmt = $this->conn->prepare('insert into answers_select (id_answer, id_question_select) values (:id_answer, :id_question_select)');
                                                $stmt->bindParam('id_answer', $id_answers);
                                                $stmt->bindParam('id_question_select', $id_question_select);
                                                $stmt->execute();
                                                if ($stmt->rowCount()) {
                                                } else {
                                                    $resp = ['status' => 'FAIL', 'message' => 'submit_exam'];
                                                    echo json_encode($resp);
                                                    return json_encode($resp);
                                                }
                                            }
                                        } else {
                                            $correct = 0;
                                            $score = 0;
                                            $stmt = $this->conn->prepare('insert into answers (id_user, id_question, correct, score) values (:id_user, :id_question, :correct, :score)');
                                            $stmt->bindParam('id_user', $id_user);
                                            $stmt->bindParam('id_question', $id_question);
                                            $stmt->bindParam('correct', $correct);
                                            $stmt->bindParam('score', $score);
                                            $stmt->execute();
                                            if ($stmt->rowCount()) {
                                                $id_answers = $this->conn->lastInsertId();
                                                $answer = $value['id_answer'];
                                                $stmt = $this->conn->prepare('insert into answers_select (id_answer, id_question_select) values (:id_answer, :id_question_select)');
                                                $stmt->bindParam('id_answer', $id_answers);
                                                $stmt->bindParam('id_question_select', $id_question_select);
                                                $stmt->execute();
                                            } else {
                                                $resp = ['status' => 'FAIL', 'message' => 'submit_exam'];
                                                echo json_encode($resp);
                                                return json_encode($resp);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (isset($item['qImage'])) {
                        if (!empty($item['qImage'])) {
                            foreach ($item['qImage'] as $value) {
                                if (isset($value['id']) && isset($value['image_data'])) {
                                    if (!empty($value['id']) && !empty($value['image_data'])) {
                                        $id_question = $value['id'];
                                        $img_data = $value['image_data'];
                                    }
                                } else {
                                    $resp = ['status' => 'FAIL', 'message' => 'submit_exam'];
                                    echo json_encode($resp);
                                    return json_encode($resp);
                                }
                                $stmt = $this->conn->prepare("select ais_id from users where id=:user_id");
                                $stmt->bindParam('user_id', $id_user);
                                $stmt->execute();
                                $ais_id = $stmt->fetchColumn();;
                                if ($ais_id) {
                                    $t = time();
                                    $path = "uploads/" . $ais_id . $t . ".png";
                                    $status = file_put_contents($path, base64_decode($img_data));

                                    $correct = 0;
                                    $score = 0;
                                    $stmt = $this->conn->prepare('insert into answers (id_user, id_question, correct, score) values (:id_user, :id_question, :correct, :score)');
                                    $stmt->bindParam('id_user', $id_user);
                                    $stmt->bindParam('id_question', $id_question);
                                    $stmt->bindParam('correct', $correct);
                                    $stmt->bindParam('score', $score);
                                    $stmt->execute();
                                    if ($stmt->rowCount()) {
                                        $id_answers = $this->conn->lastInsertId();
                                        $stmt = $this->conn->prepare('insert into answers_images (id_answer, answer) values (:id_answer, :path)');
                                        $stmt->bindParam('id_answer', $id_answers);
                                        $stmt->bindParam('path', $path);
                                        $stmt->execute();
                                        if ($stmt->rowCount()) {
                                        } else {
                                            $resp = ['status' => 'FAIL', 'message' => 'submit_exam'];
                                            echo json_encode($resp);
                                            return json_encode($resp);
                                        }
                                    }

                                } else {
                                    $resp = ['status' => 'FAIL', 'message' => 'submit_exam'];
                                    echo json_encode($resp);
                                    return json_encode($resp);
                                }
                            }
                        }
                    }
                    if (isset($item['qEquation'])) {
                        if (!empty($item['qEquation'])) {
                            foreach ($item['qEquation'] as $value) {
                                if (isset($value['id']) && isset($value['answer'])) {
                                    if (!empty($value['id']) && !empty($value['answer'])) {
                                        $id_question = $value['id'];
                                        $answer = $value['answer'];
                                    } else {
                                        $resp = ['status' => 'FAIL', 'message' => 'submit_exam'];
                                        echo json_encode($resp);
                                        return json_encode($resp);
                                    }
                                }


                            }
                        }
                    }
                }

                $resp = ['status' => 'OK', 'message' => 'submit_exam'];
                echo json_encode($resp);
                return json_encode($resp);
            }
        }
>>>>>>> Stashed changes
    }

    public function get_exam_by_id()
    {
        $resp = ['status' => 'FAIL', 'message' => 'get_exam_by_id'];
        return json_encode($resp);
    }
}

?>