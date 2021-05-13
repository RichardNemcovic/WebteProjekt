<?php
    require 'connector.php';

    class ExportService
    {
        private $conn;

        public function __construct()
        {
            $this->conn = (new MyDbConn())->get_connection();
        }

        public function get_exam_CSV() {

        }

        public function get_exam_PDF() {

        }
    }
?>
