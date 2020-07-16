<?php
    session_start();

    include '../../layouts/layout.php';

    // Checking If logged in
    if (isset($_SESSION['data'])) {
        if (($_SESSION['data']['role_id'] !== "1") && ($_SESSION['data']['role_id'] !== "2")) {
            header("Location: ../../index.php");
        }
    } else {
        header("Location: ../../index.php");
    }

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
    } 
?>

<?php startblock("another_css") ?>
<style>
</style>
<?php endblock() ?>

<?php startblock("main_content") ?>
    <div class="container-fluid p-3">
        <h2 class="text-center my-2">STUDENT</h2>
        <div class="row">
            <div class="offset-md-1 col-md-10 col-sm-12">
                <div class="profile ml-5">
                    <div class="profile-header d-flex justify-content-start align-items-center">
                        <div class="profile-picture p-1" style="border: 3px solid black; height: 150px; width: 150px">
                            <img src="../../dist/img/default_image.png" alt="" class="img-responsive rounded h-100 w-100">
                        </div>	
                        <div class="profile-info pl-2">
                            <div class="student-number"><?= $student_number ?></div>
                            <div class="student-name"><?= strtoupper($student_name) ?></div>
                            <div class="student-course"><?= strtoupper($course_name) ?></div>
                            <div class="student-year"><?= strtoupper($year_name) ?></div>
                            <div class="student-subject">
                                <form action="student_dean_chairperson.php" method="GET" id="form-subjects">
                                <input type="hidden" name="course_id" value="<?= $course_id ?>">
                                <input type="hidden" name="student_id" value="<?= $student_id ?>">
                                    SUBJECT CODE 
                                    <select name="subject_id" id="subject_id">
                                    <?php
                                        $getStudentEnrollSubject = $enroll->getStudentEnrollSubject($course_id, $subject_id, $student_id);
                                        $getSubject = $subject->getSubject($getStudentEnrollSubject['subject_id']);
                                        $subject_code = $getSubject['code'];

                                        $getStudentAllEnrollSubjects = $enroll->getStudentAllEnrollSubjects($course_id, $student_id);
                                        foreach($getStudentAllEnrollSubjects as $getStudentAllEnrollSubject) {
                                            $getSubject = $subject->getSubject($getStudentAllEnrollSubject['subject_id']);
                                            if ($subject_id !== $getSubject['id']) {
                                                echo "<option value='".$getSubject['id']."'>".$getSubject['code']."</option>";
                                            }
                                        }

                                        if ($subject_id !== "all") {
                                            echo "<option value='".$subject_id."' default selected>".$subject_code."</option>";
                                            echo '<option value="all">ALL SUBJECTS</option>';
                                            
                                        } else {
                                            echo '<option value="all" selected default>ALL SUBJECTS</option>';
                                        }
                                    ?>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn-group float-right">
                    <a href="../../pdf/download.php?course_id=<?= $course_id ?>&subject_id=<?= $subject_id ?>&student_id=<?= $student_id ?>" target="_blank" class="btn btn-link text-danger font-weight-bold"><h5>Download PDF File</h5></a>
                </div>
                <div class="table-responsive px-3 pt-2">
                        <table class="table table-bordered table-hover" id="idAttendance">
                            <thead class="text-center thead-dark">
                                <tr>
                                    <th id="time_in">TIME-IN</th>
                                    <th id="date">DATE</th>
                                    <th id="subject">SUBJECT</th>
                                    <th id="remarks">&nbsp</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                            <?php
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
                                        
                                        // Checking if the date is on archive
                                        if ($dateDeletedAt === NULL) {
                                            echo "<tr>";
                                            echo "<td>";
                                            echo date("h:iA", strtotime($getStudentAttendance['time']));
                                            echo "</td>";
                                            echo "<td>";
                                            echo date("M d, Y", strtotime($dates));
                                            echo "</td>";
                                            echo "<td>";
                                            echo $subject_name;
                                            echo "</td>";
                                            if ($getStudentAttendance['presence'] === "P") {
                                                echo '<td class="bg-dark text-success">'. $getStudentAttendance['presence'] .'</td>';
                                            } else {
                                                echo '<td class="bg-dark text-danger">'. $getStudentAttendance['presence'] .'</td>';
                                            }
                                            echo "</tr>";
                                        }
                                    }
                                } else {
                                    echo "<tr><td class='text-center' colspan='4'>No data found</td></tr>";
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endblock() ?>

<?php startblock("another_js") ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const subjectOptions = document.querySelector("#subject_id");
            const formSubjects = document.querySelector("#form-subjects");
            subjectOptions.addEventListener("change", function(e) {
                e.preventDefault();
                formSubjects.submit();
            })
        })
    </script>
<?php endblock() ?>