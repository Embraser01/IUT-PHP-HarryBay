<?php
if (isset($_GET['id'])) {

    if (file_exists(__DIR__ . "/objects/" . basename($_GET['id']) . '.jpg')) {
        header('Content: image/jpg');
        readfile( __DIR__ . "/objects/" . basename($_GET['id']) . '.jpg');
        exit;
    }
}
header('Content: image/jpg');
readfile( __DIR__ . "/objects/nia.jpg");