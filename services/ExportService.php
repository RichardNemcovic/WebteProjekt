<?php
    require 'connector.php';

    class ExportService
    {
        private $conn;

        public function __construct()
        {
            $this->conn = (new MyDbConn())->get_connection();
        }

        public function get_exam_csv($id_exam)
        {
        $stmt = $this->conn->prepare("SELECT users.ais_id, name, surname, points FROM users, exam_status WHERE exam_status.id_exam=:id_exam AND id_status=2 AND exam_status.id_user=users.id");
        $stmt->bindParam(":id_exam", $id_exam);
        $stmt->execute();
        $filename = 'tmp/exam' . $id_exam . '.csv';
        $fp = fopen($filename, 'w');
        
        $row =  $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            fputcsv($fp, array_keys($row));
            fputcsv($fp, $row);
            while($row =  $stmt->fetch(PDO::FETCH_ASSOC, 0)) {
                fputcsv($fp, $row);
            }
            $resp = ['status' => 'OK', 'message' => $filename];
        } else {
            $resp = ['status' => 'FAIL', 'message' => 'No tests with this id.'];
        }
        fclose($fp);
        echo json_encode($resp);
        }

        public function get_exam_pdf($id_exam)//Composer Needed https://getcomposer.org/download/ and https://mpdf.github.io/installation-setup/installation-v7-x.html
        {//and U need to change priority to current file chmod 777 PLUS u need to create tmp file and do the same chmod mate
            $filename = '../tmp/exam' . $id_exam . '.zip';
            $zip = new ZipArchive;
            if ($zip->open($filename, ZipArchive::CREATE) === TRUE){
                $stmt = $this->conn->prepare("SELECT id_user, users.name as Username, surname, exams.name as Examname, ais_id  FROM exam_status, users, exams WHERE id_status=2 AND id_exam=:id_exam AND id_user=users.id AND id_exam=exams.id AND users.id_role=1");
                $stmt->bindParam(":id_exam", $id_exam);
                $stmt->execute();
                $status = false;
                while($data = $stmt->fetch(PDO::FETCH_OBJ)){//Pre kazdeho usera
                    $status = true;
                    $mpdf = new \Mpdf\Mpdf();
                    $myData = '
                    <!DOCTYPE html>
                    <html lang="sk">
                    <head>
                        <meta charset="UTF-8">
                        <style>
                        h1 {text-align: center;}
                        h2 {text-align: center;}
                        img {
                            border: 1px solid #ddd;
                            border-radius: 4px;
                            padding: 5px;
                            width: 150px;
                        }
                        </style>
                        </head>
                        <body>
                    ';
                    $myData .= '<h1>' . $data->Examname .'</h1>';
                    $myData .= '<h2>' . $data->Username . ' '. $data->surname .'</h2>';
                    $pdfname = $data->ais_id . '_' . $id_exam. '.pdf';

                    $stmtQ = $this->conn->prepare("SELECT answers.id as AnswerID, id_type as IDtype, questions.name as QuestionsName FROM questions,answers WHERE questions.id_exam=:id_exam AND answers.id_user=:USERid AND id_question=questions.id");
                    $stmtQ->bindParam(":id_exam", $id_exam);
                    $stmtQ->bindParam(":USERid", $data->id_user);
                    $stmtQ->execute();
                    while($dataQ = $stmtQ->fetch(PDO::FETCH_OBJ)){//Pre kazdu question
                        $myData .= '<strong>Question: </strong>' . $dataQ->QuestionsName . '<br />';
                        switch ($dataQ->IDtype) {
                            case 1:
                                $stmtA = $this->conn->prepare("SELECT questions_select.answer as Answer FROM answers_select, questions_select WHERE id_answer=:IDanswer AND questions_select.id=answers_select.id_question_select");
                                $stmtA->bindParam(":IDanswer", $dataQ->AnswerID);
                                $stmtA->execute();
                                while($dataA = $stmtA->fetch(PDO::FETCH_OBJ)){
                                    $myData .= '<strong>Answer: </strong>' . $dataA->Answer . '<br />';
                                }
                                break;
                            case 2:
                                $stmtA = $this->conn->prepare("SELECT id_answer, answer FROM answers_short WHERE id_answer=:IDanswer");
                                $stmtA->bindParam(":IDanswer", $dataQ->AnswerID);
                                $stmtA->execute();
                                while($dataA = $stmtA->fetch(PDO::FETCH_OBJ)){
                                    $myData .= '<strong>Answer: </strong>' . $dataA->answer . '<br />';
                                }
                                break;
                            case 3:
                                $stmtA = $this->conn->prepare("SELECT id_answer, answer FROM answers_images WHERE id_answer=:IDanswer");
                                $stmtA->bindParam(":IDanswer", $dataQ->AnswerID);
                                $stmtA->execute();
                                while($dataA = $stmtA->fetch(PDO::FETCH_OBJ)){
                                    $myData .= '<strong>Answer: </strong><img src=../'. $dataA->answer .' alt="picture" width="350" height="200">';
                                }
                                break;
                            case 4:
                                $stmtA = $this->conn->prepare("SELECT id_answer, answer FROM answers_equations WHERE id_answer=:IDanswer");
                                $stmtA->bindParam(":IDanswer", $dataQ->AnswerID);
                                $stmtA->execute();
                                while($dataA = $stmtA->fetch(PDO::FETCH_OBJ)){//budem ocakavat img
                                    $myData .= '<strong>Answer: </strong><img src=../'. $dataA->answer .' alt="picture" width="350" height="200">';
                                }
                                break;
                            case 5:
                                $stmtA = $this->conn->prepare("SELECT id_answer, answer_left, answer_right FROM answers_pairing WHERE id_answer=:IDanswer");
                                $stmtA->bindParam(":IDanswer", $dataQ->AnswerID);
                                $stmtA->execute();
                                while($dataA = $stmtA->fetch(PDO::FETCH_OBJ)){
                                    $myData .= '<strong>Answer: </strong>'  . '<br />';
                                    $myData .= '<p>' .$dataA->answer_left. '---'. $dataA->answer_right . '</p>'  . '<br />';
                                }
                                break;
                        }//TOTO MA BYT PRE KAZDEHO USERA
                        $myData .= '<hr>';
                    }
                    $mpdf->WriteHTML($myData);
                    $mpdf->Output($pdfname, "F");

                    if (file_exists($pdfname)) {
                        $zip->addFile($pdfname);
                    } else {
                        //echo "The file does not exist";
                    }
                }
                $conn = null;
            }
            if($status){//Toto co ti poslem je, ze ten zip bude v WEBTEPROJEKT/tmp/exam.zip
                $filename = 'tmp/exam' . $id_exam . '.zip';
                $resp = ['status' => 'OK', 'path' => $filename];
            }else{
                $resp = ['status' => 'FAIL', 'message' => 'No zip was made due to non existent id_exam or no users.'];
            }
            $zip->close();
            array_map('unlink', glob("*.pdf"));
            echo json_encode($resp);
        }

        public function delete_exam_zip($filename)//Ocakavam tmp/exam1.zip
        {   
            $location = basename($filename);
            $filename = '../tmp/' . $location;
            if(file_exists($filename)){
                array_map('unlink', glob($filename));
                $resp = ['status' => 'OK', 'path' => 'Deletion completed'];
            }else{
                $resp = ['status' => 'FAIL', 'message' => 'Deletion not completed, file didnt exist in that dir.'];
            }
            echo json_encode($resp);
        }

        public function delete_exam_csv($filename)//Ocakavam tmp/exam1.csv
        {   
//            $location = basename($filename);
//            $filename = '../tmp/' . $location;
            if(file_exists($filename)){
                array_map('unlink', glob($filename));
                $resp = ['status' => 'OK', 'path' => 'Deletion completed'];
            }else{
                $resp = ['status' => 'FAIL', 'message' => 'Deletion not completed, file didnt exist in that dir.'];
            }
            echo json_encode($resp);
        }
    }
?>
