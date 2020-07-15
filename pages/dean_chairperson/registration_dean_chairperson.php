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
?>

<?php startblock("another_css") ?>
<style>
</style>
<?php endblock() ?>

<?php startblock("main_content") ?>
    <div class="container-fluid p-3">
        <h2 class="text-center my-2">REGISTRATION</h2>
        <div class="row px-3 py-2">
            <div class="offset-md-1 col-md-10 col-sm-12 border p-3">
                <form action="">
                    <div id="basic-information">
                        <h5 class="text-center py-2 border bg-dark text-white">BASIC INFORMATION</h5>
                        <div class="row">
                            <div class="col-12 text-center" id="basic-information-error"></div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="#student_number">Student Number</label>
                                    <input type="text" name="student_number" id="student_number" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="#password">Password</label>
                                    <input type="password" name="password" id="password" required class="form-control">
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <div class="form-group">
                                    <label for="#fullname">Full Name</label>
                                    <input type="text" name="fullname" id="fullname" required class="form-control">
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
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="#course">Course</label>
                                    <select name="course" id="course" class="form-control" required>
                                        <option default selected disabled>Select Course</option>
                                        <?php
                                            $getAllCourses = $course->getAllCourses();
                                            foreach($getAllCourses as $getAllCourse){
                                                echo "<option value='".$getAllCourse['id']."'>".$getAllCourse['name']."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="#year">Year</label>
                                    <select name="year" id="year" class="form-control" required>
                                        <option default selected disabled>Select Year</option>
                                        <?php
                                            $getAllYears = $year->getAllYears();
                                            foreach($getAllYears as $getAllYear){
                                                echo "<option value='".$getAllYear['id']."'>".$getAllYear['name']."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="#section">Section</label>
                                    <select class="form-control select-section" name="section" id="section">
                                        <option value="" default selected disabled>Select Section</option>
                                    </select>
                                </div>        
                            </div>
                            <div class="col-12">
                                <div class="btn-group float-right">
                                    <button type="button" id="btn-next-basic-information" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="guardian-information" style="display: none">
                        <h5 class="text-center py-2 border bg-dark text-white">GUARDIAN INFORMATION</h5>
                        <div class="row">
                            <div class="col-12 text-center" id="guardian-information-error"></div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="#guardian_name">Full Name*</label>
                                    <input type="text" name="guardian_name" id="guardian_name" required class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="#guardian_number">Contact Number*</label>
                                    <input type="text" name="guardian_number" id="guardian_number" required class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="#guardian_relationship">Relationship*</label>
                                    <input type="text" name="guardian_relationship" id="guardian_relationship" required class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="#guardian_name2">Full Name</label>
                                    <input type="text" name="guardian_name2" id="guardian_name2" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="#guardian_number2">Contact Number</label>
                                    <input type="text" name="guardian_number2" id="guardian_number2" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="#guardian_relationship2">Relationship</label>
                                    <input type="text" name="guardian_relationship2" id="guardian_relationship2" class="form-control">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="btn-group float-right">
                                    <button type="button" id="btn-back-guardian-information" class="btn btn-info m-1">Back</button>
                                    <button type="button" id="btn-next-guardian-information" class="btn btn-primary m-1">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="subject-information" style="display: none">
                        <h5 class="text-center py-2 border bg-dark text-white">SUBJECTS</h5>
                        <div class="row">
                            <div class="col-12 text-center" id="subject-information-error"></div>
                            <div class="offset-md-1 col-md-10 col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th>&nbsp</th>
                                                <th>Subject</th>
                                                <th>Section</th>
                                            </tr>
                                        </thead>
                                        <tbody class="select-subject"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="btn-group float-right">
                                    <button type="button" id="btn-back-student-information" class="btn btn-info m-1">Back</button>
                                    <button type="button" id="btn-save-student-information" class="btn btn-primary m-1">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endblock() ?>

<?php startblock("another_js") ?>
    <script>
        $(document).ready(function() {
            $('#course').on("change", function() {
                const course_id = $(this).val();
                $.ajax({
                    method: "POST",
                    url: "../../ajax/process-registration.ajax.php",
                    data: { queryRegisterSection : course_id },
                    success: function(data) {
                        $(".select-section").html(data);
                    }
                })
                $.ajax({
                    method: "POST",
                    url: "../../ajax/process-registration.ajax.php",
                    data: { queryRegisterSubject : course_id },
                    success: function(data) {
                        $(".select-subject").html(data);
                    }
                })
            });

            const course_id = document.querySelector("#course");
            const student_number = document.querySelector("#student_number");
            const password = document.querySelector("#password");
            const fullname = document.querySelector("#fullname");
            const email = document.querySelector("#email");
            const course = document.querySelector("#course");
            const year = document.querySelector("#year");
            const section = document.querySelector("#section");
            const guardian_name = document.querySelector("#guardian_name");
            const guardian_number = document.querySelector("#guardian_number");
            const guardian_relationship = document.querySelector("#guardian_relationship");
            const guardian_name2 = document.querySelector("#guardian_name2");
            const guardian_number2 = document.querySelector("#guardian_number2");
            const guardian_relationship2 = document.querySelector("#guardian_relationship2");

            // Basic Information [Next]
            $("#btn-next-basic-information").on("click", function() {
                if (student_number.value !== "" && password.value !== "" && fullname.value !== "" && email.value !== "" && course.value !== "" && year.value !== "" && section.value !== "") {
                    // alert(student_number.value+"\n"+password.value+"\n"+fullname.value+"\n"+email.value+"\n"+course.value+"\n"+year.value+"\n"+section.value)
                    $("#basic-information").slideUp(1000);
                    $("#guardian-information").slideDown();
                } else {
                    $("#basic-information-error").addClass("alert alert-danger");
                    $("#basic-information-error").html("All fields are required");
                    setTimeout(() => {
                        $("#basic-information-error").removeClass("alert alert-danger");
                        $("#basic-information-error").html("");
                    }, 3000);
                }
            });
            $("#btn-back-guardian-information").on("click", function() {
                $("#guardian-information").slideUp(1000);
                $("#basic-information").slideDown();
            });
            $("#btn-next-guardian-information").on("click", function() {
                if (guardian_name.value !== "" && guardian_number.value !== "" && guardian_relationship.value !== "") {
                    // alert(student_number.value+"\n"+password.value+"\n"+fullname.value+"\n"+email.value+"\n"+course.value+"\n"+year.value+"\n"+section.value)
                    // alert(guardian_name.value+"\n"+guardian_number.value+"\n"+guardian_relationship.value+"\n"+guardian_name2.value+"\n"+guardian_number2.value+"\n"+guardian_relationship2.value)
                    $("#guardian-information").slideUp(1000);
                    $("#subject-information").slideDown();
                } else {
                    $("#guardian-information-error").addClass("alert alert-danger");
                    $("#guardian-information-error").html("Please fill up required fields");
                    setTimeout(() => {
                        $("#guardian-information-error").removeClass("alert alert-danger");
                        $("#guardian-information-error").html("");
                    }, 3000);
                }
            });
            $("#btn-back-student-information").on("click", function() {
                $("#subject-information").slideUp(1000);
                $("#guardian-information").slideDown();
            });
            $('#btn-save-student-information').on("click", function() {
                var checkboxes = document.querySelectorAll("input[name=subject_checkbox]:checked");
                var values = [];
                var subjectIds = [];
                var sectionIds = [];
                var sectionLength = 0;
                checkboxes.forEach(checkbox => {
                    values.push(checkbox.value);
                });
                for (var i=0; i<values.length; i++) {
                    const subject_id = $("#subject-id"+values[i]).val();
                    const section_id = $("#subject-section"+values[i]).val();
                    if (section_id !== null) {
                        subjectIds.push(subject_id);
                        sectionIds.push(section_id);
                        sectionLength++;
                    } else {
                        $("#subject-information-error").addClass("alert alert-danger");
                        $("#subject-information-error").html("Please select section for chosen subjects");
                        setTimeout(() => {
                            $("#subject-information-error").removeClass("alert alert-danger");
                            $("#subject-information-error").html("");
                        }, 3000);
                        sectionLength = 0;
                        subjects = []
                        return;
                    }
                }

                // Checking if have subjects
                if (sectionLength === values.length && values.length > 0) {
                    // Save Basic Information
                    const basic_information_data = {
                        student_number: student_number.value,
                        fullname: fullname.value,
                        password: password.value,
                        email: email.value,
                        course: course.value,
                        year: year.value,
                        section: section.value
                    };
                    const guardian_information_data = {
                        guardian_name: guardian_name.value,
                        guardian_number: guardian_number.value,
                        guardian_relationship: guardian_relationship.value,
                        guardian_name2: guardian_name2.value,
                        guardian_number2: guardian_number2.value,
                        guardian_relationship2: guardian_relationship2.value,
                    }

                    $.ajax({
                        method: "POST",
                        url: "../../ajax/process-registration.ajax.php",
                        data: { querySaveBasicInformation : basic_information_data },
                        success: function(data) {
                            if (data === "success") {
                                $.ajax({
                                    method: "POST",
                                    url: "../../ajax/process-registration.ajax.php",
                                    data: { querySaveGuardianInformation : guardian_information_data },
                                    success: function(data) {
                                        if (data === "success") {
                                            for (var i=0; i<values.length; i++) {
                                                const data = { 
                                                    course_id : course_id.value, 
                                                    subject_id : subjectIds[i], 
                                                    section_id : sectionIds[i] 
                                                };
                                                $.ajax({
                                                    method: "POST",
                                                    url: "../../ajax/process-registration.ajax.php",
                                                    data: { querySaveSubject: data},
                                                })
                                            }
                                            // Assume that student information save successfully
                                            setTimeout(() => {
                                                Swal.fire({
                                                    title: 'Success',
                                                    text: "Student was saved successfully",
                                                    icon: 'success',
                                                    timer: 3000,
                                                    showConfirmButton: false
                                                }).then(function() {
                                                    window.location.reload();
                                                });
                                            }, 2000);
                                        } else {
                                            Swal.fire({
                                                title: 'Error',
                                                text: "There was an error saving student guardian information",
                                                icon: 'error',
                                                timer: 3000,
                                                showConfirmButton: false
                                            });
                                        }
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: "There was an error saving student basic information",
                                    icon: 'error',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
<?php endblock() ?>