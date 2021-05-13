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
        $resp = ['status' => 'OK', 'message' => 'create_exam'];

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
        if(isset($data['start'])){
            if(!empty($data['start'])) {
                $start = $data['start'];
            }
        }

        if(isset($data['end'])){
            if(!empty($data['end'])) {
                $end = $data['end'];
            }
        }
        if(isset($data['description'])) {
            if (!empty($data['description'])) {
                $name = $data['description'];
            }
        }

        $stmt = $this->conn->prepare('insert into exams (id_creator, code, name, start, end) values (:id_creator, :code, :name, :start, :end)');
        $stmt->bindParam('id_creator', $id_creator);
        $stmt->bindParam('code', $code);
        $stmt->bindParam('name', $name);
        $stmt->bindParam('start', $start);
        $stmt->bindParam('end', $end);
        $stmt->execute();
        if($stmt->rowCount()){
            $id_exam = $this->conn->lastInsertId();
        }
        else{
            $resp = ['status' => 'FAIL', 'message' => 'create_exam'];
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
                            $stmt_qShort->execute();
                            if ($stmt_qShort->rowCount()) {
                                $id_question = $this->conn->lastInsertId();
                            } else {
                                $resp = ['status' => 'FAIL', 'message' => 'create_exam'];
                                break;
                            }
                            $stmt_qShort_answer = $this->conn->prepare('insert into questions_short(id_question, answer) values (:id_question, :answer)');
                            $stmt_qShort_answer->bindParam('id_question', $id_question);
                            $stmt_qShort_answer->bindParam('answer', $answer);
                            $stmt_qShort_answer->execute();
                            if ($stmt_qShort_answer->rowCount()) {
                            }
                            else{
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
                            $stmt_qSelect->execute();
                            if ($stmt_qSelect->rowCount()) {
                                $id_question = $this->conn->lastInsertId();
                            } else {
                                $resp = ['status' => 'FAIL', 'message' => 'create_exam'];
                                break;
                            }
                            foreach ($item['possibilities'] as $value) {
                                if(isset($value['answer'])){
                                    if(!empty($value['answer'])){
                                        $answer = $value['answer'];
                                        $stmt_qSelect_answer = $this->conn->prepare('insert into questions_select(id_question, answer, correct) values (:id_question, :answer, :correct)');
                                        $stmt_qSelect_answer->bindParam('id_question', $id_question);
                                        $stmt_qSelect_answer->bindParam('answer', $answer);
                                        $stmt_qSelect_answer->bindParam('correct', $correctAnswer);
                                        $stmt_qSelect_answer->execute();
                                        if ($stmt_qSelect_answer->rowCount()) {
                                        }else{
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
                    if(isset($item['description']) && isset($item['score'])){
                        if(!empty($item['description']) && !empty($item['score'])){
                            $name = $item['description'];
                            $score = $item['score'];
                            $id_type = 3;
                            $stmt_qImage = $this->conn->prepare('insert into questions  (id_exam, id_type, name, score) values (:id_exam, :id_type, :name, :score)');
                            $stmt_qImage->bindParam('id_exam', $id_exam);
                            $stmt_qImage->bindParam('id_type', $id_type);
                            $stmt_qImage->bindParam('name', $name);
                            $stmt_qImage->bindParam('score', $score);
                            $stmt_qImage->execute();
                            if ($stmt_qImage->rowCount()) {
                            }else{
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
                            $stmt_qEquation->execute();
                            if ($stmt_qEquation->rowCount()) {

                            }else{
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
                            $stmt_qPairs->execute();
                            if ($stmt_qPairs->rowCount()) {
                                $id_question = $this->conn->lastInsertId();
                            } else {
                                $resp = ['status' => 'FAIL', 'message' => 'create_exam'];
                                break;
                            }
                            if(isset($item['answers'])){
                                if(!empty($item['answers'])){
                                    foreach ($item['answers'] as $value){
                                        if(isset($value['left']) && isset($value['right'])) {
                                            if(!empty($value['left']) && !empty($value['right'])) {
                                                $answer_left = $value['left'];
                                                $answer_right = $value['right'];
                                                $stmt = $this->conn->prepare('insert into questions_pairing(id_question, answer_left, answer_right) values (:id_question, :answer_left, :answer_right)');
                                                $stmt->bindParam('id_question', $id_question);
                                                $stmt->bindParam('answer_left', $answer_left);
                                                $stmt->bindParam('answer_right', $answer_right);
                                                $stmt->execute();
                                                if($stmt->rowCount()){
                                                }else{
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

        if($resp['status'] == 'FAIL'){
            $resp = ['status' => 'FAIL', 'message' => 'create_exam'];
            echo json_encode($resp);
            return json_encode($resp);
        }else{
            echo json_encode($resp);
            return json_encode($resp);
        }
    }

    public function get_exams_students($id_exam) {
        $stmt = $this->conn->prepare("SELECT exam_status.id_user, exam_status.points, status_type.name AS status, users.name, users.surname 
                                            FROM status_type 
                                            INNER JOIN exam_status ON exam_status.id_status=status_type.id 
                                            INNER JOIN users ON exam_status.id_user=users.id 
                                            WHERE exam_status.id_exam=:id_exam");
        $stmt->bindParam(":id_exam", $id_exam);
        $stmt->execute();
        $output = $stmt->fetchAll();

        $students = [];

        if ($output) {
            foreach ($output as $index=>$out) {
                $students[$index]['id']     = $out['id_user'];
                $students[$index]['status']   = $out['status'];
                $students[$index]['score']  = $out['points'];
                $students[$index]['name']  = $out['name'];
                $students[$index]['surname']  = $out['surname'];
            }
            $resp = ['status' => 'OK', 'tests' => $students];
        } else {
            $resp = ['status' => 'FAIL', 'message' => 'No students took part in this test.'];
        }
        echo json_encode($resp);
        return json_encode($resp);
    }

    public function set_score($id_user, $id_answer, $id_question, $score) {
        $stmt = $this->conn->prepare("SELECT * FROM answers 
                                            WHERE id=:id_answer 
                                              AND id_user=:id_user 
                                              AND id_question=:id_question");
        $stmt->bindParam(":id_question", $id_question);
        $stmt->bindParam(":id_answer", $id_answer);
        $stmt->bindParam(":id_user", $id_user);
        $stmt->execute();
        $output = $stmt->rowCount();

        $stmt = $this->conn->prepare("UPDATE answers 
                                            SET score=:score 
                                            WHERE id=:id_answer 
                                              AND id_user=:id_user 
                                              AND id_question=:id_question");
        $stmt->bindParam(":id_question", $id_question);
        $stmt->bindParam(":id_answer", $id_answer);
        $stmt->bindParam(":id_user", $id_user);
        $stmt->bindParam(":score", $score);
        $stmt->execute();
        $rowCount = $stmt->rowCount();

        if ($output == 0) {
            $resp = ['status' => 'FAIL', 'message' => 'No matching rows.'];
        } else if ($rowCount != $output) {
            $resp = ['status' => 'FAIL', 'message' => 'No changes in the database.'];
        } else {
            $resp = ['status' => 'SUCCESS'];
        }

        echo json_encode($resp);
        return json_encode($resp);
    }

    // -----------------------------------------------------------------------------------------------
    //                                   Student part
    // -----------------------------------------------------------------------------------------------

    public function get_all_exams_for_creator($id_creator) {
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

    public function submit_exam() {
        $resp = ['status' => 'FAIL', 'message' => 'submit_exam'];
        return json_encode($resp);
    }

    public function get_exam_by_id($id_exam, $id_user) {
        $stmt = $this->conn->prepare("SELECT exams.name, exam_status.submit_timestamp
                                            FROM exams 
                                            INNER JOIN exam_status ON exams.id=exam_status.id_exam
                                            WHERE exams.id=:id_exam
                                              AND exam_status.id_user=:id_user");
        $stmt->bindParam(":id_exam", $id_exam);
        $stmt->bindParam(":id_user", $id_user);
        $stmt->execute();
        $output = $stmt->fetch();

        if ($output) {
            $resp = ['status' => 'OK', 'name' => $output['name'], 'submit_timestamp' => $output['submit_timestamp']];

            // Select question, id=1
            $id_type = 1;
            $stmt = $this->conn->prepare("SELECT name, score, id
                                                FROM questions 
                                                WHERE id_exam=:id_exam
                                                  AND id_type=:id_type");
            $stmt->bindParam(":id_exam", $id_exam);
            $stmt->bindParam(":id_type", $id_type);
            $stmt->execute();
            $output = $stmt->fetchAll();

            foreach ($output as $index01=>$out) {
                $resp['qSelect'][$index01]['question']['description'] = $out['name'];
                $resp['qSelect'][$index01]['question']['score'] = $out['score'];
                $stmt = $this->conn->prepare("SELECT id, answer, correct
                                                    FROM questions_select 
                                                    WHERE id_question=:id_question");
                $stmt->bindParam(":id_question", $out['id']);
                $stmt->execute();
                $res = $stmt->fetchAll();

                if ($res) {
                    foreach ($res as $index02=>$item) {
                        if ($item['correct'] == 1) {
                            $resp['qSelect'][$index01]['question']['correctAnswer'] = $item['answer'];
                        }
                        $resp['qSelect'][$index01]['question']['possibilities'][$index02]['id']     = $item['id'];
                        $resp['qSelect'][$index01]['question']['possibilities'][$index02]['answer'] = $item['answer'];
                    }
                }

                $stmt = $this->conn->prepare("SELECT answers.id, answers.score, answers_select.id_question_select
                                                    FROM answers
                                                    INNER JOIN answers_select ON answers.id=answers_select.id_answer
                                                    WHERE answers.id_question=:id_question");
                $stmt->bindParam(":id_question", $out['id']);
                $stmt->execute();
                $res = $stmt->fetch();

                if ($res) {
                    $resp['qSelect'][$index01]['answer']['id'] = $res['id'];
                    $resp['qSelect'][$index01]['answer']['answer'] = $res['id_question_select'];
                    $resp['qSelect'][$index01]['answer']['score'] = $res['score'];
                }
            }

            // Short answer question, id=2
            $id_type = 2;
            $stmt = $this->conn->prepare("SELECT questions.name, questions.score, questions_short.answer
                                                FROM questions 
                                                INNER JOIN questions_short ON questions.id=questions_short.id_question
                                                WHERE id_exam=:id_exam
                                                  AND id_type=:id_type");
            $stmt->bindParam(":id_exam", $id_exam);
            $stmt->bindParam(":id_type", $id_type);
            $stmt->execute();
            $output = $stmt->fetchAll();

            foreach ($output as $index01=>$out) {
                $resp['qShort'][$index01]['question']['description'] = $out['name'];
                $resp['qShort'][$index01]['question']['score'] = $out['score'];
                $resp['qShort'][$index01]['question']['answer'] = $out['answer'];

                $stmt = $this->conn->prepare("SELECT answers.id, answers.score, answers_short.answer
                                                    FROM answers
                                                    INNER JOIN answers_select ON answers.id=answers_short.id_answer
                                                    WHERE answers.id_question=:id_question");
                $stmt->bindParam(":id_question", $out['id']);
                $stmt->execute();
                $res = $stmt->fetch();

                if ($res) {
                    $resp['qShort'][$index01]['answer']['id'] = $res['id'];
                    $resp['qShort'][$index01]['answer']['answer'] = $res['answer'];
                    $resp['qShort'][$index01]['answer']['score'] = $res['score'];
                }
            }

        } else {
            $resp = ['status' => 'FAIL', 'message' => 'get_exam_by_id'];
        }

        echo json_encode($resp);

        return json_encode($resp);
    }

    public function get_exam_times() {
        $resp = ['status' => 'FAIL', 'message' => 'get_exam_times'];
        echo json_encode($resp);
        return json_encode($resp);
    }

    public function get_server_time() {
        $resp = ['status' => 'FAIL', 'message' => 'get_server_time'];
        echo json_encode($resp);
        return json_encode($resp);
    }
}

?>