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

    public function get_exams_students($id_exam) {
        $stmt = $this->conn->prepare("SELECT exam_status.id_user, exam_status.points, status_type.name AS status, users.name, users.surname 
                                            FROM status_type 
                                            INNER JOIN exam_status ON exam_status.id_status=status_type.id 
                                            INNER JOIN users ON exam_status.id_user=users.id 
                                            WHERE exam_status.id_exam=:id_exam");
        $stmt->bindParam(":id_exam", $id_exam);
        $stmt->execute();
        $output = $stmt->fetchAll();

        $students = [];

        if ($output) {
            foreach ($output as $index=>$out) {
                $students[$index]['id']     = $out['id_user'];
                $students[$index]['status']   = $out['status'];
                $students[$index]['score']  = $out['points'];
                $students[$index]['name']  = $out['name'];
                $students[$index]['surname']  = $out['surname'];
            }
            $resp = ['status' => 'OK', 'tests' => $students];
        } else {
            $resp = ['status' => 'FAIL', 'message' => 'No students took part in this test.'];
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