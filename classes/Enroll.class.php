<?php

class Enroll extends Database {
    // Getting all enrolled subjects
    public function getAllEnrollees($course_id, $subject_id, $faculty_id, $section_id) {
        $sql = "SELECT * FROM enrolled WHERE course_id = ? AND subject_id = ? AND faculty_id = ? AND section_id = ? AND deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$course_id, $subject_id, $faculty_id, $section_id, NULL]);
        return $query->fetchAll();
    }

    // Saving enroll subjects
    public function saveStudentSubjects($course_id, $subject_id, $faculty_id, $section_id, $student_id) {
        $sql = "INSERT INTO enrolled (course_id, subject_id, faculty_id, section_id, student_id) VALUES (?,?,?,?,?)";
        $query = $this->connect()->prepare($sql);
        $query->execute([$course_id, $subject_id, $faculty_id, $section_id, $student_id]);
        return $query;
    }

    // Get enroll subject of student
    public function getStudentEnrollSubject($course_id, $subject_id, $student_id, $faculty_id = "") {
        if ($faculty_id !== "") {
            $sql = "SELECT * FROM enrolled WHERE course_id = ? AND subject_id = ? AND student_id = ? AND faculty_id = ? AND deleted_at IS ? ";
            $query = $this->connect()->prepare($sql);
            $query->execute([$course_id, $subject_id, $student_id, $faculty_id, NULL]);
            return $query->fetch();
        } else {
            $sql = "SELECT * FROM enrolled WHERE course_id = ? AND subject_id = ? AND student_id = ? AND deleted_at IS ? ";
            $query = $this->connect()->prepare($sql);
            $query->execute([$course_id, $subject_id, $student_id, NULL]);
            return $query->fetch();
        }
    }

    // Get all enroll subjects of student
    public function getStudentAllEnrollSubjects($course_id, $student_id, $faculty_id = "") {
        if ($faculty_id !== "") {
            $sql = "SELECT * FROM enrolled WHERE course_id = ? AND student_id = ? AND faculty_id = ? AND deleted_at IS ? ";
            $query = $this->connect()->prepare($sql);
            $query->execute([$course_id, $student_id, $faculty_id, NULL]);
            return $query->fetchAll();
        } else {
            $sql = "SELECT * FROM enrolled WHERE course_id = ? AND student_id = ? AND deleted_at IS ? ";
            $query = $this->connect()->prepare($sql);
            $query->execute([$course_id, $student_id, NULL]);
            return $query->fetchAll();
        }
    }
}