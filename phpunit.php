<?php

if(file_exists("../../autoload.php")) {
    require "../../autoload.php";
} else {
        if (file_exists("vendor/autoload.php")) {
        require_once "vendor/autload.php";
    }

    spl_autoload_register(function($class){
        $path = str_replace("elise\\events\\","src".DIRECTORY_SEPARATOR,$class).".php";
        if(file_exists($path)){
            require_once $path;
        }
    });
}
