<?php

    include_once "../includes/all.include.php";

    if (isset($_POST['queryRegisterSection'])) {
        $course_id = $_POST['queryRegisterSection'];
        $getAllSectionByCourse = $section->getAllSectionByCourse($course_id);
        $output = "";
        if ($getAllSectionByCourse) {
            $output .= '<option value="" default selected disabled>Select Section</option>';
            foreach($getAllSectionByCourse as $getAllSection) {
                $output .= "<option value='".$getAllSection['id']."'>".$getAllSection['name']."</option>";
            }
        } else {
            $output .= '<option value="" default selected disabled>Select Section</option>';
        }
        echo $output;
    }

    if (isset($_POST['queryRegisterSubject'])) {
        $course_id = $_POST['queryRegisterSubject'];
        $getFacultyScheduleByCourseAndSubject = $schedule->getFacultyScheduleByCourseAndSubject($course_id);
        $output = '';
        if ($getFacultyScheduleByCourseAndSubject) {
            $count = 0;
            foreach($getFacultyScheduleByCourseAndSubject as $getScheduleByCourseAndSubject) {
                $subject_id = $getScheduleByCourseAndSubject['subject_id'];
                $getSubject = $subject->getSubject($subject_id);
                $subject_name = $getSubject['name'];
                $getScheduleByCourseAndSubject = $schedule->getFacultyScheduleByCourseAndSubject($course_id, $subject_id);
                $output .= '<tr>';
                $output .= '<td>';
                $output .= '<input type="checkbox" name="subject_checkbox" id="subject_checkbox'.$count.'" class="form-control" value="'.$count.'">';
                $output .= '</td>';
                $output .= '<td>';
                $output .= '<input type="text" disabled value="'.$subject_name.'" class="form-control">';
                $output .= '<input type="hidden" id="subject-id'.$count.'" value="'.$subject_id.'" class="form-control">';
                $output .= '</td>';
                $output .= '<td>';
                $output .= '<select class="form-control" id="subject-section'.$count.'">';
                $output .= '<option value="" default selected disabled>Select Section</option>';
                foreach($getScheduleByCourseAndSubject as $getScheduleBySubjectAndCourse) {
                    $section_id = $getScheduleBySubjectAndCourse['section_id'];
                    $getSection = $section->getSection($section_id);
                    $output .= '<option value="'.$getSection['id'].'">'.$getSection['name'].'</option>';
                }
                $output .= '</select>';
                $output .= '</td>';
                $output .= '</tr>';
                $count++;
            }
        } else {
            $output .= '<tr><td colspan="3" class="text-center">No subjects available</td></tr>';
        }
        echo $output;
    }

    // Save Basic Information
    if (isset($_POST['querySaveBasicInformation'])) {
        $student_number = $_POST['querySaveBasicInformation']['student_number'];
        $fullname = $_POST['querySaveBasicInformation']['fullname'];
        $password = $_POST['querySaveBasicInformation']['password'];
        $email = $_POST['querySaveBasicInformation']['email']."@my.jru.edu";
        $course = $_POST['querySaveBasicInformation']['course'];
        $year = $_POST['querySaveBasicInformation']['year'];
        $section = $_POST['querySaveBasicInformation']['section'];
        $saveStudentBasicInformation = $student->saveStudentBasicInformation($student_number, $password, $fullname, $email, $course, $year, $section);
        if ($saveStudentBasicInformation) {
            echo "success";
        } else {
            echo "failed";
        }
    }

    // Save Guardian Information
    if (isset($_POST['querySaveGuardianInformation'])) {
        $guardian_name = $_POST['querySaveGuardianInformation']['guardian_name'];
        $guardian_number = $_POST['querySaveGuardianInformation']['guardian_number'];
        $guardian_relationship = $_POST['querySaveGuardianInformation']['guardian_relationship'];
        $guardian_name2 = $_POST['querySaveGuardianInformation']['guardian_name2'];
        $guardian_number2 = $_POST['querySaveGuardianInformation']['guardian_number2'];
        $guardian_relationship2 = $_POST['querySaveGuardianInformation']['guardian_relationship2'];
        $student_id = $student->getLastStudentId();
        $saveStudentGuardianInformation = $guardian->saveStudentGuardianInformation($student_id, $guardian_name, $guardian_number, $guardian_relationship, $guardian_name2, $guardian_number2, $guardian_relationship2);
        if ($saveStudentGuardianInformation) {
            echo "success";
        } else {
            echo "failed";
        }
    }

    // Save Subjects
    if (isset($_POST['querySaveSubject'])) {
        $course_id = $_POST['querySaveSubject']['course_id'];
        $subject_id = $_POST['querySaveSubject']['subject_id'];
        $section_id = $_POST['querySaveSubject']['section_id'];
        $faculty_id = $schedule->getFacultyId($course_id, $subject_id, $section_id);
        $student_id = $student->getLastStudentId();
        // $getScheduleBySubjectSectionCourse = $schedule->getScheduleBySubjectSectionCourse($course_id, $subject_id, $section_id, $faculty_id);
        // if (count($getScheduleBySubjectSectionCourse) > 0) {
        //     foreach($getScheduleBySubjectSectionCourse as $getScheduleBy) {
                // $saveStudentSubjects = $enroll->saveStudentSubjects($course_id, $subject_id, $faculty_id, $section_id, $student_id);
        //     }
        $saveStudentSubjects = $enroll->saveStudentSubjects($course_id, $subject_id, $faculty_id, $section_id, $student_id);
        if ($saveStudentSubjects) {
            echo "success";
        } else {
            echo "failed";
        }
    }