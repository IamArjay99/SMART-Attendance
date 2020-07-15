<?php

    session_start();

    // Require fpdf to use
    require 'fpdf.php';

    // Include all classes
    include "../includes/all.include.php";

    if (isset($_GET['faculty_id']) && isset($_GET['subject_id']) && isset($_GET['section_id']) && isset($_GET['course_id'])) {
        $faculty_id = $_GET['faculty_id'];
        $subject_id = $_GET['subject_id'];
        $section_id = $_GET['section_id'];
        $course_id = $_GET['course_id'];

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
                $this->Cell(40, 8, "Course Title ", 1, 0, 'C');
                $this->setX(50);
                $this->Cell(98, 8, $subject_name , 1, 0, 'L');
                $this->setX(148);
                $this->Cell(40, 8, "Instructor ", 1, 0, 'C');
                $this->setX(188);
                $this->Cell(98, 8, $faculty_profession.". ".$faculty_name , 1, 0, 'L');
                $this->Ln();
            }

            function attendanceTable() {
                global $course_id, $subject_id, $faculty_id, $section_id, $date, $enroll, $student, $attendance;
                $this->setY(69);
                $this->Cell(61, 16, "Names", 1, 0, 'C');
                $this->setX(71);
                $this->Cell(215, 8, "Dates", 1, 0, 'C');
                $this->Ln();
                $this->setX(71);
                $this->Cell(215, 8, "", 1, 0, 'C');
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
