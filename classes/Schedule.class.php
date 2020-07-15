<?php

class Schedule extends Database {
    public function getAllFacultySchedules() {
        $sql = "SELECT * FROM schedules 
                WHERE deleted_at IS ?
                GROUP BY subject_id, faculty_id";
        $query = $this->connect()->prepare($sql);
        $query->execute([NULL]);
        return $query->fetchAll();
    }

    public function getFacultySchedules($faculty_id, $subject_id = "") {
        if ($subject_id !== "") {
            $sql = "SELECT  sec.name as section, day.abbr as day, sched.time_start as time_start, sched.time_end as time_end FROM schedules as sched
                INNER JOIN sections as sec
                ON sec.id = sched.section_id AND sec.deleted_at IS NULL
                INNER JOIN days as day
                ON day.id = sched.day_id AND day.deleted_at IS NULL
                WHERE 
                faculty_id = ? AND
                subject_id = ? AND
                sched.deleted_at IS ?
                ORDER BY sec.name";
            $query = $this->connect()->prepare($sql);
            $query->execute([$faculty_id, $subject_id, NULL]);
            return $query->fetchAll();
        } else {
            $sql = "SELECT sched.id as id, subj.id as subject_id, subj.code as subject_code, subj.name as subject_name, sec.id as section_id, sec.name as section, day.id as day_id, day.abbr as day, day.name as day_name, sched.time_start as time_start, sched.time_end as time_end FROM schedules as sched
                INNER JOIN sections as sec
                ON sec.id = sched.section_id AND sec.deleted_at IS NULL
                INNER JOIN days as day
                ON day.id = sched.day_id AND day.deleted_at IS NULL
                INNER JOIN subjects as subj
                ON subj.id = sched.subject_id AND subj.deleted_at IS NULL
                WHERE 
                faculty_id = ? AND
                sched.deleted_at IS ?
                ORDER BY sec.name";
            $query = $this->connect()->prepare($sql);
            $query->execute([$faculty_id, NULL]);
            return $query->fetchAll();
        }
    }

    public function getFacultyScheduleBySections($faculty_id, $subject_id, $section_id = '') {
        if ($section_id !== '') {
            $sql = "SELECT sched.course_id as course_id, sched.section_id as section_id, sec.name as section, day.abbr as day, sched.time_start as time_start, sched.time_end as time_end FROM schedules as sched
                INNER JOIN sections as sec
                ON sec.id = sched.section_id AND sec.deleted_at IS NULL
                INNER JOIN days as day
                ON day.id = sched.day_id AND day.deleted_at IS NULL
                WHERE 
                faculty_id = ? AND
                subject_id = ? AND
                section_id = ? AND
                sched.deleted_at IS ?
                GROUP BY sec.name";
            $query = $this->connect()->prepare($sql);
            $query->execute([$faculty_id, $subject_id, $section_id, NULL]);
            return $query->fetchAll();
        } else {
            $sql = "SELECT sched.course_id as course_id, sched.section_id as section_id, sec.name as section, day.abbr as day, sched.time_start as time_start, sched.time_end as time_end FROM schedules as sched
                INNER JOIN sections as sec
                ON sec.id = sched.section_id AND sec.deleted_at IS NULL
                INNER JOIN days as day
                ON day.id = sched.day_id AND day.deleted_at IS NULL
                WHERE 
                faculty_id = ? AND
                subject_id = ? AND
                sched.deleted_at IS ?
                GROUP BY sec.name";
            $query = $this->connect()->prepare($sql);
            $query->execute([$faculty_id, $subject_id, NULL]);
            return $query->fetchAll();
        }
    }

    public function getFacultyScheduleInSection($faculty_id, $subject_id, $section_id) {
        $sql = "SELECT  sched.id as id, sched.course_id as course_id, sched.subject_id as subject_id, sched.faculty_id as faculty_id, sched.section_id as section_id, sec.name as section, day.abbr as day, sched.time_start as time_start, sched.time_end as time_end FROM schedules as sched
                INNER JOIN sections as sec
                ON sec.id = sched.section_id AND sec.deleted_at IS NULL
                INNER JOIN days as day
                ON day.id = sched.day_id AND day.deleted_at IS NULL
                WHERE 
                faculty_id = ? AND
                subject_id = ? AND
                section_id = ? AND
                sched.deleted_at IS ?
                ORDER BY sched.section_id";
        $query = $this->connect()->prepare($sql);
        $query->execute([$faculty_id, $subject_id, $section_id, NULL]);
        return $query->fetchAll();
    }

    public function updateDeleteFacultySchedule($id) {
        $dateNow = date('Y-m-d H:i:s');
        $sql = "UPDATE schedules SET deleted_at = ? WHERE id = ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$dateNow, $id]);
        return $query;
    }

    public function insertNewSchedule($course_id, $subject_id, $faculty_id, $section_id, $day_id, $time_start, $time_end) {
        $sql = "INSERT INTO schedules (course_id, subject_id, faculty_id, section_id, day_id, time_start, time_end) VALUES (?,?,?,?,?,?,?)";
        $query = $this->connect()->prepare($sql);
        $query->execute([$course_id, $subject_id, $faculty_id, $section_id, $day_id, $time_start, $time_end]);
        return $query;
    }

    public function updateFacultySchedule($id, $course_id, $subject_id, $faculty_id, $section_id, $day_id, $time_start, $time_end) {
        $sql = "UPDATE schedules SET course_id = ?, subject_id = ?, faculty_id = ?, section_id = ?, day_id = ?, time_start = ?, time_end = ? WHERE id = ? AND deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$course_id, $subject_id, $faculty_id, $section_id, $day_id, $time_start, $time_end, $id, NULL]);
        return $query;
    }

    // Get schedules by course
    public function getFacultyScheduleByCourseAndSubject($course_id, $subject_id = "") {
        if ($subject_id !== "") {
            $sql = "SELECT * FROM schedules WHERE course_id = ? AND subject_id = ? AND deleted_at IS ? GROUP BY section_id";
            $query = $this->connect()->prepare($sql);
            $query->execute([$course_id, $subject_id, NULL]);
            return $query->fetchAll();
        } else {
            $sql = "SELECT * FROM schedules WHERE course_id = ? AND deleted_at IS ? GROUP BY subject_id";
            $query = $this->connect()->prepare($sql);
            $query->execute([$course_id, NULL]);
            return $query->fetchAll();
        }
    }

    // Get faculty id
    public function getScheduleBySubjectSectionCourse($course_id, $subject_id, $section_id, $faculty_id) {
        $sql = "SELECT * FROM schedules WHERE course_id = ? AND subject_id = ? AND section_id = ? AND faculty_id = ? AND deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$course_id, $subject_id, $section_id, $faculty_id, NULL]);
        return $query->fetchAll();
    }

    // Getting Faculty schedule
    public function getFacultyScheduleById($id) {
        $sql = "SELECT * FROM schedules WHERE id = ? AND deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$id, NULL]);
        return $query->fetch();
    }

    // Getting Faculty Id
    public function getFacultyId($course_id, $subject_id, $section_id) {
        $sql = "SELECT * FROM schedules WHERE course_id = ? AND subject_id = ? AND section_id = ? AND deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$course_id, $subject_id, $section_id, NULL]);
        $result = $query->fetch();
        return $result['faculty_id'];
    }

}