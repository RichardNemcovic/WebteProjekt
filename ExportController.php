<?php
//Errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR | E_PARSE);
error_reporting(E_ALL);

//Imports
require 'services/ExportService.php';

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
            case('exportCSV'):
                if(isset($_GET['id_test'])){
                    $exportService->get_exam_csv($_GET['id_test']);
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
            case('exportPDF'):
                if(isset($_GET['id_test'])){
                    $exportService->get_exam_PDF($_GET['id_test']);
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
            case('deleteZip'):
                if(isset($_GET['filename'])){
                    $exportService->delete_exam_zip($_GET['filename']);
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
            case('deleteCsv'):
                if(isset($_GET['filename'])){
                    $exportService->delete_exam_csv($_GET['filename']);
                } else{
                    echo json_encode(['status'=>'FAIL']);
                }
                break;
        }
    }
}