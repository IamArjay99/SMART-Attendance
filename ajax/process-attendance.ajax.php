<?php

    include_once "../includes/all.include.php";

    // Deletling Attendance
    if (isset($_POST['queryDeleteAttendance'])) {
        $course_id = $_POST['queryDeleteAttendance']['course_id'];
        $subject_id = $_POST['queryDeleteAttendance']['subject_id'];
        $faculty_id = $_POST['queryDeleteAttendance']['faculty_id'];
        $section_id = $_POST['queryDeleteAttendance']['section_id'];
        $dates = $_POST['queryDeleteAttendance']['date'];
        $updateDeleteDateAttendance = $date->updateDeleteDateAttendance($course_id, $subject_id, $faculty_id, $section_id, $dates);
        if ($updateDeleteDateAttendance) {
            echo "success";
        } else {
            echo "failed";
        }
    }
    
    // Restoring Attendance
    if (isset($_POST['queryRestoreAttendance'])) {
        $course_id = $_POST['queryRestoreAttendance']['course_id'];
        $subject_id = $_POST['queryRestoreAttendance']['subject_id'];
        $faculty_id = $_POST['queryRestoreAttendance']['faculty_id'];
        $section_id = $_POST['queryRestoreAttendance']['section_id'];
        $dates = $_POST['queryRestoreAttendance']['date'];
        $updateRestoreAttendance = $date->updateRestoreAttendanceDate($course_id, $subject_id, $faculty_id, $section_id, $dates);
        if ($updateRestoreAttendance) {
            echo "success";
        } else {
            echo "failed";
        }
    }

    // Insert date attendance
    if (isset($_POST['queryAttendanceDate'])) {
        $course_id = $_POST['queryAttendanceDate']['course_id'];
        $subject_id = $_POST['queryAttendanceDate']['subject_id'];
        $faculty_id = $_POST['queryAttendanceDate']['faculty_id'];
        $section_id = $_POST['queryAttendanceDate']['section_id'];
        $student_id = $_POST['queryAttendanceDate']['student_id'];
        $dates = $_POST['queryAttendanceDate']['date'];
        // Insert date
        $newAttendanceDate = $date->newAttendanceDate($course_id, $subject_id, $faculty_id, $section_id, $dates);
        if ($newAttendanceDate) {
            echo 'success';
        } else {
            echo 'failed';
        }
    }

    // Insert Attendance
    if (isset($_POST['querySaveAttendance'])) {
        $course_id = $_POST['querySaveAttendance']['course_id'];
        $subject_id = $_POST['querySaveAttendance']['subject_id'];
        $faculty_id = $_POST['querySaveAttendance']['faculty_id'];
        $section_id = $_POST['querySaveAttendance']['section_id'];
        $student_id = $_POST['querySaveAttendance']['student_id'];
        $dates = $_POST['querySaveAttendance']['date'];
        $presence = $_POST['querySaveAttendance']['presence'];
        $time = $_POST['querySaveAttendance']['time'];
        $date_id = $date->getLastDateId(); 
        $takeAttendance = $attendance->takeAttendance($course_id, $subject_id, $faculty_id, $section_id, $student_id, $date_id, $presence, $time);
        if ($takeAttendance) {
            echo 'success';
        } else {
            echo 'failed';
        }
    }
    