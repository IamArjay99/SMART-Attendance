<?php

class Subject extends Database {
    public function getSubject($id) {
        $sql = "SELECT * FROM subjects WHERE id = ? AND deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$id, NULL]);
        return $query->fetch();
    }

    public function insertNewSubject($course_id, $subject_code, $subject_name) {
        $sql = "INSERT INTO subjects (course_id, code, name) VALUES (?, ?, ?)";
        $query = $this->connect()->prepare($sql);
        $query->execute([$course_id, $subject_code, $subject_name]);
        return $query;
    }

    public function getAllSubjectByCourse($course_id) {
        $sql = "SELECT * FROM subjects WHERE course_id = ? AND deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$course_id, NULL]);
        return $query->fetchAll();
    }

    public function getAllSubjectGroupByCode() {
        $sql = "SELECT * FROM subjects WHERE deleted_at IS ? GROUP BY code";
        $query = $this->connect()->prepare($sql);
        $query->execute([NULL]);
        return $query->fetchAll();
    }
}