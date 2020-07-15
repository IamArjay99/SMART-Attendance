<?php

class Course extends Database {
    public function getCourse($id) {
        $sql = "SELECT * FROM courses WHERE id = ? AND deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$id, NULL]);
        return $query->fetch();
    }

    public function getAllCourses() {
        $sql = "SELECT * FROM courses WHERE deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([NULL]);
        return $query->fetchAll();
    }

    // Inserting new course
    public function insertNewCourse($course_name, $course_abbr)  {
        $sql = "INSERT INTO courses (name, abbr) VALUES (?, ?)";
        $query = $this->connect()->prepare($sql);
        $query->execute([$course_name, $course_abbr]);
        return $query;
    }
}