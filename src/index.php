<?php
    switch (@parse_url($_SERVER['REQUEST_URI'])['path']) {
        case '/home.php':
            require 'home.php';
            break;
        case '/query.php':
            require 'query.php';
            break;
        default:
            require 'home.php';
    }
?>