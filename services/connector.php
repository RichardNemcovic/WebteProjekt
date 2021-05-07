<?php 
    class MyDbConn{
        private $connection = null;

        public function __construct()
        {
            $server = "127.0.0.1:3306";
            $username = "xmikusa1";
            $password = "8x898989";
            $db = "zaver";

            $this->connection = new mysqli($server, $username, $password, $db);

            if($this->connection->connect_error){
                die("Connection failed.".$this->connection->connect_error);
            }
        }

        public function get_connection(){
            return $this->connection;
        }
    }
?>