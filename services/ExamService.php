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

    public function get_exam()
    {
        $resp = ['status' => 'FAIL', 'message' => 'get_exam'];
        return json_encode($resp);
    }

    public function set_score()
    {
        $resp = ['status' => 'FAIL', 'message' => 'set_score'];
        return json_encode($resp);
    }

    // -----------------------------------------------------------------------------------------------
    //                                   Student part
    // -----------------------------------------------------------------------------------------------

    public function get_all_my_exams()
    {
        $resp = ['status' => 'FAIL', 'message' => 'get_all_my_exams'];
        return json_encode($resp);
    }

    public function submit_exam()
    {
        $resp = ['status' => 'FAIL', 'message' => 'submit_exam'];
        return json_encode($resp);
    }

    public function get_exam_by_id()
    {
        $resp = ['status' => 'FAIL', 'message' => 'get_exam_by_id'];
        return json_encode($resp);
    }
}

?>