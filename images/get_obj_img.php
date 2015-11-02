<?php
session_start();

if (isset($_GET['id'])) {

    if (file_exists(__DIR__ . "/../images/objects/" . basename($_GET['id']))) {
        readfile( __DIR__ . "/../images/objects/" . basename($_GET['id']));
        exit;
    }
}
header('Content: image/jpg');
readfile( __DIR__ . "/../images/objects/nia.jpg");