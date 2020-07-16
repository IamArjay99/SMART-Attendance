<?php

    session_start();

    include_once "../includes/all.include.php";

    if (isset($_POST['query'])) {
        $email = $_POST['query']['email'];
        $password = $_POST['query']['password'];

        $login = $auth->authenticateLogin($email, $password);
        if ($login) {
            if ($login['role_id'] == "1" || $login['role_id'] == "2" || $login['role_id'] == "3") {
                $_SESSION['data'] = [
                    "id" => $login['id'],
                    "role_id" => $login['role_id'],
                    "profession_id" => $login['profession_id'],
                    "name" => $login["name"],
                    "username" => $login['username'],
                    "email" => $login['email'],
                ];    
                if ($login["role_id"] == "1" || $login["role_id"] == "2") {
                    echo "pages/dean_chairperson/dashboard_dean_chairperson.php";
                } else {
                    echo "pages/professor/dashboard_professor.php";
                }
            } else {
                $_SESSION['data'] = [
                    "id" => $login['id'],
                    "role_id" => $login['role_id'],
                    "student_number" => $login["student_number"],
                    "name" => $login['name'],
                    "email" => $login['email'],
                    "password" => $login['password'],
                    "year_id" => $login['year_id'],
                ];    
                echo "pages/student/dashboard_student.php?subject_id=all";       
            }
        } else {
            echo "false";
        }
    }