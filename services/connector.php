<?php 
    class MyDbConn{
        private $conn;

        public function get_connection(){

            $server = "147.175.98.107:3306";
            $username = "root";
            $password = "8x898989";
            $db = "zaver";

            $this->conn = null;
            try{
                $this->conn = new PDO("mysql:host=" . $server . ";dbname=" . $db, $username, $password);
                $this->conn->exec("set names utf8");
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $exception){
                echo "Database could not be connected: " . $exception->getMessage();
            }
            return $this->conn;
        }
    }
?>