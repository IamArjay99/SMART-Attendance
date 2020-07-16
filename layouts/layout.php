<?php
    // Assume that this layout is inside the child or the inherited file
    include "../../plugins/phpti-master/ti.php";

    // Include all class
    include "../../includes/all.include.php";

    $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

    date_default_timezone_set('Asia/Manila');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMART Attendance</title>
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <!-- Bootstrap JS CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <!-- Datatables CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    <!-- Sweet Alert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../dist/css/styles.min.css">

    <?php emptyblock("another_css") ?>

    <script>
        function toggleMenu() {
            const menu = document.getElementById("menu");
            const sidebar = document.getElementById("sidebar");
            const mainContent = document.getElementById("main-content");
            menu.classList.toggle("min");
            sidebar.classList.toggle("min");
            mainContent.classList.toggle("min");
        }
    </script>
</head>
<body>
    <div id="wrapper">
        <header id="navbar">
            <div class="header-color">
                <div class="blue"></div>
                <div class="gold">
                    <i
                        class="fa fa-bars"
                        aria-hidden="true"
                        onclick="toggleMenu()"
                        id="menu"
                    ></i>
                </div>
            </div>
            <div class="header-img">
                <img src="../../dist/img/logo.png" alt="" class="logo" />
                <img
                    src="../../dist/img/header.png"
                    alt=""
                    class="header"
                />
            </div>
        </header>
        <aside class="sidebar" id="sidebar">
            <?php if ($_SESSION['data']['role_id'] === "1" || $_SESSION['data']['role_id'] === "2") { ?>
            <ul>
                <li class="<?= strpos($url, "dashboard_dean_chairperson") ? "active" : "" ?>">
                    <a href="dashboard_dean_chairperson.php">Dashboard</a>
                </li>
                <li class="<?= strpos($url, "faculty_dean_chairperson") ? "active" : "" ?>">
                    <a href="faculty_dean_chairperson.php">Faculty</a>
                </li>
                <li class="<?= strpos($url, "registration_dean_chairperson") ? "active" : "" ?>">
                    <a href="registration_dean_chairperson.php">Registration</a>
                </li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
            <?php } ?>
        </aside>
        <div class="main-content" id="main-content">
            <?php emptyblock("main_content") ?>
        </div>
    </div>
    <?php emptyblock("another_js") ?>
</body>
</html>