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
        $outputFaculties .= "<td>";
        $outputFaculties .= '<a href="details_dean_chairperson.php?faculty_id='.$faculty_id.'" class="btn btn-primary">Details</a>';
        $outputFaculties .= "</td>";
        $outputFaculties .= "</tr>";
    }
?>

<?php startblock("another_css") ?>
<style>
</style>
<?php endblock() ?>

<?php startblock("main_content") ?>
    <div class="container-fluid p-3">
        <h2 class="text-center my-2">FACULTY</h2>
        <div class="row">
            <div class="offset-md-1 col-md-10 col-sm-12">
                <div class="btn-group">
                    <button class="btn btn-success m-1" id="add-faculty">Add Faculty</button>
                    <button class="btn btn-secondary m-1" id="add-subject">Add Subject</button>
                    <button class="btn btn-warning m-1" id="add-course">Add Course</button>
                    <button class="btn btn-info m-1" id="add-section">Add Section</button>
                </div>
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
                                <th>&nbsp</th>
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

    <!-- Add Faulcty Modal -->
    <div class="modal" tabindex="-1" role="dialog" id="add-faculty-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title">New Faculty</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#name">Name</label>
                            <input type="text" class="form-control" name='name' id="name" required>
                        </div>
                        <div class="form-group">
                            <label for="#username">Username</label>
                            <input type="text" class="form-control" name='username' id="username" required>
                        </div>
                        <div class="form-group">
                            <label for="#email">Email</label>
				        	<div class="input-group">
					        	<input type="text" id="email" name="email" class="form-control" pattern="[A-Za-z]{3,}[.]{1}[A-Za-z]{2,}" placeholder="firstname.lastname" title="firstname.lastname" required>
					        	<div class="input-group-append">
								    <span class="input-group-text" id="basic-addon2">@my.jru.edu</span>
							    </div>
						    </div>
                        </div>
                        <div class="form-group">
				        	<label for="#profession">Profession</label>
                            <select name="profession" id="profession" class='form-control'>
                                <option value="" default selected disabled>Select Profession</option>
                                <?php
                                    $getAllProfessions = $profession->getAllProfessions();
                                    foreach($getAllProfessions as $getAllProfession) {
                                        echo "<option value='".$getAllProfession['id']."' >".$getAllProfession['name']."</option>";
                                    }
                                ?>
                            </select>
				        </div>
				        <div class="form-group">
				        	<label for="#password">Password</label>
				        	<input type="password" name="password" id="password" class="form-control" required>
				        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn-save-faculty" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Subject Modal -->
    <div class="modal" tabindex="-1" role="dialog" id="add-subject-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title">New Subject</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#add-subject_course">Course</label>
                            <select name="add-subject_course" id="add-subject_course" class="form-control" required>
                                <option default selected disabled>Select Course</option>
                                <?php
                                    $getAllCourses = $course->getAllCourses();
                                    foreach($getAllCourses as $getAllCourse) {
                                        echo "<option value='".$getAllCourse['id']."'>".$getAllCourse['name']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="#subject_code">Subject Code</label>
                            <input type="text" class="form-control" name='subject_code' id="subject_code" required>
                        </div>
                        <div class="form-group">
                            <label for="#subject_name">Subject Name</label>
                            <input type="text" class="form-control" name='subject_name' id="subject_name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn-save-subject" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Section Modal -->
    <div class="modal" tabindex="-1" role="dialog" id="add-section-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title">New Section</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#add-section_course">Course</label>
                            <select name="add-section_course" id="add-section_course" class="form-control" required>
                                <option default selected disabled>Select Course</option>
                                <?php
                                    $getAllCourses = $course->getAllCourses();
                                    foreach($getAllCourses as $getAllCourse) {
                                        echo "<option value='".$getAllCourse['id']."'>".$getAllCourse['name']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="#add-section_name">Section Name</label>
                            <input type="text" class="form-control" name='add-section_name' id="add-section_name" placeholder="e.g. 101G" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn-save-section" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Course Modal -->
    <div class="modal" tabindex="-1" role="dialog" id="add-course-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title">New Course</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#add-course-name">Course Name</label>
                            <input type="text" class="form-control" name='add-course_name' id="add-course_name" required>
                        </div>
                        <div class="form-group">
                            <label for="#add-course-abbr">Course Abbreviation</label>
                            <input type="text" class="form-control" name='add-course_abbr' id="add-course_abbr" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn-save-course" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endblock() ?>

<?php startblock("another_js") ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Add faculty
            $("#add-faculty").on("click", function() {
                $("#add-faculty-modal").modal("show");
                $("#btn-save-faculty").on("click", function(e) {
                    e.preventDefault();
                    const name = $("#name").val();
                    const username = $("#username").val();
                    const email = $("#email").val();
                    const profession = $("#profession").val();
                    const password = $("#password").val();
                    $.ajax({
                        method: "POST",
                        url: "../../ajax/process-faculty.ajax.php",
                        data: { queryAddFaculty: { name, username, email, profession, password } },
                        success: function(data) {
                            if (data === "success") {
                                Swal.fire({
                                    title: 'Success',
                                    text: "The faculty saved successfully",
                                    icon: 'success',
                                    timer: 3000,
                                    showConfirmButton: false
                                }).then(function() {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: "There was an error saving faculty",
                                    icon: 'error',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            }
                        }
                    })
                })
            });
            // Add subject
            $("#add-subject").on("click", function() {
                $("#add-subject-modal").modal("show");
                $("#btn-save-subject").on("click", function(e) {
                    e.preventDefault();
                    const subject_name = $("#subject_name").val();
                    const subject_code = $("#subject_code").val();
                    const subject_course = $("#add-subject_course").val();
                    $.ajax({
                        method: "POST",
                        url: "../../ajax/process-faculty.ajax.php",
                        data: { queryAddSubject: { subject_course, subject_code, subject_name } },
                        success: function(data) {
                            if (data === "success") {
                                Swal.fire({
                                    title: 'Success',
                                    text: "The subject saved successfully",
                                    icon: 'success',
                                    timer: 3000,
                                    showConfirmButton: false
                                }).then(function() {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: "There was an error saving subject",
                                    icon: 'error',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            }
                        }
                    })
                })
            });
            // Add Course
            $("#add-course").on("click", function() {
                $("#add-course-modal").modal("show");

                $("#btn-save-course").on("click", function() {
                    const course_name = $("#add-course_name").val();
                    const course_abbr = $("#add-course_abbr").val();
                    if (course_name !== "" && course_abbr !== "") {
                        $.ajax({
                            method: "POST",
                            url: "../../ajax/process-faculty.ajax.php",
                            data: { queryAddCourse : { course_name, course_abbr } },
                            success: function(data) {
                                if (data === "success") {
                                    Swal.fire({
                                        title: 'Success',
                                        text: "The course saved successfully",
                                        icon: 'success',
                                        timer: 3000,
                                        showConfirmButton: false
                                    }).then(function() {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: "There was an error saving the course",
                                        icon: 'error',
                                        timer: 3000,
                                        showConfirmButton: false
                                    });
                                }
                            }
                        })
                    } 
                })
            })
            // Add Section
            $('#add-section').on("click", function() {
                $("#add-section-modal").modal("show");
                $("#btn-save-section").on("click", function() {
                    const course_id = $("#add-section_course").val();
                    const section_name = $("#add-section_name").val();
                    if (course_id !== "" && section_name !== "") {
                        $.ajax({
                            method: "POST",
                            url: "../../ajax/process-faculty.ajax.php",
                            data: { queryAddSection : { course_id, section_name } },
                            success: function(data) {
                                if (data === "success") {
                                    Swal.fire({
                                        title: 'Success',
                                        text: "The section saved successfully",
                                        icon: 'success',
                                        timer: 3000,
                                        showConfirmButton: false
                                    }).then(function() {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: "There was an error saving the section",
                                        icon: 'error',
                                        timer: 3000,
                                        showConfirmButton: false
                                    });
                                }
                            }
                        })
                    }
                })
            })
        })
    </script>
<?php endblock() ?>