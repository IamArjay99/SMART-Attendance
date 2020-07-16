<?php

class Date extends Database {
    // Get schedule dates
    public function getDates($course_id, $subject_id, $faculty_id, $section_id) {
        $sql = "SELECT * FROM dates WHERE course_id = ? AND subject_id = ? AND faculty_id = ? AND section_id = ? AND deleted_at IS ? ORDER BY date DESC";
        $query = $this->connect()->prepare($sql);
        $query->execute([$course_id, $subject_id, $faculty_id, $section_id, NULL]);
        return $query->fetchAll();
    }

    // Get archive dates
    public function getArchiveDates($course_id, $subject_id, $faculty_id, $section_id) {
        $sql = "SELECT * FROM dates WHERE course_id = ? AND subject_id = ? AND faculty_id = ? AND section_id = ? AND deleted_at IS NOT ? ORDER BY date DESC";
        $query = $this->connect()->prepare($sql);
        $query->execute([$course_id, $subject_id, $faculty_id, $section_id, NULL]);
        return $query->fetchAll();
    }

    // Insert new attendance
    public function newAttendanceDate($course_id, $subject_id, $faculty_id, $section_id, $date) {
        $sql = "INSERT INTO dates (course_id, subject_id, faculty_id, section_id, date) VALUES (?,?,?,?,?)";
        $query = $this->connect()->prepare($sql);
        $query->execute([$course_id, $subject_id, $faculty_id, $section_id, $date]);
        return $query;
    }

    // Get last id
    public function getLastDateId() {
        $sql = "SELECT * FROM dates WHERE deleted_at IS ? ORDER BY id DESC LIMIT 1";
        $query = $this->connect()->prepare($sql);
        $query->execute([NULL]);
        $result = $query->fetch();
        return $result['id'];
    }

    // Delete attendance date
    public function updateDeleteDateAttendance($course_id, $subject_id, $faculty_id, $section_id, $dates) {
        $dateNow = date('Y-m-d H:i:s');
        $sql = "UPDATE dates SET deleted_at = ? WHERE course_id = ? AND subject_id = ? AND faculty_id = ? AND section_id = ? AND date = ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$dateNow, $course_id, $subject_id, $faculty_id, $section_id, $dates]);
        return $query;
    }
    
    // Restore attendance date
    public function updateRestoreAttendanceDate($course_id, $subject_id, $faculty_id, $section_id, $dates) {
        $sql = "UPDATE dates SET deleted_at = ? WHERE course_id = ? AND subject_id = ? AND faculty_id = ? AND section_id = ? AND date = ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([NULL, $course_id, $subject_id, $faculty_id, $section_id, $dates]);
        return $query;
    }

    // Get date
    public function getDate($date_id) {
        $sql = "SELECT * FROM dates WHERE id = ? AND deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$date_id, NULL]);
        return $query->fetch();
    }

    // check date if on archive
    public function checkDate($date_id) {
        $sql = "SELECT * FROM dates WHERE id = ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$date_id]);
        return $query->fetch();
    }
}