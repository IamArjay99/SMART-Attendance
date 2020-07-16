<?php 
    session_start();

    include 'includes/autoloader.include.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>SMART Attendance</title>
        <link rel="shortcut icon" href="dist/img/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="./dist/css/styles.min.css" />
    </head>
    <body>
        <div class="wrapper">
            <header>
                <div class="blue"></div>
                <div class="gold"></div>
            </header>
            <main>
                <div class="form">
                    <div class="form-header">
                        <img src="./dist/img/logo.png" alt="" class="level1" />
                        <img
                            src="./dist/img/header.png"
                            alt=""
                            class="level2"
                        />
                        <small>Developed by Elyano TechQuad</small>
                    </div>
                    <div class="form-body">
                        <form action="#" method="POST">
                            <div class="feedback"></div>
                            <div class="form-group">
                                <label for="email">Email/ Username</label>
                                <input type="text" name="email" id="email" required autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    required autocomplete="off"
                                />
                            </div>
                            <button type="submit" name="login" id="login">
                                Log in
                            </button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
        
        <!-- jQuery CDN -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
               const btnSignin = document.querySelector("#login");
               const feedback = document.querySelector(".feedback");
               const email = document.querySelector("#email");
               const password = document.querySelector("#password");
               btnSignin.addEventListener("click", function(e) {
                e.preventDefault();
                if (email.value === "" || password.value === "") {
                    feedback.classList.add("error-feedback");
                    feedback.innerHTML = "Email/Username and Password is required";
                    setTimeout(() => {
                        feedback.classList.remove("error-feedback");
                        feedback.innerHTML = "";
                    }, 3000);
                } else {
                    const data = { email: email.value, password: password.value };
                    $.ajax({
                        method: "POST",
                        url: "ajax/process-signin.ajax.php",
                        data: { query: data },
                        success: function(data) {
                            if (data !== "false") {
                                window.location = data;
                            } else {
                                feedback.classList.add("error-feedback");
                                feedback.innerHTML = "Incorrect Email/ Username or Password";
                                email.focus();
                                password.value = "";
                                setTimeout(() => {
                                    feedback.classList.remove("error-feedback");
                                    feedback.innerHTML = "";
                                }, 3000);
                            }
                        }
                    })
                }
               }) 
            });
        </script>
    </body>
</html>
