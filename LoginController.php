<?php
//Errors
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ERROR | E_PARSE);
    error_reporting(E_ALL);

//Imports
    require 'services/LoginService.php';

//Headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET,POST");
    header("Access-Control-Max-Age: 3600");
    
//Services
    $loginService = new LoginService();

//Routing
    if(isset($_GET['ep'])){
        $ep = $_GET['ep'];
        // read the POST body
        $data = json_decode(file_get_contents('php://input'), true);

        if($_SERVER["REQUEST_METHOD"] === 'GET'){

        }

        if($_SERVER["REQUEST_METHOD"] === 'POST'){
            switch($ep) {
                case('teacherRegistration'):
                    if(isset($data['ais_id']) && isset($data['name']) && isset($data['surname']) && isset($data['password']) && isset($data['email'])){
                        $loginService->teacher_register($data['ais_id'], $data['name'], $data['surname'], $data['password'], $data['email']);
                    } else{
                        echo json_encode(['status'=>'regFail']);
                    }
                    break;
                case('teacherLogin'):
                    if (isset($data['email']) && isset($data['password'])) {
                        $loginService->teacher_login($data['email'], $data['password']);
                    }else{
                        echo json_encode(['status'=>'FAIL']);
                    }
                    break;
                case('studentLogin'):
                    if(isset($data['name']) && isset($data['surname']) && isset($data['code']) && isset($data['ais_id'])){
                        $loginService->student_login($data['name'], $data['surname'], $data['ais_id'], $data['code']);
                    }else{
                        echo json_encode(['status'=>'FAIL']);
                    }
                    break;
            }
        }
    }else{
        echo json_encode(["status"=>"FAIL"]);
    }
?>