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
$exportService = new ExportService();

//Routing
if(isset($_GET['ep'])) {
    $ep = $_GET['ep'];
    // read the POST body
    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER["REQUEST_METHOD"] === 'GET') {
        switch($ep) {
            case('getExamCSV'):
                if(isset($data['id_test'])){
                    $exportService->get_exam_CSV($data['id_test']);
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
            case('getExamPDF'):
                if(isset($data['id_test'])){
                    $exportService->get_exam_PDF($data['id_test']);
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
        }
    }
}