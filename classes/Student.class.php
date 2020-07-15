<?php

class Student extends Database {
    public function getStudent($id) {
        $sql = "SELECT * FROM students WHERE id = ? AND deleted_at IS ? ORDER BY name ASC";
        $query = $this->connect()->prepare($sql);
        $query->execute([$id, NULL]);
        return $query->fetch();
    }

    public function getLastStudentId() {
        $sql = "SELECT * FROM students WHERE deleted_at IS ? ORDER BY id DESC LIMIT 1";
        $query = $this->connect()->prepare($sql);
        $query->execute([NULL]);
        $result = $query->fetch();
        return $result['id'];
    }

    public function saveStudentBasicInformation($student_number, $password, $fullname, $email, $course, $year, $section) {
        $sql = "INSERT INTO students
                (student_number, password, name, email, role_id, course_id, year_id, section_id)
                VALUES (?,?,?,?,?,?,?,?)";
        $query = $this->connect()->prepare($sql);
        $query->execute([$student_number, $password, $fullname, $email, "4", $course, $year, $section]);
        return $query;
    }
}