<?php

require 'connector.php';

class ExamService
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new MyDbConn())->get_connection();
    }

    // -----------------------------------------------------------------------------------------------
    //                                   Teacher part
    // -----------------------------------------------------------------------------------------------

    public function create_exam($resp)
    {
        $resp = ['status' => 'FAIL', 'message' => 'create_exam'];
        return json_encode($resp);
    }

    public function get_exam() {
        $stmt = $this->conn->prepare("SELECT id, code, name, start, end, status FROM exams WHERE id_creator=:id_creator");
        $stmt->bindParam(":id_creator", $id_creator);
        $stmt->execute();
        $output = $stmt->fetchAll();

        $tests = [];

        if ($output) {
            foreach ($output as $index=>$out) {
                $tests[$index]['id']     = $out['id'];
                $tests[$index]['code']   = $out['code'];
                $tests[$index]['name']   = $out['name'];
                $tests[$index]['start']  = $out['start'];
                $tests[$index]['end']    = $out['end'];
                $tests[$index]['status'] = $out['status'];
            }
            $resp = ['status' => 'OK', 'tests' => $tests];
        } else {
            $resp = ['status' => 'FAIL', 'message' => 'No tests for this teacher.'];
        }
        echo json_encode($resp);
        return json_encode($resp);
    }

    public function set_score() {
        $resp = ['status' => 'FAIL', 'message' => 'set_score'];
        return json_encode($resp);
    }

    // -----------------------------------------------------------------------------------------------
    //                                   Student part
    // -----------------------------------------------------------------------------------------------

    public function get_all_exams_for_creator($id_creator) {
        $stmt = $this->conn->prepare("SELECT id, code, name, start, end, status FROM exams WHERE id_creator=:id_creator");
        $stmt->bindParam(":id_creator", $id_creator);
        $stmt->execute();
        $output = $stmt->fetchAll();

        $tests = [];

        if ($output) {
            foreach ($output as $index=>$out) {
                $tests[$index]['id']     = $out['id'];
                $tests[$index]['code']   = $out['code'];
                $tests[$index]['name']   = $out['name'];
                $tests[$index]['start']  = $out['start'];
                $tests[$index]['end']    = $out['end'];
                $tests[$index]['status'] = $out['status'];
            }
            $resp = ['status' => 'OK', 'tests' => $tests];
        } else {
            $resp = ['status' => 'FAIL', 'message' => 'No tests for this teacher.'];
        }
        return json_encode($resp);
    }

    public function submit_exam() {
        $resp = ['status' => 'FAIL', 'message' => 'submit_exam'];
        return json_encode($resp);
    }

    public function get_exam_by_id() {
        $resp = ['status' => 'FAIL', 'message' => 'get_exam_by_id'];
        return json_encode($resp);
    }
}

?>