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
                    $examService->create_exam();
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
            case('getAllMyExams'):
                if(isset($data['email'])){
                    $examService->get_all_my_exams();
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