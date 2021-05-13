<?php
    require 'connector.php';

    class ExportService
    {
        private $conn;

        public function __construct()
        {
            $this->conn = (new MyDbConn())->get_connection();
        }

        public function get_exam_csv($id_exam)
        {
        //vyexportovať výsledky vo formáte csv, kde v prvom stĺpci bude ID študenta, v druhom a treťom meno a priezvisko
        // a v poslednom stĺpci sumárne bodové hodnotenie za celý test. ID-Meno-Preizvisko-Sum(body za test) vsetko zo studenta za dany test
        $stmt = $this->conn->prepare("SELECT users.id, name, surname, points FROM users, exam_status WHERE exam_status.id_exam=:id_exam AND exam_status.id_user=users.id");
        $stmt->bindParam(":id_exam", $id_exam);
        $stmt->execute();

        header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: attachment;filename=exam.csv');
        $fp = fopen('php://output', 'w');
        
        $row =  $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            fputcsv($fp, array_keys($row));
            fputcsv($fp, $row);
            while($row =  $stmt->fetch(PDO::FETCH_ASSOC, 0)) {
                fputcsv($fp, $row);
            }
            $resp = ['status' => 'OK', 'path' => 'exam.csv'];
        } else {
            $resp = ['status' => 'FAIL', 'message' => 'No tests with this id.'];
        }
        fclose($fp);
        return json_encode($resp);
        }

        public function get_exam_PDF() {

        }
    }
?>
