<?php

class Attendance extends Database {

    // Get all attendances
    // public function getAttendances($faculty_schedule_id, $student_id = "") {
    //     if ($student_id !== "") {
    //         if ($faculty_schedule_id == "all") {
    //             $sql = "SELECT * FROM attendances WHERE student_id = ? AND deleted_at IS ? ORDER BY faculty_schedule_id, date DESC";
    //             $query = $this->connect()->prepare($sql);
    //             $query->execute([$student_id, NULL]);
    //             return $query->fetchAll();
    //         } else {
    //             $sql = "SELECT * FROM attendances WHERE faculty_schedule_id = ? AND student_id = ? AND deleted_at IS ? ORDER BY date DESC";
    //             $query = $this->connect()->prepare($sql);
    //             $query->execute([$faculty_schedule_id, $student_id, NULL]);
    //             return $query->fetchAll();
    //         }
    //     } else {
            // $sql = "SELECT * FROM attendances WHERE faculty_schedule_id = ? AND deleted_at IS ? ORDER BY date DESC";
            // $query = $this->connect()->prepare($sql);
            // $query->execute([$faculty_schedule_id, NULL]);
            // return $query->fetchAll();
    //     }
    // }
    public function getAttendances($course_id, $subject_id, $faculty_id, $section_id, $student_id = "", $date_id = "") {
        if ($student_id !== "" && $date_id !== "") {
            $sql = "SELECT * FROM attendances WHERE course_id = ? AND subject_id = ? AND faculty_id = ? AND section_id = ? AND student_id = ? AND date_id = ? AND deleted_at IS ?";
            $query = $this->connect()->prepare($sql);
            $query->execute([$course_id, $subject_id, $faculty_id, $section_id, $student_id, $date_id, NULL]);
            return $query->fetchAll();
        } else {
            $sql = "SELECT * FROM attendances WHERE course_id = ? AND subject_id = ? AND faculty_id = ? AND section_id = ? AND deleted_at IS ? ORDER BY date DESC";
            $query = $this->connect()->prepare($sql);
            $query->execute([$course_id, $subject_id, $faculty_id, $section_id, NULL]);
            return $query->fetchAll();
        }
    }

    // Get all archived attendances
    // public function getArchiveAttendances($course_id, $subject_id, $faculty_id, $section_id, $student_id = "") {
    //     if ($student_id !== "") {
    //         $sql = "SELECT * FROM attendances WHERE course_id = ? AND subject_id = ? AND faculty_id = ? AND section_id = ? AND student_id = ? AND deleted_at IS NOT ? ORDER BY date DESC";
    //         $query = $this->connect()->prepare($sql);
    //         $query->execute([$course_id, $subject_id, $faculty_id, $section_id,  $student_id, NULL]);
    //         return $query->fetchAll();
    //     } else {
    //         $sql = "SELECT * FROM attendances WHERE course_id = ? AND subject_id = ? AND faculty_id = ? AND section_id = ? AND deleted_at IS NOT ? ORDER BY date DESC";
    //         $query = $this->connect()->prepare($sql);
    //         $query->execute([$course_id, $subject_id, $faculty_id, $section_id, NULL]);
    //         return $query->fetchAll();
    //     }
    // }

    // Delete Attendance
    // public function updateDeleteAttendance($course_id, $subject_id, $faculty_id, $section_id, $date) {
        // $dateNow = date('Y-m-d H:i:s');
        // $sql = "UPDATE attendances SET deleted_at = ? WHERE course_id = ? AND subject_id = ? AND faculty_id = ? AND section_id = ? AND date = ?";
        // $query = $this->connect()->prepare($sql);
        // $query->execute([$dateNow, $course_id, $subject_id, $faculty_id, $section_id, $date]);
        // return $query;
    // }
    
    // Restore Attendance
    // public function updateRestoreAttendance($course_id, $subject_id, $faculty_id, $section_id, $student_id, $date) {
    //     $sql = "UPDATE attendances SET deleted_at = ? WHERE course_id = ? AND subject_id = ? AND faculty_id = ? AND section_id = ? AND student_id = ? AND date = ?";
    //     $query = $this->connect()->prepare($sql);
    //     $query->execute([NULL, $course_id, $subject_id, $faculty_id, $section_id, $student_id, $date]);
    //     return $query;
    // }

    // Take Attendance
    public function takeAttendance($course_id, $subject_id, $faculty_id, $section_id, $student_id, $date_id, $presence, $time) {
        $sql = "INSERT INTO attendances (course_id, subject_id, faculty_id, section_id, student_id, date_id, presence, time) VALUES (?,?,?,?,?,?,?,?)";
        $query = $this->connect()->prepare($sql);
        $query->execute([$course_id, $subject_id, $faculty_id, $section_id, $student_id, $date_id, $presence, $time]);
        return $query;
    }

    // Get studnet attendance
    public function getStudentAttendances($course_id, $subject_id, $student_id, $faculty_id = "") {
        if ($subject_id === "all") {
            if ($faculty_id !== "") {
                $sql = "SELECT * FROM attendances
                        INNER JOIN dates ON dates.id = attendances.date_id 
                        WHERE attendances.course_id = ? AND attendances.student_id = ? AND attendances.faculty_id = ? AND attendances.deleted_at IS ? ORDER BY attendances.subject_id ASC, dates.date DESC";
                $query = $this->connect()->prepare($sql);
                $query->execute([$course_id, $student_id, $faculty_id, NULL]);
                return $query->fetchAll();
            } else {
                $sql = "SELECT * FROM attendances
                        INNER JOIN dates ON dates.id = attendances.date_id 
                        WHERE attendances.course_id = ? AND attendances.student_id = ? AND attendances.deleted_at IS ? ORDER BY attendances.subject_id ASC, dates.date DESC";
                $query = $this->connect()->prepare($sql);
                $query->execute([$course_id, $student_id, NULL]);
                return $query->fetchAll();
            }
        } else {
            if ($faculty_id !== "") {
                $sql = "SELECT * FROM attendances
                        INNER JOIN dates ON dates.id = attendances.date_id 
                        WHERE attendances.course_id = ? AND attendances.subject_id = ? AND attendances.student_id = ? AND attendances.faculty_id = ? AND attendances.deleted_at IS ? ORDER BY attendances.subject_id ASC, dates.date DESC";
                $query = $this->connect()->prepare($sql);
                $query->execute([$course_id, $subject_id, $student_id, $faculty_id, NULL]);
                return $query->fetchAll();
            } else {
                $sql = "SELECT * FROM attendances
                        INNER JOIN dates ON dates.id = attendances.date_id 
                        WHERE attendances.course_id = ? AND attendances.subject_id = ? AND attendances.student_id = ? AND attendances.deleted_at IS ? ORDER BY attendances.subject_id ASC, dates.date DESC";
                $query = $this->connect()->prepare($sql);
                $query->execute([$course_id, $subject_id,  $student_id, NULL]);
                return $query->fetchAll();
            }
        }
    }

}