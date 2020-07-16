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
?>

<?php startblock("another_css") ?>
<style>
    .card {
        background-color: #35383C;
        font-weight: bold;
        border-radius: 15px;
        transition: all 250ms;
        box-shadow: 2px 2px 5px grey;
        min-height: 38vh;
    }
    .card:hover {
        transform: scale(1.02);
    }
    a#card {
        text-decoration: none;
    }
    .card-title {
        color: #FFC0CB;
    }
    .card-text {
        color: white;
    }
</style>
<?php endblock() ?>

<?php startblock("main_content") ?>
    <div class="container-fluid p-3">
        <h2 class="text-center my-2">DASHBOARD</h2>
        <div class="row px-3 py-2">
        <?php
            $getAllFacultySchedules = $schedule->getFacultySchedules($faculty_id);
            foreach($getAllFacultySchedules as $getAllFacultySchedule) {
                // $faculty_id = $getAllFacultySchedule['faculty_id'];
                $subject_id = $getAllFacultySchedule['subject_id'];
                $getFacultyInfo = $faculty->getFacultyInfo($faculty_id);
                $faculty_name = $getFacultyInfo['name'];
                $getProfession = $profession->getProfession($getFacultyInfo['profession_id']);
                $faculty_profession = $getProfession['abbr'];
                $getSubject = $subject->getSubject($subject_id);
                $subject_code = $getSubject['code'];
                $subject_name = $getSubject['name'];
        ?>
            <div class="col-md-4 my-2">
                <div class="card">
                    <a href="subject_professor.php?faculty_id=<?= $faculty_id ?>&subject_id=<?= $subject_id ?>" id="card">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="card-subject-code"><?= $subject_code ?></div>
                                <div class="card-subject-title"><?= $subject_name ?></div>
                            </div>
                            <div class="card-text">
                            <?php
                                $getFacultySchedules = $schedule->getFacultySchedules($faculty_id, $subject_id);
                                foreach($getFacultySchedules as $getFacultySchedule) {
                                    $section = $getFacultySchedule["section"];
                                    $day = $getFacultySchedule["day"];
                                    $time_start = date("g:iA", strtotime($getFacultySchedule["time_start"]));
                                    $time_end = date("g:iA", strtotime($getFacultySchedule["time_end"]));
                                    $sched = $section."\t".$day."\t".$time_start."-".$time_end;
                            ?>
                                <div class="card-schedule text-italic"><?= $sched ?></div>
                            <?php
                                }
                            ?>
                                <br>
                                <div class="card-professor"><?= $faculty_profession.". ".$faculty_name ?></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        <?php
            }
        ?>
        </div>
    </div>
<?php endblock() ?>

<?php startblock("another_js") ?>
<?php endblock() ?>