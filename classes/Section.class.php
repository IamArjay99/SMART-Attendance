<?php

class Section extends Database {
    public function getAllSectionByCourse($course_id) {
        $sql = "SELECT * FROM sections WHERE course_id = ? AND deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$course_id, NULL]);
        return $query->fetchAll();
    }

    public function getSection($id) {
        $sql = "SELECT * FROM sections WHERE id = ? AND deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$id, NULL]);
        return $query->fetch();
    }

    // Insert new Section
    public function insertNewSection($course_id, $section_name) {
        $sql = "INSERT INTO sections (course_id, name) VALUES (?, ?)";
        $query = $this->connect()->prepare($sql);
        $query->execute([$course_id, $section_name]);
        return $query;
    }
}   