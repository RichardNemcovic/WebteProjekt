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
        $exists = false;

        while(!$exists){
            $code = rand(100000,999999);
            $stmt = $this->conn->prepare('select * from exams where code=:code');
            $stmt->bindParam('code', $code);
            $stmt->execute();
            if($stmt->rowCount()){
                $exists = false;
            }else{
                $exists = true;
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
                            foreach ($item['possibilities'] as $key=>$value) {
                                if(isset($value['answer'])){
                                    if(!empty($value['answer'])){
                                        $answer = $value['answer'];
                                        $stmt_qSelect_answer = $this->conn->prepare('insert into questions_select(id_question, answer, correct) values (:id_question, :answer, :correct)');
                                        $stmt_qSelect_answer->bindParam('id_question', $id_question);
                                        $stmt_qSelect_answer->bindParam('answer', $answer);
                                        if ($key == $correctAnswer-1){
                                            $isCorrect = 1;
                                            $stmt_qSelect_answer->bindParam('correct', $isCorrect);
                                        } else {
                                            $isCorrect = 0;
                                            $stmt_qSelect_answer->bindParam('correct', $isCorrect);
                                        }

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

    public function set_answers_score($id_answer, $score) {
        $stmt = $this->conn->prepare("SELECT * FROM answers 
                                            WHERE id=:id_answer");
        $stmt->bindParam(":id_answer", $id_answer);
        $stmt->execute();
        $output = $stmt->rowCount();

        $stmt = $this->conn->prepare("UPDATE answers 
                                            SET score=:score 
                                            WHERE id=:id_answer ");
        $stmt->bindParam(":id_answer", $id_answer);
        $stmt->bindParam(":score", $score);
        $stmt->execute();
        $rowCount = $stmt->rowCount();

        if ($output == 0) {
            $resp = ['status' => 'FAIL', 'message' => 'No matching rows.'];
        } else if ($rowCount != $output) {
            $resp = ['status' => 'FAIL', 'message' => 'No changes in the database.'];
        } else {
            $resp = ['status' => 'OK'];
        }

        echo json_encode($resp);
        return json_encode($resp);
    }

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
        echo json_encode($resp);
    }

    public function get_exam_by_id($id_exam, $id_user) {
        $stmt = $this->conn->prepare("SELECT users.name, users.surname
                                                FROM exam_status 
                                                INNER JOIN users ON exam_status.id_user=users.id
                                                WHERE exam_status.id_exam=:id_exam
                                                  AND exam_status.id_user=:id_user");
        $stmt->bindParam(":id_exam", $id_exam);
        $stmt->bindParam(":id_user", $id_user);
        $stmt->execute();
        $output = $stmt->fetch();
        $studentName = $output['name']." ".$output['surname'];

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
            $resp = ['status' => 'OK', 'name' => $output['name'], 'submit_timestamp' => $output['submit_timestamp'], 'studentName' => $studentName];

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
                            $resp['qSelect'][$index01]['question']['correctAnswer'] = $item['id'];
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
            $stmt = $this->conn->prepare("SELECT questions.id, questions.name, questions.score, questions_short.answer
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
                                                    INNER JOIN answers_short ON answers.id=answers_short.id_answer
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

            // Image question, id=3
            $id_type = 3;
            $stmt = $this->conn->prepare("SELECT id, name, score
                                                FROM questions 
                                                WHERE id_exam=:id_exam
                                                  AND id_type=:id_type");
            $stmt->bindParam(":id_exam", $id_exam);
            $stmt->bindParam(":id_type", $id_type);
            $stmt->execute();
            $output = $stmt->fetchAll();

            foreach ($output as $index01=>$out) {
                $resp['qImage'][$index01]['question']['description'] = $out['name'];
                $resp['qImage'][$index01]['question']['score'] = $out['score'];

                $stmt = $this->conn->prepare("SELECT answers.id, answers_images.answer
                                                    FROM answers
                                                    INNER JOIN answers_images ON answers.id=answers_images.id_answer
                                                    WHERE answers.id_question=:id_question");
                $stmt->bindParam(":id_question", $out['id']);
                $stmt->execute();
                $res = $stmt->fetch();

                if ($res) {
                    $resp['qImage'][$index01]['answer']['id'] = $res['id'];
                    $resp['qImage'][$index01]['answer']['answer'] = $res['answer'];
                }
            }

            // Math question, id=4
            $id_type = 4;
            $stmt = $this->conn->prepare("SELECT id, name, score
                                                FROM questions 
                                                WHERE id_exam=:id_exam
                                                  AND id_type=:id_type");
            $stmt->bindParam(":id_exam", $id_exam);
            $stmt->bindParam(":id_type", $id_type);
            $stmt->execute();
            $output = $stmt->fetchAll();

            foreach ($output as $index01=>$out) {
                $resp['qEquation'][$index01]['question']['description'] = $out['name'];
                $resp['qEquation'][$index01]['question']['score'] = $out['score'];

                $stmt = $this->conn->prepare("SELECT answers.id, answers_equations.answer
                                                    FROM answers
                                                    INNER JOIN answers_equations ON answers.id=answers_equations.id_answer
                                                    WHERE answers.id_question=:id_question");
                $stmt->bindParam(":id_question", $out['id']);
                $stmt->execute();
                $res = $stmt->fetch();

                if ($res) {
                    $resp['qEquation'][$index01]['answer']['id'] = $res['id'];
                    $resp['qEquation'][$index01]['answer']['answer'] = $res['answer'];
                }
            }

            // Pairings question, id=5
            $id_type = 5;
            $stmt = $this->conn->prepare("SELECT id, name, score
                                                FROM questions 
                                                WHERE id_exam=:id_exam
                                                  AND id_type=:id_type");
            $stmt->bindParam(":id_exam", $id_exam);
            $stmt->bindParam(":id_type", $id_type);
            $stmt->execute();
            $output = $stmt->fetchAll();

            foreach ($output as $index01=>$out) {
                $resp['qPairs'][$index01]['question']['description'] = $out['name'];
                $resp['qPairs'][$index01]['question']['score'] = $out['score'];

                $stmt = $this->conn->prepare("SELECT answer_left, answer_right
                                                FROM questions_pairing
                                                WHERE id_question=:id_question");
                $stmt->bindParam(":id_question", $out['id']);
                $stmt->execute();
                $res = $stmt->fetchAll();

                foreach ($res as $index02=>$val) {
                    $resp['qPairs'][$index01]['question']['answers'][$index02]['left'] = $val['answer_left'];
                    $resp['qPairs'][$index01]['question']['answers'][$index02]['right'] = $val['answer_right'];
                }

                $stmt = $this->conn->prepare("SELECT answers.id, answers_pairing.answer_left, answers_pairing.answer_right
                                                    FROM answers
                                                    INNER JOIN answers_pairing ON answers.id=answers_pairing.id_answer
                                                    WHERE answers.id_question=:id_question");
                $stmt->bindParam(":id_question", $out['id']);
                $stmt->execute();
                $res = $stmt->fetchAll();

                foreach ($res as $index02=>$val) {
                    $resp['qPairs'][$index01]['answer']['answers'][$index02]['left'] = $val['answer_left'];
                    $resp['qPairs'][$index01]['answer']['answers'][$index02]['right'] = $val['answer_right'];
                }
            }
        } else {
            $resp = ['status' => 'FAIL', 'message' => 'get_exam_by_id'];
        }

        echo json_encode($resp);

        return json_encode($resp);
    }

    public function change_exams_status($id_exam) {
        $stmt = $this->conn->prepare("SELECT status
                                            FROM exams
                                            WHERE id=:id_exam");
        $stmt->bindParam(":id_exam", $id_exam);
        $stmt->execute();
        $status = $stmt->fetchColumn();

        if ($status) {
            if ($status == "active") {
                $status = "inactive";
            } else {
                $status = "active";
            }

            $stmt = $this->conn->prepare("UPDATE exams SET status=:status WHERE exams.id=:id_exam ");
            $stmt->bindParam(":id_exam", $id_exam);
            $stmt->bindParam(":status", $status);
            $stmt->execute();

            $resp = ['status' => 'OK', 'newStatus' => $status];
        } else {
            $resp = ['status' => 'FAIL', 'message' => 'get_exam_by_id'];
        }

        echo json_encode($resp);
        return json_encode($resp);
    }

    // -----------------------------------------------------------------------------------------------
    //                                   Student part
    // -----------------------------------------------------------------------------------------------

    public function open_exam($id_exam, $id_user) {
        $status = 'active';
        $stmt = $this->conn->prepare("SELECT name, start, end
                                            FROM exams
                                            WHERE id=:id_exam
                                              AND status=:status");
        $stmt->bindParam(":id_exam", $id_exam);
        $stmt->bindParam(":status", $status);
        $stmt->execute();
        $output = $stmt->fetch();

        if ($output) {
            $id_status = 1;
            $stmt = $this->conn->prepare('INSERT INTO exam_status (id_exam, id_user, id_status) values (:id_exam, :id_user, :id_status)');
            $stmt->bindParam(":id_exam", $id_exam);
            $stmt->bindParam(":id_user", $id_user);
            $stmt->bindParam(":id_status", $id_status);
            $stmt->execute();

            $resp = ['status' => 'OK', 'name' => $output['name'], 'start' => $output['start'], 'end' => $output['end']];

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
                $resp['qSelect'][$index01]['description'] = $out['name'];
                $resp['qSelect'][$index01]['score'] = $out['score'];
                $stmt = $this->conn->prepare("SELECT id, answer, correct
                                                    FROM questions_select 
                                                    WHERE id_question=:id_question");
                $stmt->bindParam(":id_question", $out['id']);
                $stmt->execute();
                $res = $stmt->fetchAll();

                if ($res) {
                    foreach ($res as $index02=>$item) {
                        $resp['qSelect'][$index01]['possibilities'][$index02]['id']     = $item['id'];
                        $resp['qSelect'][$index01]['possibilities'][$index02]['answer'] = $item['answer'];
                    }
                }
            }

            // Short answer question, id=2
            $id_type = 2;
            $stmt = $this->conn->prepare("SELECT id, name, score
                                                FROM questions 
                                                WHERE id_exam=:id_exam
                                                  AND id_type=:id_type");
            $stmt->bindParam(":id_exam", $id_exam);
            $stmt->bindParam(":id_type", $id_type);
            $stmt->execute();
            $output = $stmt->fetchAll();

            foreach ($output as $index01=>$out) {
                $resp['qShort'][$index01]['id'] = $out['id'];
                $resp['qShort'][$index01]['description'] = $out['name'];
                $resp['qShort'][$index01]['score'] = $out['score'];
            }

            // Image question, id=3
            $id_type = 3;
            $stmt = $this->conn->prepare("SELECT id, name, score
                                                FROM questions 
                                                WHERE id_exam=:id_exam
                                                  AND id_type=:id_type");
            $stmt->bindParam(":id_exam", $id_exam);
            $stmt->bindParam(":id_type", $id_type);
            $stmt->execute();
            $output = $stmt->fetchAll();

            foreach ($output as $index01=>$out) {
                $resp['qImage'][$index01]['id'] = $out['id'];
                $resp['qImage'][$index01]['description'] = $out['name'];
                $resp['qImage'][$index01]['score'] = $out['score'];
            }

            // Math question, id=4
            $id_type = 4;
            $stmt = $this->conn->prepare("SELECT id, name, score
                                                FROM questions 
                                                WHERE id_exam=:id_exam
                                                  AND id_type=:id_type");
            $stmt->bindParam(":id_exam", $id_exam);
            $stmt->bindParam(":id_type", $id_type);
            $stmt->execute();
            $output = $stmt->fetchAll();

            foreach ($output as $index01=>$out) {
                $resp['qEquation'][$index01]['id'] = $out['id'];
                $resp['qEquation'][$index01]['description'] = $out['name'];
                $resp['qEquation'][$index01]['score'] = $out['score'];
            }

            // Pairing question, id=5
            $id_type = 5;
            $stmt = $this->conn->prepare("SELECT id, name, score
                                                FROM questions 
                                                WHERE id_exam=:id_exam
                                                  AND id_type=:id_type");
            $stmt->bindParam(":id_exam", $id_exam);
            $stmt->bindParam(":id_type", $id_type);
            $stmt->execute();
            $output = $stmt->fetchAll();

            foreach ($output as $index01=>$out) {
                $resp['qPairs'][$index01]['id'] = $out['id'];
                $resp['qPairs'][$index01]['description'] = $out['name'];
                $resp['qPairs'][$index01]['score'] = $out['score'];

                $stmt = $this->conn->prepare("SELECT answer_left, answer_right
                                                FROM questions_pairing
                                                WHERE id_question=:id_question");
                $stmt->bindParam(":id_question", $out['id']);
                $stmt->execute();
                $res = $stmt->fetchAll();

                $inputs = [];

                foreach ($res as $index02=>$val) {
                    $resp['qPairs'][$index01]['pairs'][$index02]['left'] = $val['answer_left'];
                    $inputs[$index02] = $val['answer_right'];
                }

                shuffle($inputs);

                foreach ($res as $index02=>$val) {
                    $resp['qPairs'][$index01]['pairs'][$index02]['right'] = $inputs[$index02];
                }
            }

        } else {
            $resp = ['status' => 'FAIL', 'message' => 'get_exam_by_id'];
        }

        echo json_encode($resp);

        return json_encode($resp);
    }

    public function submit_exam($data) {
        $final_score=0;
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
                            $stmt = $this->conn->prepare('select * from exams where id=:id and status="active"');
                            $stmt->bindParam('id', $id_exam);
                            $stmt->execute();
                            if($stmt->rowCount()){
                            }else{
                                $resp = ['status' => 'FAIL', 'message' => 'submit_exam exam doesnt exists'];
                                echo json_encode($resp);
                                return json_encode($resp);
                            }

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
                                            $final_score += $score;
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
                                        foreach ($question_select as $select) {
                                            if($select['id'] == $id_answer) {
                                                if ($select['correct'] == 1) {
                                                    $correct = 1;
                                                    $score = $question[0]['score'];
                                                    $stmt = $this->conn->prepare('insert into answers (id_user, id_question, correct, score) values (:id_user, :id_question, :correct, :score)');
                                                    $stmt->bindParam('id_user', $id_user);
                                                    $stmt->bindParam('id_question', $id_question);
                                                    $stmt->bindParam('correct', $correct);
                                                    $stmt->bindParam('score', $score);
                                                    $stmt->execute();
                                                    $final_score += $score;
                                                    if ($stmt->rowCount()) {
                                                        $id_question_select = $id_answer;
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
                                                        $id_question_select = $id_answer;
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
                                    $img_data = substr($img_data, 22);
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
                                $correct = 0;
                                $score = 0;
                                $stmt = $this->conn->prepare('insert into answers (id_user, id_question, correct, score) values (:id_user, :id_question, :correct, :score)');
                                $stmt->bindParam('id_user', $id_user);
                                $stmt->bindParam('id_question', $id_question);
                                $stmt->bindParam('correct', $correct);
                                $stmt->bindParam('score', $score);
                                $stmt->execute();
                                if($stmt->rowCount()){
                                    $id_answers = $this->conn->lastInsertId();
                                    $stmt = $this->conn->prepare('insert into answers_equations (id_answer, answer) values (:id_answer, :answer)');
                                    $stmt->bindParam('id_answer', $id_answers);
                                    $stmt->bindParam('answer', $answer);
                                    $stmt->execute();
                                    if($stmt->rowCount()){}else {
                                        $resp = ['status' => 'FAIL', 'message' => 'submit_exam'];
                                        echo json_encode($resp);
                                        return json_encode($resp);
                                    }
                                }else {
                                    $resp = ['status' => 'FAIL', 'message' => 'submit_exam'];
                                    echo json_encode($resp);
                                    return json_encode($resp);
                                }
                            }
                        }
                    }
                    if(isset($item['qPairs'])){
                        if(!empty($item['qPairs'])){
                            foreach ($item['qPairs'] as $value){
                                if(isset($value['id']) && isset($value['pairs'])){
                                    if(!empty($value['id']) && !empty($value['pairs'])){
                                        $id_question = $value['id'];
                                    }else{
                                        $resp = ['status' => 'FAIL', 'message' => 'submit_exam'];
                                        echo json_encode($resp);
                                        return json_encode($resp);
                                    }
                                    $stmt = $this->conn->prepare('select score from questions where id=:id_question');
                                    $stmt->bindParam('id_question', $id_question);
                                    $stmt->execute();
                                    $score = $stmt->fetchColumn();
                                    $correct = 1;
                                    if($score){}else{
                                        $resp = ['status' => 'FAIL', 'message' => 'submit_exam'];
                                        echo json_encode($resp);
                                        return json_encode($resp);
                                    }

                                    foreach ($value['pairs'] as $pairs){
                                        if(isset($pairs['left']) && isset($pairs['right'])){
                                            if(!empty($pairs['left'] && !empty($pairs['right']))){
                                                $left = $pairs['left'];
                                                $right = $pairs['right'];

                                                $stmt = $this->conn->prepare('Select * from questions_pairing where id_question = :id_question && answer_left=:answer_left');
                                                $stmt->bindParam('id_question', $id_question);
                                                $stmt->bindParam('answer_left', $left);
                                                $stmt->execute();
                                                $question_pairing = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                if($question_pairing){
                                                    if($right == null){
                                                        $score = 0;
                                                        $correct = 0;
                                                    }
                                                    if($question_pairing[0]['answer_right'] == $right){
                                                    }else{
                                                        $score = 0;
                                                        $correct = 0;
                                                    }
                                                }else{
                                                    $resp = ['status' => 'FAIL', 'message' => 'submit_exam'];
                                                    echo json_encode($resp);
                                                    return json_encode($resp);
                                                }
                                            }
                                        }
                                    }
                                    $stmt = $this->conn->prepare('insert into answers (id_user, id_question, correct, score) values (:id_user, :id_question, :correct, :score)');
                                    $stmt->bindParam('id_user', $id_user);
                                    $stmt->bindParam('id_question', $id_question);
                                    $stmt->bindParam('correct', $correct);
                                    $stmt->bindParam('score', $score);
                                    $stmt->execute();
                                    $final_score += $score;
                                    if($stmt->rowCount()){
                                        $id_answers = $this->conn->lastInsertId();
                                    }else{
                                        $resp = ['status' => 'insert into answers fail', 'message' => 'submit_exam'];
                                        echo json_encode($resp);
                                        return json_encode($resp);
                                    }
                                    foreach ($value['pairs'] as $pairs){
                                        $left = $pairs['left'];
                                        $right = $pairs['right'];
                                        $stmt = $this->conn->prepare('insert into answers_pairing (id_answer, answer_left, answer_right) values (:id_answer, :answer_left, :answer_right)');
                                        $stmt->bindParam('id_answer', $id_answers);
                                        $stmt->bindParam('answer_left', $left);
                                        $stmt->bindParam('answer_right', $right);
                                        $stmt->execute();
                                        if($stmt->rowCount()){}else{
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
                $stmt = $this->conn->prepare('update exam_status set id_status=2, submit_timestamp=NOW(), points=:score where id_exam=:id_exam and id_user=:id_user');
                $stmt->bindParam('score', $final_score);
                $stmt->bindParam('id_exam', $id_exam);
                $stmt->bindParam('id_user', $id_user);
                $stmt->execute();
                $resp = ['status' => 'OK', 'message' => 'submit_exam'];
                echo json_encode($resp);
                return json_encode($resp);
            }
        }
    }

    public function get_exam_times($id) {
        $stmt = $this->conn->prepare('select * from exams where id=:id');
        $stmt->bindParam('id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($result){
            $start = $result[0]['start'];
            $end = $result[0]['end'];
            $resp = array(
                "status"=> "OK",
                "start"=>$start,
                "end"=>$end
            );
            echo json_encode($resp);
            return json_encode($resp);
        }
        $resp = ['status' => 'FAIL', 'message' => 'get_exam_times'];
        echo json_encode($resp);
        return json_encode($resp);
    }

    public function get_server_time() {
        date_default_timezone_set('Europe/Bratislava');
        $time = date("Y-m-d H:i:s");

        if($time){
            $resp = array(
                "status"=>"OK",
                "time"=>$time
            );
            echo json_encode($resp);
            return json_encode($resp);
        }
        $resp = ['status' => 'FAIL', 'message' => 'get_server_time'];
        echo json_encode($resp);
        return json_encode($resp);
    }
}

?>