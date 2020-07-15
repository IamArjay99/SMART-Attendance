<?php

    include_once "../includes/all.include.php";

    // Adding new faculty accoumt
    if (isset($_POST['queryAddFaculty'])) {
        $name = $_POST['queryAddFaculty']['name'];
        $username = $_POST['queryAddFaculty']['username'];
        $email = $_POST['queryAddFaculty']['email']."@jru.edu";
        $profession = $_POST['queryAddFaculty']['profession'];
        $password = $_POST['queryAddFaculty']['password'];
        $insertNewFaculty = $faculty->insertNewFaculty($name, $username, $email, $profession, $password);
        if ($insertNewFaculty) {
            echo "success";
        } else {
            echo "failed";
        }
    }

    // Adding new subject
    if (isset($_POST['queryAddSubject'])) {
        $subject_code = $_POST['queryAddSubject']['subject_code'];
        $subject_name = $_POST['queryAddSubject']['subject_name'];
        $subject_course = $_POST['queryAddSubject']['subject_course'];
        $insertNewSubject = $subject->insertNewSubject($subject_course, $subject_code, $subject_name);
        if ($insertNewSubject) {
            echo "success";
        } else {
            echo "failed";
        }
    }

    // Display this when adding new schedule sort by course
    if (isset($_POST['queryAddScheduleByCourse'])) {
        $course_id = $_POST['queryAddScheduleByCourse']['course_id'];
        $output = "";
        // Subject
        $getAllSubjectByCourse = $subject->getAllSubjectByCourse($course_id);
        $output .= "<div class='form-group'>";
        $output .= '<label for="#add-subject">Subject</label>';
        $output .= '<select name="add-subject" id="add-subject" class="form-control" required>';
        $output .= '<option default selected disabled>Select Subject</option>';
        foreach ($getAllSubjectByCourse as $getAllSubject) {
            $output .= '<option value="'.$getAllSubject['id'].'">'.$getAllSubject['name'].'</option>';
        }
        $output .= '</select>';
        $output .= "</div>";
        // Section
        $getAllSectionByCourse = $section->getAllSectionByCourse($course_id);
        $output .= "<div class='form-group'>";
        $output .= '<label for="#add-section">Section</label>';
        $output .= '<select name="add-section" id="add-section" class="form-control" required>';
        $output .= '<option default selected disabled>Select Section</option>';
        foreach ($getAllSectionByCourse as $getAllSection) {
            $output .= '<option value="'.$getAllSection['id'].'">'.$getAllSection['name'].'</option>';
        }
        $output .= '</select>';
        $output .= "</div>";
        // Days
        $getAllDays = $day->getAllDays();
        $output .= "<div class='form-group'>";
        $output .= '<label for="#add-day">Day</label>';
        $output .= '<select name="add-day" id="add-day" class="form-control" required>';
        $output .= '<option default selected disabled>Select Day</option>';
        foreach ($getAllDays as $getAllDay) {
            $output .= '<option value="'.$getAllDay['id'].'">'.$getAllDay['name'].'</option>';
        }
        $output .= '</select>';
        $output .= "</div>";
        // Time
        $output .= "<div class='form-group'>";
        $output .= "<div class='row'>";
        $output .= "<div class='col-6'>";
        $output .= "<div class='form-group'>";
        $output .= '<label for="#add-time_start">Time Start</label>';
        $output .= '<input type="time" class="form-control" id="add-time_start" name="add-time_start" required>';
        $output .= "</div>";
        $output .= "</div>";
        $output .= "<div class='col-6'>";
        $output .= "<div class='form-group'>";
        $output .= '<label for="#add-time_end">Time End</label>';
        $output .= '<input type="time" class="form-control" id="add-time_end" name="add-time_end" required>';
        $output .= "</div>";
        $output .= "</div>";
        $output .= "</div>";
        $output .= "</div>";

        echo $output;
    }

    // Display this when editing schedule
    if (isset($_POST['queryEditSchedule'])) {
        $faculty_schedule_id = $_POST['queryEditSchedule'];

        $getStudentSchedule = $schedule->getFacultyScheduleById($faculty_schedule_id);
        $course_id = $getStudentSchedule['course_id'];
        $getCourse = $course->getCourse($course_id);
        $course_name = $getCourse['name'];
        $subject_id = $getStudentSchedule['subject_id'];
        $getSubject = $subject->getSubject($subject_id);
        $subject_name = $getSubject['name'];
        $section_id = $getStudentSchedule['section_id'];
        $getSection = $section->getSection($section_id);
        $section_name = $getSection['name'];
        $day_id = $getStudentSchedule['day_id'];
        $getDay = $day->getDay($day_id);
        $day_name = $getDay['name'];
        $time_start = $getStudentSchedule['time_start'];
        $time_end = $getStudentSchedule['time_end'];

        $output = "";
        $output .= "<input type='hidden' name='edit-faculty_schedule_id' id='edit-faculty_schedule_id' value='".$faculty_schedule_id."'>";
        // Course
        $output .= '<div class="form-group">';
        $output .= '<label for="#edit-course">Course</label>';
        $output .= '<select name="edit-course" id="edit-course" class="form-control" required readonly>';
        $output .= '<option value="'.$course_id.'" default selected>'.$course_name.'</option>';
        // $getAllCourses = $course->getAllCourses();
        // foreach($getAllCourses as $getAllCourse){
        //     if ($getAllCourse['id'] != $course_id) {
        //         $output .= "<option value='".$getAllCourse['id']."'>".$getAllCourse['name']."</option>";
        //     }
        // }
        $output .= '</select>';
        $output .= '</div>';
        // Subject
        $getAllSubjectByCourse = $subject->getAllSubjectByCourse($course_id);
        $output .= "<div class='form-group'>";
        $output .= '<label for="#edit-subject">Subject</label>';
        $output .= '<select name="edit-subject" id="edit-subject" class="form-control" required>';
        $output .= '<option value="'.$subject_id.'" default selected>'.$subject_name.'</option>';
        foreach ($getAllSubjectByCourse as $getAllSubject) {
            if ($getAllSubject['id'] != $subject_id) {
                $output .= '<option value="'.$getAllSubject['id'].'">'.$getAllSubject['name'].'</option>';
            }
        }
        $output .= '</select>';
        $output .= "</div>";
        // Section
        $getAllSectionByCourse = $section->getAllSectionByCourse($course_id);
        $output .= "<div class='form-group'>";
        $output .= '<label for="#edit-section">Section</label>';
        $output .= '<select name="edit-section" id="edit-section" class="form-control" required>';
        $output .= '<option value="'.$section_id.'" default selected>'.$section_name.'</option>';
        foreach ($getAllSectionByCourse as $getAllSection) {
            if ($getAllSection['id'] != $section_id) {
                $output .= '<option value="'.$getAllSection['id'].'">'.$getAllSection['name'].'</option>';
            }
        }
        $output .= '</select>';
        $output .= "</div>";
        // Days
        $getAllDays = $day->getAllDays();
        $output .= "<div class='form-group'>";
        $output .= '<label for="#edit-day">Day</label>';
        $output .= '<select name="edit-day" id="edit-day" class="form-control" required>';
        $output .= '<option value="'.$day_id.'" default selected>'.$day_name.'</option>';
        foreach ($getAllDays as $getAllDay) {
            if ($getAllDay['id'] != $day_id) {
                $output .= '<option value="'.$getAllDay['id'].'">'.$getAllDay['name'].'</option>';
            }
        }
        $output .= '</select>';
        $output .= "</div>";
        // Time
        $output .= "<div class='form-group'>";
        $output .= "<div class='row'>";
        $output .= "<div class='col-6'>";
        $output .= "<div class='form-group'>";
        $output .= '<label for="#edit-time_start">Time Start</label>';
        $output .= '<input type="time" class="form-control" id="edit-time_start" name="edit-time_start" value="'.$time_start.'" required>';
        $output .= "</div>";
        $output .= "</div>";
        $output .= "<div class='col-6'>";
        $output .= "<div class='form-group'>";
        $output .= '<label for="#edit-time_end">Time End</label>';
        $output .= '<input type="time" class="form-control" id="edit-time_end" name="edit-time_end" value="'.$time_end.'" required>';
        $output .= "</div>";
        $output .= "</div>";
        $output .= "</div>";
        $output .= "</div>";

        echo $output;
    }

    // Deleting Schedule
    if (isset($_POST['queryDeleteSchedule'])) {
        $faculty_schedule_id = $_POST['queryDeleteSchedule'];
        $updateDeleteFacultySchedule = $schedule->updateDeleteFacultySchedule($faculty_schedule_id);
        if ($updateDeleteFacultySchedule) {
            echo "success";
        } else {
            echo "failed";
        }
    }

    // Deleting faculty Account
    if (isset($_POST['queryDeleteFaculty'])) {
        $faculty_id = $_POST['queryDeleteFaculty'];
        $updateDeleteFacultyAccount = $faculty->updateDeleteFacultyAccount($faculty_id);
        if ($updateDeleteFacultyAccount) {
            echo "success";
        } else {
            echo "failed";
        }
    }

    // Updating faculty account
    if (isset($_POST['queryUpdateFaculty'])) {
        $faculty_id = $_POST['queryUpdateFaculty']['faculty_id'];
        $name = $_POST['queryUpdateFaculty']['name'];
        $profession = $_POST['queryUpdateFaculty']['profession'];
        $username = $_POST['queryUpdateFaculty']['username'];
        $email = $_POST['queryUpdateFaculty']['email'];
        $role = $_POST['queryUpdateFaculty']['role'];

        $updateFacultyAccount = $faculty->updateFacultyAccount($faculty_id, $name, $profession, $username, $email, $role);
        if ($updateFacultyAccount) {
            echo "success";
        } else {
            echo "failed";
        }
    }

    // Adding schedule
    if (isset($_POST['queryAddSchedule'])) {
        $faculty_id = $_POST['queryAddSchedule']['faculty_id'];
        $course_id = $_POST['queryAddSchedule']['course_id'];
        $subject_id = $_POST['queryAddSchedule']['subject_id'];
        $section_id = $_POST['queryAddSchedule']['section_id'];
        $day_id = $_POST['queryAddSchedule']['day_id'];
        $time_start = $_POST['queryAddSchedule']['time_start'];
        $time_end = $_POST['queryAddSchedule']['time_end'];

        $insertNewSchedule = $schedule->insertNewSchedule($course_id, $subject_id, $faculty_id, $section_id, $day_id, $time_start, $time_end);
        if ($insertNewSchedule) {
            echo "success";
        } else {
            echo 'failed';
        }
    }

    // Updating schedule
    if (isset($_POST['querySaveEditSchedule'])) {
        $faculty_schedule_id = $_POST['querySaveEditSchedule']['faculty_schedule_id'];
        $faculty_id = $_POST['querySaveEditSchedule']['faculty_id'];
        $course_id = $_POST['querySaveEditSchedule']['course_id'];
        $subject_id = $_POST['querySaveEditSchedule']['subject_id'];
        $section_id = $_POST['querySaveEditSchedule']['section_id'];
        $day_id = $_POST['querySaveEditSchedule']['day_id'];
        $time_start = $_POST['querySaveEditSchedule']['time_start'];
        $time_end = $_POST['querySaveEditSchedule']['time_end'];

        $updateFacultySchedule = $schedule->updateFacultySchedule($faculty_schedule_id, $course_id, $subject_id, $faculty_id, $section_id, $day_id, $time_start, $time_end);
        if ($updateFacultySchedule) {
            echo "success";
        } else {
            echo 'failed';
        }
    }

    // Adding new Course
    if (isset($_POST['queryAddCourse'])) {
        $course_name = $_POST['queryAddCourse']['course_name'];
        $course_abbr = $_POST['queryAddCourse']['course_abbr'];
        $insertNewCourse = $course->insertNewCourse($course_name, $course_abbr);
        if ($insertNewCourse) {
            echo "success";
        } else {
            echo "failed";
        }
    }

    // Adding new Section
    if (isset($_POST['queryAddSection'])) {
        $course_id = $_POST['queryAddSection']['course_id'];
        $section_name = $_POST['queryAddSection']['section_name'];
        $insertNewSection = $section->insertNewSection($course_id, $section_name);
        if ($insertNewSection) {
            echo "success";
        } else {
            echo "failed";
        }
    }