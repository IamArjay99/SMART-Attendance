<?php

class Faculty extends Database {

    public function getFacultyInfo($id) {
        $sql = "SELECT * FROM faculties WHERE id = ? AND deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$id, NULL]);
        return $query->fetch();
    }

    public function getAllFaculties() {
        $sql = "SELECT * FROM faculties WHERE deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([NULL]);
        return $query->fetchAll();
    }

    // Add new faculty
    public function insertNewFaculty($name, $username, $email, $profession, $password) {
        $sql = "INSERT INTO faculties (role_id, profession_id, name, username, email, password) VALUES (?,?,?,?,?,?)";
        $query = $this->connect()->prepare($sql);
        $query->execute(["3", $profession, $name, $username, $email, $password]);
        return $query;
    }

    public function updateDeleteFacultyAccount($id) {
        $dateNow = date('Y-m-d H:i:s');
        $sql = "UPDATE faculties SET deleted_at = ? WHERE id = ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$dateNow, $id]);
        return $query;
    }

    public function updateFacultyAccount($faculty_id, $name, $profession, $username, $email, $role) {
        $sql = "UPDATE faculties SET name = ?, profession_id = ?, username = ?, email = ?, role_id = ? WHERE id = ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$name, $profession, $username, $email, $role, $faculty_id]);
        return $query;
    }

}