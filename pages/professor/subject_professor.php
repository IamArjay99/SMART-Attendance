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

    if (isset($_GET['faculty_id']) && isset($_GET['subject_id'])) {
        $faculty_id = $_GET['faculty_id'];
        $subject_id = $_GET['subject_id'];
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

    $today = date("yy-m-d");  
?>

<?php startblock("another_css") ?>
<?php endblock() ?>

<?php startblock("main_content") ?>
    <div class="container-fluid p-3">
        <h2 class="text-center my-2">SUBJECT</h2>
        <div class="row">
            <?php
                $getFacultyScheduleBySections = $schedule->getFacultyScheduleBySections($faculty_id, $subject_id);
                foreach($getFacultyScheduleBySections as $getFacultyScheduleBySection) {
                    $section_id = $getFacultyScheduleBySection['section_id'];
                    $course_id = $getFacultyScheduleBySection['course_id'];
            ?>
            <div class="offset-md-1 col-md-10 col-sm-12 my-2">
                <div class="col-header d-flex justify-content-between align-items-end w-100">
                    <div id="pla" class="">
                        <img src="../../dist/img/pla.png" height="70px" width="130px">
                    </div>
                    <div class="btn-group">
                        <a href="archive_professor.php?faculty_id=<?= $faculty_id ?>&subject_id=<?= $subject_id ?>&section_id=<?= $section_id ?>" class="btn btn-link text-secondary font-weight-bold"><h5>Archived</h5></a>
                        <a href="../../pdf/download.php?faculty_id=<?= $faculty_id ?>&subject_id=<?= $subject_id ?>&section_id=<?= $section_id ?>&course_id=<?= $course_id ?>" target="_blank" class="btn btn-link text-danger font-weight-bold"><h5>Download PDF File</h5></a>
                    </div>
                </div>
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
                                    $section = $getFacultySchedule["section"];
                                    $day = $getFacultySchedule["day"];
                                    $time_start = date("g:iA", strtotime($getFacultySchedule["time_start"]));
                                    $time_end = date("g:iA", strtotime($getFacultySchedule["time_end"]));
                                    $sched = $day."\t".$time_start."-".$time_end;
                                    echo $sched."<br>";
                                }

                                // This is for dates
                                $getDates = $date->getDates($course_id, $subject_id, $faculty_id, $section_id);
                                $outputDates = "";
                                $countDates = count($getDates);
                                if ($countDates > 0) {
                                    foreach($getDates as $getDate) {
                                        $outputDates .= "<td>";
                                        // if ($_SESSION['data']['id'] === $faculty_id) {
                                            $outputDates .= '<button class="btn btn-danger btn-sm btn-delete-attendance" data-date="'.$getDate['date'].'" 
                                            data-course_id="'.$course_id.'"
                                            data-subject_id="'.$subject_id.'"
                                            data-faculty_id="'.$faculty_id.'"
                                            data-section_id="'.$section_id.'"
                                            ><i class="fa fa-trash" aria-hidden="true"></i></button><br>';
                                        // }
                                        $outputDates .= "<span>";
                                        $outputDates .= date('m/d/Y' ,strtotime($getDate['date']));
                                        $outputDates .= "</span>";
                                        $outputDates .= "</td>";
                                    }
                                } else {
                                    $outputDates .= "<td> - </td>";
                                }

                                // Attendance
                                $outputAttendances = "";
                                $getAllEnrollees = $enroll->getAllEnrollees($course_id, $subject_id, $faculty_id, $section_id);
                                $countEnrollees = count($getAllEnrollees);
                                if ($countEnrollees > 0) {
                                    foreach($getAllEnrollees as $getAllEnrollee) {
                                        $student_id = $getAllEnrollee['student_id'];
                                        $getStudent = $student->getStudent($student_id);
                                        $outputAttendances .= "<tr>";
                                        $outputAttendances .= "<td colspan='2'>";
                                        $outputAttendances .= '<img src="../../dist/img/default_image.png" alt="" width="35px" height="35px" class="rounded">';
                                        $outputAttendances .= "<span><a href='student_professor.php?student_id=".$student_id."&course_id=".$course_id."&subject_id=".$subject_id."' class='pl-2'>";
                                        $outputAttendances .= $getStudent['name'];
                                        $outputAttendances .= "</a></span>";
                                        $outputAttendances .= "</td>";

                                        if (count($getDates) > 0) {
                                            foreach($getDates as $getDate) {
                                                $date_id = $getDate['id'];
                                                $getAttendances = $attendance->getAttendances($course_id, $subject_id, $faculty_id, $section_id, $student_id, $date_id);
                                                if (count($getAttendances) > 0) {
                                                    foreach($getAttendances as $getAttendance) {
                                                        $presence = $getAttendance['presence'];
                                                        if ($presence === "P") {
                                                            $outputAttendances .= "<td style='color: #1B2C64; font-weight: bolder; text-align: center'>";
                                                        } else if ($presence === "L") {
                                                            $outputAttendances .= "<td style='color: #E39D00; font-weight: bolder; text-align: center'>";
                                                        } else {
                                                            $outputAttendances .= "<td style='color: #353537; font-weight: bolder; text-align: center'>";
                                                        }
                                                        $outputAttendances .= $presence;
                                                        $outputAttendances .= "</td>";
                                                    }
                                                } else {
                                                    $outputAttendances .= "<td class='text-center'> - </td>";
                                                }
                                            }
                                        } else {
                                            $outputAttendances .= "<td class='text-center'> - </td>";
                                        }
                                        $outputAttendances .= "</tr>";
                                    }
                                } 
                                else {
                                    $outputAttendances .= "<tr><td colspan='3' class='text-center'>No data found</td></tr>";
                                }
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="background-color: #152E67; color: #F5AD03">Section</th>
                            <td style="color: #152E67; background-color: #F5AD03"><?= $getFacultyScheduleBySection['section'] ?></td>
                        </tr>
                        <tr>
                            <th style="background-color: #152E67; color: #F5AD03">Instructor</th>
                            <td style="color: #152E67; background-color: #F5AD03"><?= $faculty_profession.". ".$faculty_name ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-attendance table-responsive-sm my-2">
                    <?php 
                        // if ($_SESSION['data']['id'] === $faculty_id) { 
                        if ($countEnrollees > 0) {
                            $disabled = "";
                        } else {
                            $disabled = "disabled";
                        }
                        //}
                    ?>
                    <button class="btn btn-info btn-sm float-right m-1 take-attendance"  
                            data-section_name="<?= $getFacultyScheduleBySection['section'] ?>" 
                            data-course_id="<?= $course_id ?>"
                            data-subject_id="<?= $subject_id ?>"
                            data-faculty_id="<?= $faculty_id ?>"
                            data-section_id="<?= $section_id ?>"
                            <?= $disabled ?>>Take Attendance</button>
                    <table class="table table-bordered table-striped attendance-table">
                        <thead class="thead-dark text-center">
                            <tr>
                                <th rowspan="2" colspan="2">NAMES</th>
                                <th colspan="<?= $countDates ?>">
                                    <span>DATES</span>
                                </th>
                            </tr>
                            <tr><?= $outputDates ?></tr>
                        </thead>
                        <tbody>
                            <?= $outputAttendances ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
                }
            ?>
        </div>
    </div>

    <!-- Take Attendance Modal -->
    <div class="modal" tabindex="-1" role="dialog" id="take-attendance-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="attendance_professor.php" method="GET">
                    <div class="modal-header">
                        <h5 class="modal-title">Take Attendance - <span id="attendance-section"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#date">Date</label>
                            <input type="date" name="date" id="date" value="<?= $today ?>" class="form-control" required>
                            <input type="hidden" name="section_name" id="section_name">
                            <input type="hidden" name="course_id" id="course_id">
                            <input type="hidden" name="subject_id" id="subject_id">
                            <input type="hidden" name="faculty_id" id="faculty_id">
                            <input type="hidden" name="section_id" id="section_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Okay</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endblock() ?>

<?php startblock("another_js") ?>
    <script>
        $(document).ready( function () {

            // Delete Attendance
            $('.btn-delete-attendance').on('click', function() {
                const course_id = $(this).data("course_id");
                const subject_id = $(this).data("subject_id");
                const faculty_id = $(this).data("faculty_id");
                const section_id = $(this).data("section_id");
                const date = $(this).data("date");
                const data = { 
                    course_id,
                    subject_id,
                    faculty_id,
                    section_id,
                    date
                }
                Swal.fire({
                    title: 'Are you sure?',
                    text: ""+ date+" will be store in the archive",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            method: "POST",
                            url: "../../ajax/process-attendance.ajax.php",
                            data: { queryDeleteAttendance: data },
                            success: function(data) {
                                if (data === "success") {
                                    Swal.fire({
                                        title: 'Removed',
                                        text: "The attendance was successfully removed",
                                        icon: 'success',
                                        timer: 3000,
                                        showConfirmButton: false
                                    }).then(function() {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: "There was an error deleting attendance",
                                        icon: 'error',
                                        timer: 3000,
                                        showConfirmButton: false
                                    });
                                }
                            }
                        })
                    }
                })
            });

            // Take attendance
            $(".take-attendance").on("click", function() {
                const sectionName = $(this).data("section_name");
                const course_id = $(this).data("course_id");
                const subject_id = $(this).data("subject_id");
                const faculty_id = $(this).data("faculty_id");
                const section_id = $(this).data("section_id");
                $('#attendance-section').text(sectionName);
                $('#section_name').val(sectionName);
                $('#course_id').val(course_id);
                $('#subject_id').val(subject_id);
                $('#faculty_id').val(faculty_id);
                $('#section_id').val(section_id);
                $("#take-attendance-modal").modal("show");
            });
        });
    </script>
<?php endblock() ?>

