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

    $outputFaculties = "";
    $getAllFaculties = $faculty->getAllFaculties();
    foreach($getAllFaculties as $getFaculty) {
        $faculty_id = $getFaculty['id'];
        $profession_id = $getFaculty['profession_id'];
        
        $outputFaculties .= "<tr>";
        $outputFaculties .= "<td>";

        $getProfession = $profession->getProfession($profession_id);
        $profession_name = $getProfession['abbr'];
        $faculty_name = $getFaculty['name'];
        $outputFaculties .= $profession_name.". ".$faculty_name;

        $getFacultySchedules = $schedule->getFacultySchedules($faculty_id);
        $allSubjects = $allSections = $allDayTime = "";
        if (count($getFacultySchedules) > 0) {
            foreach($getFacultySchedules as $getFacultySchedule) {
                $subject_code = $getFacultySchedule['subject_code'];
                $subject_name = $getFacultySchedule['subject_name'];
                $section = $getFacultySchedule['section'];
                $day = $getFacultySchedule['day'];
                $time_start = date("g:iA", strtotime($getFacultySchedule['time_start']));
                $time_end = date("g:iA", strtotime($getFacultySchedule['time_end']));
                $allSubjects .= "<span>".$subject_code." - ".$subject_name."</span><br>";
                $allSections .= "<span>".$section."</span><br>";
                $allDayTime .= "<span>".$day." | ".$time_start."-".$time_end."</span><br>";
            }
        } else {
            $allSubjects = $allSections = $allDayTime = "-";
        }

        $outputFaculties .= "</td>";
        $outputFaculties .= "<td>";
        $outputFaculties .= $allSubjects;
        $outputFaculties .= "</td>";
        $outputFaculties .= "<td>";
        $outputFaculties .= $allSections;
        $outputFaculties .= "</td>";
        $outputFaculties .= "<td>";
        $outputFaculties .= $allDayTime;
        $outputFaculties .= "</td>";
        $outputFaculties .= "</tr>";
    }
?>

<?php startblock("another_css") ?>
<?php endblock() ?>

<?php startblock("main_content") ?>
    <div class="container-fluid p-3">
        <h2 class="text-center my-2">FACULTY</h2>
        <div class="row">
            <div class="offset-md-1 col-md-10 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th colspan="5">SCHEDULES</th>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <th>Subject</th>
                                <th>Section</th>
                                <th>Day & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?= $outputFaculties ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endblock() ?>

<?php startblock("another_js") ?>
<?php endblock() ?>