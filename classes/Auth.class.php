<?php

    require_once 'Database.class.php';

    class Auth extends Database {

        public function authenticateLogin($email, $password) {
            $sql = "SELECT * FROM faculties WHERE (username = ? OR email = ?) AND password = ? AND deleted_at IS ?";
            $query = $this->connect()->prepare($sql);
            $query->execute([$email, $email, $password, null]);
            if ($query->rowCount() > 0) {
                return $query->fetch(); 
            } else {
                $sql2 = "SELECT * FROM students WHERE (student_number = ? OR email = ?) AND password = ? AND deleted_at IS ?";
                $query2 = $this->connect()->prepare($sql2);
                $query2->execute([$email, $email, $password, null]);
                return $query2->fetch(); 
            }
        }

    }