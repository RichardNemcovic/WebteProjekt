<?php
    require 'connector.php';

    class LoginService{
        private $conn;
        
        public function __construct()
        {
            $this->conn = (new MyDbConn())->get_connection();
        }

        public function teacher_register($ais_id, $name, $surname, $password, $email){
            $id_role = 2;
            $hashed_password = hash('sha256', $password);
            $stmt = $this->conn->prepare("INSERT IGNORE INTO users (id_role, ais_id, name, surname, email, password) values (:id_role, :ais_id, :name, :surname, :email, :password)");
            $stmt->bindParam(":id_role", $id_role, PDO::PARAM_INT);
            $stmt->bindParam(":ais_id", $ais_id);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":surname", $surname);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $hashed_password);
            $stmt->execute();

            $stmt = $this->conn->prepare("SELECT id, name, surname FROM users WHERE ais_id=:ais_id");
            $stmt->bindParam(":ais_id", $ais_id);
            $stmt->execute();
            $output = $stmt->fetch();

            if ($output) {
                $resp = ['status' => 'OK', 'id' => $output["id"], 'name' => $output["name"] ." ". $output["surname"]];
            } else {
                $resp = ['status' => 'FAIL', 'message' => 'Database error.'];
            }
            echo json_encode($resp);
            return json_encode($resp);
        }

        public function teacher_login($email, $password){
            $stmt = $this->conn->prepare("SELECT id, name, surname, password FROM users WHERE email=:email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            $output = $stmt->fetch();
            if ($output) {
                $hashed_password = hash('sha256', $password);
                if ($output['password'] == $hashed_password) {
                    $resp = ['status' => 'OK', 'id' => $output["id"], 'name' => $output["name"] ." ". $output["surname"]];
                } else {
                    $resp = ['status' => 'FAIL', 'message' => 'Wrong password.'];
                }
            } else {
                $resp = ['status' => 'FAIL', 'message' => 'Wrong email.'];
            }
            echo json_encode($resp);
            return json_encode($resp);
        }

        public function student_login($name, $surname, $ais_id, $code){
            $id_role = 1;
            $stmt = $this->conn->prepare("INSERT IGNORE INTO users (id_role, ais_id, name, surname) values (:id_role, :ais_id, :name, :surname)");
            $stmt->bindParam(":id_role", $id_role, PDO::PARAM_INT);
            $stmt->bindParam(":ais_id", $ais_id);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":surname", $surname);
            $stmt->execute();

            $stmt = $this->conn->prepare("SELECT id, name, surname FROM users WHERE ais_id=:ais_id");
            $stmt->bindParam(":ais_id", $ais_id);
            $stmt->execute();
            $output = $stmt->fetch();

            if ($output['name'] != $name || $output['surname'] != $surname) {
                $resp = ['status' => 'FAIL', 'message' => 'Name of surname not matching for given AIS ID.'];
            } else {
                $resp = ['status' => 'OK', 'id' => $output["id"], 'name' => $output["name"] ." ". $output["surname"]];
                $id_user = $output["id"];
                $stmt = $this->conn->prepare("SELECT * 
                                                    FROM exam_status
                                                    WHERE id_exam=:id_exam 
                                                      AND id_user=:id_user");
                $stmt->bindParam(":id_exam", $id_exam);
                $stmt->bindParam(":id_user", $id_user);
                $stmt->execute();
                $output = $stmt->fetch();

                if ($output) {
                    $resp = ['status' => 'FAIL', 'message' => "You've already submitted this test."];
                } else {
                    $status = 'active';
                    $stmt = $this->conn->prepare("SELECT id 
                                                        FROM exams 
                                                        WHERE code=:code 
                                                          AND status=:status");
                    $stmt->bindParam(":code", $code);
                    $stmt->bindParam(":status", $status);
                    $stmt->execute();
                    $output = $stmt->fetch();

                    if ($output) {
                        $resp['exam_id'] = $output['id'];
                    } else {
                        $resp = ['status' => 'FAIL', 'message' => 'No test matching this code.'];
                    }
                }


            }
            echo json_encode($resp);
            return json_encode($resp);
        }
    }
?>
