<?php
    require 'connector.php';

    class LoginService{
        private $dbConnector;
        
        public function __construct()
        {
            $this->dbConnector = new MyDbConn();
        }

        public function teacher_login($email, $password){
            $conn = $this->dbConnector->get_connection();
            
            $resp = [
                'status' => 'FAIL'
            ];

            $resp['status'] ='OK';
        }   
    }
?>
