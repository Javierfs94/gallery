<?php

function AutoLoader($class)
{

    $class = strtolower($class);


    $path = "includes/{$class}.php";


    if (file_exists($path)) {
        require_once($path);
    } else {
        die("This file name {$class}.php was not man...");;
    }

    function redirect($location)
    {
        header("location: {$location}");
    }
}

spl_autoload_register('AutoLoader');
