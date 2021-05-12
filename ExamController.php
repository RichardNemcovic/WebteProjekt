<?php
//Errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR | E_PARSE);
error_reporting(E_ALL);

//Imports
require 'services/ExamService.php';

//Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST");
header("Access-Control-Max-Age: 3600");

//Services
$examService = new ExamService();

//Routing
if(isset($_GET['ep'])){
    $ep = $_GET['ep'];
    // read the POST body
    $data = json_decode(file_get_contents('php://input'), true);

    if($_SERVER["REQUEST_METHOD"] === 'GET'){

    }

    if($_SERVER["REQUEST_METHOD"] === 'POST'){
        switch($ep) {
            case('createExam'):
                if(isset($data['email'])){

                    $resp = array();

                    if(isset($data['description'])){
                        $resp['description']= $data['description'];
                    }

                    if(isset($data['$start'])){
                        $resp['start'] = $data['$start'];
                    }

                    if(isset($data['end'])){
                        $resp['end'] = $data['end'];
                    }

                    if(isset($data['qShort'])){
                        foreach ($data['qShort'] as $item) {
                            if(isset($item['description']) && isset($item['score']) && isset($item['answer'])){
                                $score = array(
                                    'answer' => $data['answer'],
                                    'score' => $data['score'],
                                    'description' => $data['description'],
                                );
                                array_push($resp['qShort'], $score);
                                /*$resp['description']['score']['answer'] = $data['answer'];
                                $resp['description']['score']['score'] = $data['score'];
                                $resp['description']['score']['description'] = $data['description'];*/
                            }
                        }
                    }
                    if(isset($data['qSelect'])){
                        foreach ($data['qSelect'] as $item){
                            if(isset($item['description']) && isset($item['score']) && isset($item['correctAnswer']) && isset($item['possibilities'])) {
                                $qSelect = array(
                                    'description' => $item['description'],
                                    'score' => $item['score'],
                                    'correctAnswer' => $item['correctAnswer']
                                );
                                foreach($item['possibilities'] as $value){
                                    if(isset($value['answer'])){
                                        $possibilities = array(
                                            'answer' => $value['answer'],
                                        );
                                        array_push($qSelect, $possibilities);
                                    }
                                }
                                array_push($resp['qSelect'], $qSelect);
                            }
                        }
                    }

                    if(isset($data['qImage'])){
                        foreach ($data['qImage'] as $item){
                            if(isset($item['description']) && isset($item['score'])){
                                $qImage = array(
                                  'description' =>  $item['description'],
                                    'score' =>$item['score'],
                                );
                                array_push($resp['qImage'], $qImage);
                            }
                        }
                    }

                    if(isset($data['qEquation'])){
                        foreach ($data['qEquation'] as $item){
                            if(isset($item['description']) && isset($item['score'])){
                                $qEquation = array(
                                    'description' =>  $item['description'],
                                    'score' =>$item['score'],
                                );
                                array_push($resp['qImage'], $qEquation);
                            }
                        }
                    }

                    if(isset($data['qPairs'])){
                        foreach ($data['qPairs'] as $item){
                            if(isset($item['description']) && isset($item['score']) &&isset($item['answers'])){
                                $qPairs = array(
                                  'description' =>   $item['description'],
                                    'score'=>$item['score'],
                                );
                                foreach ($item['answers'] as $value){
                                    if(isset($value['left']) && isset($value['right'])){
                                        $answers = array(
                                            'left' => $value['left'],
                                            'right' => $value['right'],
                                        );
                                        array_push($qPairs, $answers);
                                    }
                                }
                                array_push($resp['qPairs'], $qPairs);
                            }
                        }

                    }
                    $examService->create_exam(json_encode($resp));
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
            case('getExam'):
                if(isset($data['email'])){
                    $examService->get_exam();
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
            case('setScore'):
                if(isset($data['email'])){
                    $examService->set_score();
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
            case('getAllExamsForCreator'):
                if(isset($data['id_creator'])){
                    $examService->get_all_exams_for_creator($data['id_creator']);
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
            case('submitExam'):
                if(isset($data['email'])){
                    $examService->submit_exam();
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
            case('getExamById'):
                if(isset($data['email'])){
                    $examService->get_exam_by_id();
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
        }
    }
}else{
    echo json_encode(["status"=>"FAIL"]);
}
?>