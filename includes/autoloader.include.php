<?php

    spl_autoload_register('myAutoload');

    function myAutoload($className){
        $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        if (strpos($url, 'includes') !== false || strpos($url, 'ajax') !== false || strpos($url, 'pdf') !== false) {
            $path = "../classes/";
        } else if (strpos($url, 'pages') !== false) { 
            $path = "../../classes/";
        } else {
            $path = "classes/";
        }
        
        $extension = ".class.php";
        $fullPath = $path.$className.$extension;

        if (!file_exists($fullPath)) {
            return false;
        }
        require_once $fullPath;
    }