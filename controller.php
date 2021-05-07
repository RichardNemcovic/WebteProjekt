<?php
//Errors
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ERROR | E_PARSE);
    error_reporting(E_ALL);

//Imports
    require 'php/LoginService.php';

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

        if($_SERVER["REQUEST_METHOD"] === 'GET'){

        }

        if($_SERVER["REQUEST_METHOD"] === 'POST'){
            switch($ep){
                case('teacherLogin'):
                    if(isset($_POST['email']) && isset($_POST['password'])){
                        echo json_encode($loginService->teacher_login($_POST['email'], $_POST['password']));
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