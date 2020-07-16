<?php
    session_start();

    include '../../layouts/layout.php';

    // Checking If logged in
    if (isset($_SESSION['data'])) {
        if (($_SESSION['data']['role_id'] !== "3")) {
            header("Location: ../../index.php");
        } else {
            $faculty_id = $_SESSION['data']['id'];
        }
    } else {
        header("Location: ../../index.php");
    }

    if (isset($_GET['course_id']) && isset($_GET['subject_id']) && isset($_GET['faculty_id']) && isset($_GET['section_id']) && isset($_GET['date'])) {
        $course_id = $_GET['course_id'];
        $subject_id = $_GET['subject_id'];
        $faculty_id = $_GET['faculty_id'];
        $section_id = $_GET['section_id'];
        $date = $_GET['date'];
        $section_name = $_GET['section_name'];
        
        $getFacultyInfo = $faculty->getFacultyInfo($faculty_id);
        $faculty_name = $getFacultyInfo['name'];
        $getProfession = $profession->getProfession($getFacultyInfo['profession_id']);
        $faculty_profession = $getProfession['abbr'];
        $getSubject = $subject->getSubject($subject_id);
        $subject_code = $getSubject['code'];
        $subject_name = $getSubject['name'];
    } else {
        echo "<script>history.back();</script>";
    }
?>

<?php startblock("another_css") ?>
    <!-- Bootstrap Toggle CDN -->
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<?php endblock() ?>

<?php startblock("main_content") ?>
    <div class="container-fluid p-3">
        <h2 class="text-center my-2">ATTENDANCE</h2>
        <div class="row">
            <div class="offset-md-1 col-md-10 col-sm-12 my-2">
                <div class="col-attendance table-responsive-sm my-2">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th style="background-color: #152E67; color: #F5AD03">Subject Code</th>
                            <td style="color: #152E67; background-color: #F5AD03"><?= $subject_code ?></td>
                        </tr>
                        <tr>
                            <th style="background-color: #152E67; color: #F5AD03">Course Title</th>
                            <td style="color: #152E67; background-color: #F5AD03"><?= $subject_name ?></td>
                        </tr>

                        <tr>
                            <th style="background-color: #152E67; color: #F5AD03">Schedule</th>
                            <td style="color: #152E67; background-color: #F5AD03">
                            <?php
                                $getFacultyScheduleInSection = $schedule->getFacultyScheduleInSection($faculty_id, $subject_id, $section_id);
                  
                                foreach($getFacultyScheduleInSection as $getFacultySchedule) {
                                    $faculty_schedule_id = $getFacultySchedule['id'];
                                    $section = $getFacultySchedule["section"];
                                    $day = $getFacultySchedule["day"];
                                    $time_start = date("g:iA", strtotime($getFacultySchedule["time_start"]));
                                    $time_end = date("g:iA", strtotime($getFacultySchedule["time_end"]));
                                    $sched = $day."\t".$time_start."-".$time_end;
                                    echo $sched."<br>";

                                    $outputAttendances = "";
                                    $getAllEnrollees = $enroll->getAllEnrollees($course_id, $subject_id, $faculty_id, $section_id);
                                    $countEnrollees = count($getAllEnrollees);
                                    $count = 0;
                                    if ($countEnrollees > 0) {
                                        foreach($getAllEnrollees as $getAllEnrollee) {
                                            $student_id = $getAllEnrollee['student_id'];
                                            $getStudent = $student->getStudent($student_id);
                                            $outputAttendances .= "<tr>";
                                            $outputAttendances .= "<td>";
                                            $outputAttendances .= '<img src="../../dist/img/default_image.png" alt="" width="35px" height="35px" class="rounded">';
                                            $outputAttendances .= "<span><a href='#' class='pl-2'>";
                                            $outputAttendances .= $getStudent['name'];
                                            $outputAttendances .= "</a></span>";
                                            $outputAttendances .= "</td>";
                                            $outputAttendances .= "<td class='text-center'>";
                                            $outputAttendances .= '<input type="checkbox" data-toggle="toggle" data-on="Present" data-off="Absent" data-onstyle="primary" data-offstyle="warning" id="student'.$count.'" data-student_id="'.$student_id.'">';
                                            $outputAttendances .= "</td>";
                                            $outputAttendances .= "</tr>";
                                            $count++;
                                        }
                                    } else {
                                        $outputAttendances .= "<tr><td colspan='2' class='text-center'>No data found</td></tr>";
                                    }
                                }
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="background-color: #152E67; color: #F5AD03">Section</th>
                            <td style="color: #152E67; background-color: #F5AD03"><?= $section_name ?></td>
                        </tr>
                        <tr>
                            <th style="background-color: #152E67; color: #F5AD03">Instructor</th>
                            <td style="color: #152E67; background-color: #F5AD03"><?= $faculty_profession.". ".$faculty_name ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-attendance table-responsive-sm my-2">
                    <table class="table table-bordered table-striped attendance-table">
                        <thead class="thead-dark text-center">
                            <tr>
                                <th rowspan="2">NAMES</th>
                                <th>DATE</th>
                            </tr>
                            <tr>
                                <th><?= date("M d, Y", strtotime($date)) ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?= $outputAttendances ?>
                        </tbody>
                    </table>
                    <div class="float-right">
                        <button class="btn btn-success btn-md" 
                                id="save" data-count="<?= $count ?>" 
                                data-course_id="<?= $course_id ?>" 
                                data-subject_id="<?= $subject_id ?>"
                                data-faculty_id="<?= $faculty_id ?>"
                                data-section_id="<?= $section_id ?>"
                                data-date="<?= $date ?>"
                                data-time="<?= date("h:i:s") ?>">SAVE</button>
                        <button class="btn btn-danger btn-md" onclick="history.back();">CANCEL</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endblock() ?>

<?php startblock("another_js") ?>
    <script>
        $(document).ready(function() {
            $("#save").on("click", function() {
                const count = $(this).data("count");
                const course_id = $(this).data("course_id");
                const subject_id = $(this).data("subject_id");
                const faculty_id = $(this).data("faculty_id");
                const section_id = $(this).data("section_id");
                const date = $(this).data("date");
                const time = $(this).data("time");

                var data = {
                    course_id,
                    subject_id,
                    faculty_id,
                    section_id,
                    date
                }
                // Saving Dates
                $.ajax({
                    method: "POST",
                    url: "../../ajax/process-attendance.ajax.php",
                    data: { queryAttendanceDate : data },
                    success: function(data) {
                        // Saving individual attendacne
                        for(var i=0; i<count; i++) {
                            const studentId = $("#student"+i).data("student_id");
                            const studentPresence = $("#student"+i+":checked").val();
                            let presence = studentPresence ? "P" : "A";
                            var data = {
                                course_id,
                                subject_id,
                                faculty_id,
                                section_id,
                                student_id: studentId,
                                date,
                                presence,
                                time
                            }
                            $.ajax({
                                method: "POST",
                                url: "../../ajax/process-attendance.ajax.php",
                                data: { querySaveAttendance : data },
                                success: function(data) {
                                    // alert(data);
                                }
                            });
                        }
                    }
                });
                // Assume that all student attendance save successfully in 2s
                setTimeout(() => {
                    location.href = "subject_professor.php?faculty_id="+faculty_id+"&subject_id="+subject_id;
                }, 2000);
            });
        })
    </script>
<?php endblock() ?>