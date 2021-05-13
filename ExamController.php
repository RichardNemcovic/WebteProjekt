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
                if($data){
                    $examService->create_exam($data);
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
            case('getExamsStudents'):
                if(isset($data['id_exam'])){
                    $examService->get_exams_students($data['id_exam']);
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
            case('setScore'):
                if(isset($data['id_user']) && isset($data['id_answer']) && isset($data['id_question']) && isset($data['score'])){
                    $examService->set_score($data['id_user'], $data['id_answer'], $data['id_question'], $data['score']);
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
                if(isset($data['id_exam']) && isset($data['id_user'])){
                    $examService->get_exam_by_id($data['id_exam'], $data['id_user']);
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