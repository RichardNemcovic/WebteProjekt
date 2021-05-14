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
        switch($ep) {
            case('getAllExamsForCreator'):
                if(isset($_GET['id_creator'])){
                    $examService->get_all_exams_for_creator($_GET['id_creator']);
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
            case('getExamsStudents'):
                if(isset($_GET['id_exam'])){
                    $examService->get_exams_students($_GET['id_exam']);
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
            case('getExamById'):
                if(isset($_GET['id_exam']) && isset($_GET['id_user'])){
                    $examService->get_exam_by_id($_GET['id_exam'], $_GET['id_user']);
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
            case('getExamTimes'):
                if(isset($_GET['id_exam'])){
                    $examService->get_exam_times($_GET['id_exam']);
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
            case('getServerTime'):
                $examService->get_server_time();
                break;
            case('openExam'):
                if(isset($_GET['id_exam']) && isset($_GET['id_user'])){
                    $examService->open_exam($_GET['id_exam'], $_GET['id_user']);
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
        }
    }

    if($_SERVER["REQUEST_METHOD"] === 'POST'){
        switch($ep) {
            case('createExam'):
                if($data){
                    $examService->create_exam($data);
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
            case('changeExamsStatus'):
                if($data['id_exam']){
                    $examService->change_exams_status($data['id_exam']);
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
            case('setAnswersScore'):
                if(isset($data['id_user']) && isset($data['id_answer']) && isset($data['id_question']) && isset($data['score'])){
                    $examService->set_answers_score($data['id_user'], $data['id_answer'], $data['id_question'], $data['score']);
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
            case('submitExam'):
                if($data){
                    $examService->submit_exam($data);
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