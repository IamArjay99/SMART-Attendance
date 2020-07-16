<?php

    session_start();

    // Require fpdf to use
    require 'fpdf.php';

    // Include all classes
    include "../includes/all.include.php";

    // Faculty
    if (isset($_GET['faculty_id']) && isset($_GET['subject_id']) && isset($_GET['section_id']) && isset($_GET['course_id'])) {
        $faculty_id = $_GET['faculty_id'];
        $subject_id = $_GET['subject_id'];
        $section_id = $_GET['section_id'];
        $course_id = $_GET['course_id'];

        // Archive
        if (isset($_GET['type']) && $_GET['type'] === "archive") {
            $getFacultyInfo = $faculty->getFacultyInfo($faculty_id);
            $faculty_name = $getFacultyInfo['name'];
            $getProfession = $profession->getProfession($getFacultyInfo['profession_id']);
            $faculty_profession = $getProfession['abbr'];
            $getSubject = $subject->getSubject($subject_id);
            $subject_code = $getSubject['code'];
            $subject_name = $getSubject['name'];
            $getSection = $section->getSection($section_id);
            $section_name = $getSection['name'];

            class myPDF extends FPDF {
                function _construct ($orientation = 'L', $unit = 'pt', $format = 'Letter', $margin = 40) {
                    $this->FPDF($orientation, $unit, $format);
                    $this->SetTopMargin($margin);
                    $this->SetBottomMargin($margin);
                    $this->SetLeftMargin($margin);
                    $this->SetRightMargin($margin);
                    $this->SetAutoPageBreak(true, $margin);
                }

                function header() {
                    global $subject_code, $subject_name;
                    global $faculty_profession, $faculty_name;
                    global $section_name;

                    $this->Image('../dist/img/logo.png', 10,10);
                    $this->SetFont('Arial', 'B', 20);
                    $this->SetY(16);
                    $this->Cell(160,20, "SMART ATTENDANCE" , 0,0,'C');
                    $this->Ln();

                    $this->SetFont('Arial', '', 14);
                    $this->setY(45);
                    $this->Cell(40, 8, "Subject Code ", 1, 0, 'C');
                    $this->setX(50);
                    $this->Cell(98, 8, $subject_code , 1, 0, 'L');
                    $this->setX(148);
                    $this->Cell(40, 8, "Section ", 1, 0, 'C');
                    $this->setX(188);
                    $this->Cell(98, 8, $section_name , 1, 0, 'L');
                    $this->Ln();
                    
                    $this->setY(53);
                    $this->Cell(40, 8, "Subject Title ", 1, 0, 'C');
                    $this->setX(50);
                    $this->Cell(98, 8, $subject_name , 1, 0, 'L');
                    $this->setX(148);
                    $this->Cell(40, 8, "Instructor ", 1, 0, 'C');
                    $this->setX(188);
                    $this->Cell(98, 8, $faculty_profession.". ".$faculty_name , 1, 0, 'L');
                    $this->Ln();

                    $this->setY(69);
                    $this->Cell(61, 16, "Names", 1, 0, 'C');
                    $this->setX(71);
                    $this->Cell(215, 8, "Dates", 1, 0, 'C');
                    $this->Ln();
                    $this->setX(71);
                    $this->Cell(215, 8, "", 1, 0, 'C');
                }

                function attendanceTable() {
                    global $course_id, $subject_id, $faculty_id, $section_id, $date, $enroll, $student, $attendance;
                    $getArchiveDates = $date->getArchiveDates($course_id, $subject_id, $faculty_id, $section_id);
                    $countDates = count($getArchiveDates);
                    $cellDateWidth = 215 / $countDates;
                    $marginXDate = 71;
                    if ($countDates > 0) {
                        foreach($getArchiveDates as $getDate) {
                            $this->setY(77);
                            $this->setX($marginXDate);
                            $this->Cell($cellDateWidth, 8, date('m/d/y' ,strtotime($getDate['date'])), 1, 0, 'C');
                            $marginXDate += $cellDateWidth;
                        }
                    } else {
                        $this->setY(77);
                        $this->setX(71);
                        $this->Cell(215, 8, " - ", 1, 0, 'C');
                    }
                    $this->Ln();
                    $getAllEnrollees = $enroll->getAllEnrollees($course_id, $subject_id, $faculty_id, $section_id);
                    $countEnrollees = count($getAllEnrollees);
                    if ($countEnrollees > 0) {
                        $num = 1;
                        foreach($getAllEnrollees as $getAllEnrollee) {
                            $student_id = $getAllEnrollee['student_id'];
                            $getStudent = $student->getStudent($student_id);
                            $student_name = $getStudent['name'];
                            $item = $num.". ".$student_name;
                            $countX = 61;
                            $fontSize = 14;
                            $decrement_step = 0.1;
                            while ($this->GetStringWidth($item) > $countX - 2) {
                                $this->SetFontSize($fontSize -= $decrement_step);
                                $this->SetFont('Arial', '', $fontSize);
                            }
                            $this->Cell($countX, 8, $item, 1, 0, 'L');
                            $this->SetFont('Arial', '', 14);
                            if (count($getArchiveDates) > 0) {
                                $marginXAttendance = 71;
                                foreach($getArchiveDates as $getDate) {
                                    $date_id = $getDate['id'];
                                    $getAttendances = $attendance->getAttendances($course_id, $subject_id, $faculty_id, $section_id, $student_id, $date_id);
                                    if (count($getAttendances) > 0) {
                                        foreach($getAttendances as $getAttendance) {
                                            $presence = $getAttendance['presence'];
                                            $this->setX($marginXAttendance);
                                            $this->Cell($cellDateWidth, 8, $presence, 1, 0, 'C');
                                            $marginXAttendance += $cellDateWidth;
                                        }
                                    } else {
                                        $this->setX($marginXAttendance);
                                        $this->Cell($cellDateWidth, 8, "-", 1, 0, 'C');
                                        $marginXAttendance += $cellDateWidth;
                                    }
                                }
                            } else {
                                $this->setX(71);
                                $this->Cell(215, 8, "-", 1, 0, 'C');
                            }
                            $this->Ln();
                            $num++;
                        }
                    } 
                    else {
                        $this->Cell(276, 8, "-", 1, 0, 'C');
                        $this->Ln();
                    }
                    
                }
            }

            $pdf = new myPDF();
            $pdf->AliasNbPages();
            $pdf->AddPage('L', '', 0);
            $pdf->attendanceTable();
            $pdf->Output('download.pdf', 'F');
            header("Location: download.pdf");
        } 
        // Not archive
        else {
            $getFacultyInfo = $faculty->getFacultyInfo($faculty_id);
            $faculty_name = $getFacultyInfo['name'];
            $getProfession = $profession->getProfession($getFacultyInfo['profession_id']);
            $faculty_profession = $getProfession['abbr'];
            $getSubject = $subject->getSubject($subject_id);
            $subject_code = $getSubject['code'];
            $subject_name = $getSubject['name'];
            $getSection = $section->getSection($section_id);
            $section_name = $getSection['name'];

            class myPDF extends FPDF {
                function _construct ($orientation = 'L', $unit = 'pt', $format = 'Letter', $margin = 40) {
                    $this->FPDF($orientation, $unit, $format);
                    $this->SetTopMargin($margin);
                    $this->SetBottomMargin($margin);
                    $this->SetLeftMargin($margin);
                    $this->SetRightMargin($margin);
                    $this->SetAutoPageBreak(true, $margin);
                }

                function header() {
                    global $subject_code, $subject_name;
                    global $faculty_profession, $faculty_name;
                    global $section_name;

                    $this->Image('../dist/img/logo.png', 10,10);
                    $this->SetFont('Arial', 'B', 20);
                    $this->SetY(16);
                    $this->Cell(160,20, "SMART ATTENDANCE" , 0,0,'C');
                    $this->Ln();

                    $this->SetFont('Arial', '', 14);
                    $this->setY(45);
                    $this->Cell(40, 8, "Subject Code ", 1, 0, 'C');
                    $this->setX(50);
                    $this->Cell(98, 8, $subject_code , 1, 0, 'L');
                    $this->setX(148);
                    $this->Cell(40, 8, "Section ", 1, 0, 'C');
                    $this->setX(188);
                    $this->Cell(98, 8, $section_name , 1, 0, 'L');
                    $this->Ln();
                    
                    $this->setY(53);
                    $this->Cell(40, 8, "Subject Title ", 1, 0, 'C');
                    $this->setX(50);
                    $this->Cell(98, 8, $subject_name , 1, 0, 'L');
                    $this->setX(148);
                    $this->Cell(40, 8, "Instructor ", 1, 0, 'C');
                    $this->setX(188);
                    $this->Cell(98, 8, $faculty_profession.". ".$faculty_name , 1, 0, 'L');
                    $this->Ln();

                    $this->setY(69);
                    $this->Cell(61, 16, "Names", 1, 0, 'C');
                    $this->setX(71);
                    $this->Cell(215, 8, "Dates", 1, 0, 'C');
                    $this->Ln();
                    $this->setX(71);
                    $this->Cell(215, 8, "", 1, 0, 'C');
                }

                function attendanceTable() {
                    global $course_id, $subject_id, $faculty_id, $section_id, $date, $enroll, $student, $attendance;
            
                    $getDates = $date->getDates($course_id, $subject_id, $faculty_id, $section_id);
                    $countDates = count($getDates);
                    $cellDateWidth = 215 / $countDates;
                    $marginXDate = 71;
                    if ($countDates > 0) {
                        foreach($getDates as $getDate) {
                            $this->setY(77);
                            $this->setX($marginXDate);
                            $this->Cell($cellDateWidth, 8, date('m/d/y' ,strtotime($getDate['date'])), 1, 0, 'C');
                            $marginXDate += $cellDateWidth;
                        }
                    } else {
                        $this->setY(77);
                        $this->setX(71);
                        $this->Cell(215, 8, " - ", 1, 0, 'C');
                    }
                    $this->Ln();
                    $getAllEnrollees = $enroll->getAllEnrollees($course_id, $subject_id, $faculty_id, $section_id);
                    $countEnrollees = count($getAllEnrollees);
                    if ($countEnrollees > 0) {
                        $num = 1;
                        foreach($getAllEnrollees as $getAllEnrollee) {
                            $student_id = $getAllEnrollee['student_id'];
                            $getStudent = $student->getStudent($student_id);
                            $student_name = $getStudent['name'];
                            $item = $num.". ".$student_name;
                            $countX = 61;
                            $fontSize = 14;
                            $decrement_step = 0.1;
                            while ($this->GetStringWidth($item) > $countX - 2) {
                                $this->SetFontSize($fontSize -= $decrement_step);
                                $this->SetFont('Arial', '', $fontSize);
                            }
                            $this->Cell($countX, 8, $item, 1, 0, 'L');
                            $this->SetFont('Arial', '', 14);
                            if (count($getDates) > 0) {
                                $marginXAttendance = 71;
                                foreach($getDates as $getDate) {
                                    $date_id = $getDate['id'];
                                    $getAttendances = $attendance->getAttendances($course_id, $subject_id, $faculty_id, $section_id, $student_id, $date_id);
                                    if (count($getAttendances) > 0) {
                                        foreach($getAttendances as $getAttendance) {
                                            $presence = $getAttendance['presence'];
                                            $this->setX($marginXAttendance);
                                            $this->Cell($cellDateWidth, 8, $presence, 1, 0, 'C');
                                            $marginXAttendance += $cellDateWidth;
                                        }
                                    } else {
                                        $this->setX($marginXAttendance);
                                        $this->Cell($cellDateWidth, 8, "-", 1, 0, 'C');
                                        $marginXAttendance += $cellDateWidth;
                                    }
                                }
                            } else {
                                $this->setX(71);
                                $this->Cell(215, 8, "-", 1, 0, 'C');
                            }
                            $this->Ln();
                            $num++;
                        }
                    } 
                    else {
                        $this->Cell(276, 8, "-", 1, 0, 'C');
                        $this->Ln();
                    }
                    
                }
            }

            $pdf = new myPDF();
            $pdf->AliasNbPages();
            $pdf->AddPage('L', '', 0);
            $pdf->attendanceTable();
            $pdf->Output('download.pdf', 'F');
            header("Location: download.pdf");
        }
    }

    // Student
    if (isset($_GET['course_id']) && isset($_GET['subject_id']) && isset($_GET['student_id'])) {
        $course_id = $_GET['course_id'];
        $subject_id = $_GET['subject_id'];
        $student_id = $_GET['student_id'];

        $getStudent = $student->getStudent($student_id);
        $student_name = $getStudent['name'];
        $student_number = $getStudent['student_number'];
        $student_year_id = $getStudent['year_id'];
        $getYear = $year->getYear($student_year_id);
        $getCourse = $course->getCourse($course_id);
        $course_name = $getCourse['name'];
        $year_name = $getYear['name'];

        if (isset($_GET['faculty_id'])) {
            $faculty_id = $_GET['faculty_id'];
            $getStudentEnrollSubject = $enroll->getStudentEnrollSubject($course_id, $subject_id, $student_id);
            $section_id = $getStudentEnrollSubject['section_id'];
            // $faculty_id = $getStudentEnrollSubject['faculty_id'];
            $getFacultyInfo = $faculty->getFacultyInfo($faculty_id);
            $faculty_name = $getFacultyInfo['name'];
            $getProfession = $profession->getProfession($getFacultyInfo['profession_id']);
            $faculty_profession = $getProfession['abbr'];
            $display_instructor = $faculty_profession.". ".$faculty_name;

            if ($subject_id !== "all") {
                $getSubject = $subject->getSubject($subject_id);
                $subject_code = $getSubject['code'];
                $subject_name = $getSubject['name'];
                $display_subject = $subject_code." - ".$subject_name;
    
                $getSection = $section->getSection($section_id);
                $section_name = $getSection['name'];
                $display_yrsec = $year_name." - ".$section_name;
            } else {
                $display_subject = $display_yrsec = "-";
            }
    
            class myPDF extends FPDF {
                function _construct ($orientation = 'L', $unit = 'pt', $format = 'Letter', $margin = 40) {
                    $this->FPDF($orientation, $unit, $format);
                    $this->SetTopMargin($margin);
                    $this->SetBottomMargin($margin);
                    $this->SetLeftMargin($margin);
                    $this->SetRightMargin($margin);
                    $this->SetAutoPageBreak(true, $margin);
                }
    
                function header() {
                    global $student_name, $student_number;
                    global $course_name, $year_name;
                    global $display_subject;
                    global $display_yrsec;
                    global $display_instructor;
    
                    $this->Image('../dist/img/logo.png', 10,10);
                    $this->SetFont('Arial', 'B', 20);
                    $this->SetY(16);
                    $this->Cell(160,20, "SMART ATTENDANCE" , 0,0,'C');
                    $this->Ln();
    
                    $this->SetFont('Arial', '', 14);
                    $this->setY(45);
                    $this->Cell(40, 8, "Student Number", 1, 0, 'L');
                    $this->setX(50);
                    $this->Cell(98, 8, $student_number , 1, 0, 'L');
                    $this->setX(148);
                    $this->Cell(40, 8, "Subject", 1, 0, 'L');
                    $this->setX(188);
                    $this->Cell(98, 8, $display_subject , 1, 0, 'L');
                    $this->Ln();
                    
                    $this->setY(53);
                    $this->Cell(40, 8, "Name", 1, 0, 'L');
                    $this->setX(50);
                    $this->Cell(98, 8, $student_name , 1, 0, 'L');
                    $this->setX(148);
                    $this->Cell(40, 8, "Year & Section", 1, 0, 'L');
                    $this->setX(188);
                    $this->Cell(98, 8, $display_yrsec, 1, 0, 'L');
                    $this->Ln();
    
                    $this->setY(61);
                    $this->Cell(40, 8, "Course", 1, 0, 'L');
                    $this->setX(50);
                    $item = $course_name;
                    $countX = 98;
                    $fontSize = 14;
                    $decrement_step = 0.1;
                    while ($this->GetStringWidth($item) > $countX - 2) {
                        $this->SetFontSize($fontSize -= $decrement_step);
                        $this->SetFont('Arial', '', $fontSize);
                    }
                    $this->Cell($countX, 8, $item, 1, 0, 'L');
                    $this->SetFont('Arial', '', 14);
                    $this->setX(148);
                    $this->Cell(40, 8, "Instructor", 1, 0, 'L');
                    $this->setX(188);
                    $this->Cell(98, 8, $display_instructor, 1, 0, 'L');
                    $this->Ln();

                    $this->setY(77);
                    $this->Cell(69, 8, "Time", 1, 0, 'C');
                    $this->setX(79);
                    $this->Cell(69, 8, "Date", 1, 0, 'C');
                    $this->setX(148);
                    $this->Cell(69, 8, "Subject", 1, 0, 'C');
                    $this->setX(217);
                    $this->Cell(69, 8, "", 1, 0, 'C');
                    $this->Ln();
                }
    
                function attendanceTable() {
                    global $course_id, $subject_id, $student_id, $faculty_id;
                    global $attendance, $date, $subject;
    
                    $getStudentAttendances = $attendance->getStudentAttendances($course_id, $subject_id, $student_id, $faculty_id);
                    if (count($getStudentAttendances) > 0) {
                        foreach($getStudentAttendances as $getStudentAttendance) {
                            $getSubject = $subject->getSubject($getStudentAttendance['subject_id']);
                            $subject_name = $getSubject['name'];
                            $date_id = $getStudentAttendance['date_id'];
                            $getDate = $date->getDate($date_id);
                            $dates = $getDate['date'];
    
                            $checkDate = $date->checkDate($date_id);
                            $dateDeletedAt = $checkDate['deleted_at'];
    
                            if ($dateDeletedAt === NULL) {
                                $this->Cell(69, 8, date("h:iA", strtotime($getStudentAttendance['time'])), 1, 0, 'C');
                                $this->setX(79);
                                $this->Cell(69, 8, date("M d, Y", strtotime($dates)), 1, 0, 'C');
                                $this->setX(148);
                                $this->Cell(69, 8, $subject_name, 1, 0, 'C');
                                $this->setX(217);
                                $this->Cell(69, 8, $getStudentAttendance['presence'], 1, 0, 'C');
                                $this->Ln();
                            }
                        }
                    } else {
                        $this->Cell(69, 8, "-", 1, 0, 'C');
                        $this->setX(79);
                        $this->Cell(69, 8, "-", 1, 0, 'C');
                        $this->setX(148);
                        $this->Cell(69, 8, "-", 1, 0, 'C');
                        $this->setX(217);
                        $this->Cell(69, 8, "-", 1, 0, 'C');
                        $this->Ln();
                    }
                }
            }
    
            $pdf = new myPDF();
            $pdf->AliasNbPages();
            $pdf->AddPage('L', '', 0);
            $pdf->attendanceTable();
            $pdf->Output('download.pdf', 'F');
            header("Location: download.pdf");
        } else {
            $getStudentEnrollSubject = $enroll->getStudentEnrollSubject($course_id, $subject_id, $student_id);
            $section_id = $getStudentEnrollSubject['section_id'];
            $faculty_id = $getStudentEnrollSubject['faculty_id'];

            if ($subject_id !== "all") {
                $getSubject = $subject->getSubject($subject_id);
                $subject_code = $getSubject['code'];
                $subject_name = $getSubject['name'];
                $display_subject = $subject_code." - ".$subject_name;
    
                $getFacultyInfo = $faculty->getFacultyInfo($faculty_id);
                $faculty_name = $getFacultyInfo['name'];
                $getProfession = $profession->getProfession($getFacultyInfo['profession_id']);
                $faculty_profession = $getProfession['abbr'];
                $display_instructor = $faculty_profession.". ".$faculty_name;
    
                $getSection = $section->getSection($section_id);
                $section_name = $getSection['name'];
                $display_yrsec = $year_name." - ".$section_name;
            } else {
                $display_subject = $display_instructor = $display_yrsec = "-";
            }
    
            class myPDF extends FPDF {
                function _construct ($orientation = 'L', $unit = 'pt', $format = 'Letter', $margin = 40) {
                    $this->FPDF($orientation, $unit, $format);
                    $this->SetTopMargin($margin);
                    $this->SetBottomMargin($margin);
                    $this->SetLeftMargin($margin);
                    $this->SetRightMargin($margin);
                    $this->SetAutoPageBreak(true, $margin);
                }
    
                function header() {
                    global $student_name, $student_number;
                    global $course_name, $year_name;
                    global $display_subject;
                    global $display_yrsec;
                    global $display_instructor;
    
                    $this->Image('../dist/img/logo.png', 10,10);
                    $this->SetFont('Arial', 'B', 20);
                    $this->SetY(16);
                    $this->Cell(160,20, "SMART ATTENDANCE" , 0,0,'C');
                    $this->Ln();
    
                    $this->SetFont('Arial', '', 14);
                    $this->setY(45);
                    $this->Cell(40, 8, "Student Number", 1, 0, 'L');
                    $this->setX(50);
                    $this->Cell(98, 8, $student_number , 1, 0, 'L');
                    $this->setX(148);
                    $this->Cell(40, 8, "Subject", 1, 0, 'L');
                    $this->setX(188);
                    $this->Cell(98, 8, $display_subject , 1, 0, 'L');
                    $this->Ln();
                    
                    $this->setY(53);
                    $this->Cell(40, 8, "Name", 1, 0, 'L');
                    $this->setX(50);
                    $this->Cell(98, 8, $student_name , 1, 0, 'L');
                    $this->setX(148);
                    $this->Cell(40, 8, "Year & Section", 1, 0, 'L');
                    $this->setX(188);
                    $this->Cell(98, 8, $display_yrsec, 1, 0, 'L');
                    $this->Ln();
    
                    $this->setY(61);
                    $this->Cell(40, 8, "Course", 1, 0, 'L');
                    $this->setX(50);
                    $item = $course_name;
                    $countX = 98;
                    $fontSize = 14;
                    $decrement_step = 0.1;
                    while ($this->GetStringWidth($item) > $countX - 2) {
                        $this->SetFontSize($fontSize -= $decrement_step);
                        $this->SetFont('Arial', '', $fontSize);
                    }
                    $this->Cell($countX, 8, $item, 1, 0, 'L');
                    $this->SetFont('Arial', '', 14);
                    $this->setX(148);
                    $this->Cell(40, 8, "Instructor", 1, 0, 'L');
                    $this->setX(188);
                    $this->Cell(98, 8, $display_instructor, 1, 0, 'L');
                    $this->Ln();

                    $this->setY(77);
                    $this->Cell(69, 8, "Time", 1, 0, 'C');
                    $this->setX(79);
                    $this->Cell(69, 8, "Date", 1, 0, 'C');
                    $this->setX(148);
                    $this->Cell(69, 8, "Subject", 1, 0, 'C');
                    $this->setX(217);
                    $this->Cell(69, 8, "", 1, 0, 'C');
                    $this->Ln();
                }
    
                function attendanceTable() {
                    global $course_id, $subject_id, $student_id;
                    global $attendance, $date, $subject;
    
                    $getStudentAttendances = $attendance->getStudentAttendances($course_id, $subject_id, $student_id);
                    
                    if (count($getStudentAttendances) > 0) {
                        foreach($getStudentAttendances as $getStudentAttendance) {
                            $getSubject = $subject->getSubject($getStudentAttendance['subject_id']);
                            $subject_name = $getSubject['name'];
                            $date_id = $getStudentAttendance['date_id'];
                            $getDate = $date->getDate($date_id);
                            $dates = $getDate['date'];
    
                            $checkDate = $date->checkDate($date_id);
                            $dateDeletedAt = $checkDate['deleted_at'];
    
                            if ($dateDeletedAt === NULL) {
                                $this->Cell(69, 8, date("h:iA", strtotime($getStudentAttendance['time'])), 1, 0, 'C');
                                $this->setX(79);
                                $this->Cell(69, 8, date("M d, Y", strtotime($dates)), 1, 0, 'C');
                                $this->setX(148);
                                $this->Cell(69, 8, $subject_name, 1, 0, 'C');
                                $this->setX(217);
                                $this->Cell(69, 8, $getStudentAttendance['presence'], 1, 0, 'C');
                                $this->Ln();
                            }
                        }
                    } else {
                        $this->Cell(69, 8, "-", 1, 0, 'C');
                        $this->setX(79);
                        $this->Cell(69, 8, "-", 1, 0, 'C');
                        $this->setX(148);
                        $this->Cell(69, 8, "-", 1, 0, 'C');
                        $this->setX(217);
                        $this->Cell(69, 8, "-", 1, 0, 'C');
                        $this->Ln();
                    }
                }
            }
    
            $pdf = new myPDF();
            $pdf->AliasNbPages();
            $pdf->AddPage('L', '', 0);
            $pdf->attendanceTable();
            $pdf->Output('download.pdf', 'F');
            header("Location: download.pdf");
        }
    }
