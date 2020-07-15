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

    if (isset($_GET['faculty_id'])) {
        $faculty_id = $_GET['faculty_id'];

        $getFacultyInfo = $faculty->getFacultyInfo($faculty_id);
        $profession_id = $getFacultyInfo['profession_id'];
        $getProfession = $profession->getProfession($profession_id);
        $role_id = $getFacultyInfo['role_id'];
        $getRole = $role->getRole($role_id);

        $faculty_name = $getFacultyInfo['name'];
        $faculty_username = $getFacultyInfo['username'];
        $faculty_email = $getFacultyInfo['email'];
        $faculty_profession = $getProfession['name'];
        $faculty_role = $getRole['name'];

        $outputSchedules = "";
        $getFacultySchedules = $schedule->getFacultySchedules($faculty_id);
        if (count($getFacultySchedules) > 0) {
            foreach($getFacultySchedules as $getFacultySchedule) {
                $faculty_schedule_id = $getFacultySchedule['id'];
                $subject_code = $getFacultySchedule['subject_code'];
                $subject_name = $getFacultySchedule['subject_name'];
                $subject = $subject_code." - ".$subject_name;
                $section = $getFacultySchedule['section'];
                $day = $getFacultySchedule['day_name'];
                $time_start = date("g:iA", strtotime($getFacultySchedule['time_start']));
                $time_end = date("g:iA", strtotime($getFacultySchedule['time_end']));
                $time = $time_start." - ".$time_end;

                $subject_id = $getFacultySchedule['subject_id'];
                $section_id = $getFacultySchedule['section_id'];
                $day_id = $getFacultySchedule['day_id'];

                $buttons = '<div class="btn-group">';
                $buttons .= '<button class="btn btn-info btn-sm edit-faculty-schedule"
                            data-faculty_schedule_id="'.$faculty_schedule_id.'">'; 
                $buttons .= '<i class="fa fa-pencil" aria-hidden="true"></i></button>';
                $buttons .= '<button class="btn btn-warning btn-sm delete-faculty-schedule"
                            data-faculty_schedule_id="'.$faculty_schedule_id.'">';
                $buttons .= '<i class="fa fa-trash" aria-hidden="true"></i></button>';
                $buttons .= '</div>';
    
                $outputSchedules .= "<tr>";
                $outputSchedules .= "<td>";
                $outputSchedules .= $subject;
                $outputSchedules .= "</td>";
                $outputSchedules .= "<td>";
                $outputSchedules .= $section;
                $outputSchedules .= "</td>";
                $outputSchedules .= "<td>";
                $outputSchedules .= $day;
                $outputSchedules .= "</td>";
                $outputSchedules .= "<td>";
                $outputSchedules .= $time;
                $outputSchedules .= "</td>";
                $outputSchedules .= "<td>";
                $outputSchedules .= $buttons;
                $outputSchedules .= "</td>";
                $outputSchedules .= "</tr>";
            }
        } else {
            $outputSchedules .= "<tr><td colspan='5'>No data found</td></tr>";
        }
    } else {
        echo "<script>history.back();</script>";
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
            <div class="col-md-4 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr class="text-center">
                                <th colspan="2">PERSONAL INFORMATION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td><?= $faculty_name ?></td>
                            </tr>
                            <tr>
                                <th>Profession</th>
                                <td><?= $faculty_profession ?></td>
                            </tr>
                            <tr>
                                <th>Username</th>
                                <td><?= $faculty_username ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?= $faculty_email ?></td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td><?= $faculty_role ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="btn-group-horizontal float-right m-1" role="group" aria-label="horizontal button group">
                    <button class="btn btn-success" id="btn-update-account">Update</button>
                    <button class="btn btn-danger" id="btn-delete-account" data-faculty_id="<?= $faculty_id ?>">Delete Account</button>
                </div>
            </div>
            <div class="col-md-8 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th colspan="5">SCHEDULES <button class="btn btn-primary btn-sm float-right" id='add-faculty-schedule'>Add Another Schedule</button></th>
                            </tr>
                            <tr>
                                <th>SUBJECT</th>
                                <th>SECTION</th>
                                <th>DAY</th>
                                <th>TIME</th>
                                <th>&nbsp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?= $outputSchedules ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Faculty Modal -->
    <div class="modal" tabindex="-1" role="dialog" id="update-faculty-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form>
                    <input type="hidden" name="update-faculty_id" id="update-faculty_id" value="<?= $faculty_id ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Faculty</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#update-name">Name</label>
                            <input type="text" class="form-control" name='update-name' id="update-name" value="<?= $faculty_name ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="#update-profession">Profession</label>
                            <select name="update-profession" id="update-profession" class="form-control" required>
                                <option value="<?= $profession_id ?>" default selected><?= $faculty_profession ?></option>
                                <?php
                                    $getAllProfessions = $profession->getAllProfessions();
                                    foreach($getAllProfessions as $getAllProfession) {
                                        $id = $getAllProfession['id'];
                                        $name = $getAllProfession['name'];
                                        if ($id !== $profession_id) {
                                            echo "<option value='".$id."'>".$name."</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="#update-username">Username</label>
                            <input type="text" class="form-control" name='update-username' id="update-username" value="<?= $faculty_username ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="#update-email">Email</label>
                            <input type="text" class="form-control" name='update-email' id="update-email" value="<?= $faculty_email ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="#update-role">Role</label>
                            <select name="update-role" id="update-role" class="form-control" required>
                                <option value="<?= $role_id ?>" default selected><?= $faculty_role ?></option>
                                <?php
                                    $getAllRoles = $role->getAllRoles();
                                    foreach($getAllRoles as $getAllRole) {
                                        $id = $getAllRole['id'];
                                        $name = $getAllRole['name'];
                                        if ($id !== $role_id && $id != '4') {
                                            echo "<option value='".$id."'>".$name."</option>";
                                        }
                                    }
                                ?>
                            </select>
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

    <!-- Add Schedule Modal -->
    <div class="modal" tabindex="-1" role="dialog" id="add-schedule-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title">Add Schedule</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="add-faculty_id" id="add-faculty_id" value="<?= $faculty_id ?>">
                        <div class="form-group">
                            <label for="#add-course">Course</label>
                            <select name="add-course" id="add-course" class="form-control" required>
                                <option default selected disabled>Select Course</option>
                                <?php
                                    $getAllCourses = $course->getAllCourses();
                                    foreach($getAllCourses as $getAllCourse){
                                        echo "<option value='".$getAllCourse['id']."'>".$getAllCourse['name']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div id="modal-body-add-schedule"></div> 
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn-save-add-schedule" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Schedule Modal -->
    <div class="modal" tabindex="-1" role="dialog" id="edit-schedule-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Schedule</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="edit-faculty_id" id="edit-faculty_id" value="<?= $faculty_id ?>">
                        <div id="modal-body-edit-schedule"></div> 
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn-save-edit-schedule" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endblock() ?>

<?php startblock("another_js") ?>
    <script>
        $(document).ready(function() {
            // Update Faculty Account
            $("#btn-update-account").on("click", function() {
                $("#update-faculty-modal").modal("show");

                $("#btn-save-faculty").on("click", function() {
                    const faculty_id = $("#update-faculty_id").val();
                    const name = $("#update-name").val();
                    const profession = $("#update-profession").val();
                    const username = $("#update-username").val();
                    const email = $("#update-email").val();
                    const role = $("#update-role").val();
                    if (name !== "" && profession !== "" && username !== "" && email !== "" && role !== "") {
                        const data = { faculty_id, name, profession, username, email, role };
                        $.ajax({
                            method: "POST",
                            url: "../../ajax/process-faculty.ajax.php",
                            data: { queryUpdateFaculty : data },
                            success: function(data) {
                                if (data === "success") {
                                    Swal.fire({
                                        title: 'Updated',
                                        text: "The faculty account was updated successfully",
                                        icon: 'success',
                                        timer: 3000,
                                        showConfirmButton: false
                                    }).then(function() {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: "There was an error updating faculty account",
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

            // Add another schedule
            $("#add-faculty-schedule").on("click", function() {
                $("#add-schedule-modal").modal("show");

                $("#add-course").on("change", function() {
                    const course_id = $(this).val();
                    $.ajax({
                        method: "POST",
                        url: "../../ajax/process-faculty.ajax.php",
                        data: { queryAddScheduleByCourse : { course_id } },
                        success: function(data) {
                            $("#modal-body-add-schedule").html(data);
                        }
                    });
                })

                $("#btn-save-add-schedule").on("click", function() {
                    const faculty_id = $("#add-faculty_id").val();
                    const course_id = $("#add-course").val();
                    const subject_id = $("#add-subject").val();
                    const section_id = $("#add-section").val();
                    const day_id = $("#add-day").val();
                    const time_start = $("#add-time_start").val();
                    const time_end = $("#add-time_end").val();
                    // alert(course_id+"\n"+subject_id+"\n"+section_id+"\n"+day_id+"\n"+time_start+"\n"+time_end);
                    if (course_id !== "" && subject_id !== "" && section_id !== "" && day_id !== "" && time_start !== "" && time_end !== "") {
                        const data = { faculty_id, course_id, subject_id, section_id, day_id, time_start, time_end }
                        $.ajax({
                            method: "POST",
                            url: "../../ajax/process-faculty.ajax.php",
                            data: { queryAddSchedule : data },
                            success: function(data) {
                                if (data === "success") {
                                    Swal.fire({
                                        title: 'Added',
                                        text: "The schedule was added successfully",
                                        icon: 'success',
                                        timer: 3000,
                                        showConfirmButton: false
                                    }).then(function() {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: "There was an error inserting schedule",
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

            // Edit Schedule
            $(".edit-faculty-schedule").on("click", function() {
                $("#edit-schedule-modal").modal("show");
                const faculty_schedule_id = $(this).data("faculty_schedule_id");
                $.ajax({
                    method: "POST",
                    url: "../../ajax/process-faculty.ajax.php",
                    data: { queryEditSchedule : faculty_schedule_id },
                    success: function(data) {
                        $("#modal-body-edit-schedule").html(data);
                    }
                });

                $("#btn-save-edit-schedule").on("click", function() {
                    const faculty_schedule_id = $("#edit-faculty_schedule_id").val();
                    const faculty_id = $("#edit-faculty_id").val();
                    const course_id = $("#edit-course").val();
                    const subject_id = $("#edit-subject").val();
                    const section_id = $("#edit-section").val();
                    const day_id = $("#edit-day").val();
                    const time_start = $("#edit-time_start").val();
                    const time_end = $("#edit-time_end").val();
                    // alert(faculty_id+"\n"+course_id+"\n"+subject_id+"\n"+section_id+"\n"+day_id+"\n"+time_start+"\n"+time_end);
                    if (course_id !== "" && subject_id !== "" && section_id !== "" && day_id !== "" && time_start !== "" && time_end !== "") {
                        const data = { faculty_schedule_id, faculty_id, course_id, subject_id, section_id, day_id, time_start, time_end };
                        $.ajax({
                            method: "POST",
                            url: "../../ajax/process-faculty.ajax.php",
                            data: { querySaveEditSchedule : data },
                            success: function(data) {
                                if (data === "success") {
                                    Swal.fire({
                                        title: 'Updated',
                                        text: "The schedule was updated successfully",
                                        icon: 'success',
                                        timer: 3000,
                                        showConfirmButton: false
                                    }).then(function() {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: "There was an error updating schedule",
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

            // Delete Schedule
            $('.delete-faculty-schedule').on("click", function() {
                const faculty_schedule_id = $(this).data("faculty_schedule_id");
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to delete this schedule?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                })
                .then((result) => {
                    if (result.value) {
                        $.ajax({
                            method: "POST",
                            url: "../../ajax/process-faculty.ajax.php",
                            data: { queryDeleteSchedule: faculty_schedule_id },
                            success: function(data) {
                                if (data === "success") {
                                    Swal.fire({
                                        title: 'Deleted',
                                        text: "The schedule was successfully deleted",
                                        icon: 'success',
                                        timer: 3000,
                                        showConfirmButton: false
                                    }).then(function() {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: "There was an error deleting schedule",
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

            // Delete Account
            $('#btn-delete-account').on("click", function() {
                const faculty_id = $(this).data("faculty_id");
                Swal.fire({
                    title: faculty_id+'Are you sure?',
                    text: "Do you want to delete this account?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                })
                .then((result) => {
                    if (result.value) {
                        $.ajax({
                            method: "POST",
                            url: "../../ajax/process-faculty.ajax.php",
                            data: { queryDeleteFaculty: faculty_id },
                            success: function(data) {
                                if (data === "success") {
                                    Swal.fire({
                                        title: 'Deleted',
                                        text: "The faculty account was successfully deleted",
                                        icon: 'success',
                                        timer: 3000,
                                        showConfirmButton: false
                                    }).then(function() {
                                        location.href = "faculty_dean_chairperson.php";
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: "There was an error deleting faculty account",
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