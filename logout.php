<?php
    require_once 'database.php';

    $db = new Database();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_COOKIE["token"])) {
            $token = $_COOKIE["token"];
            $db->setTokenNull(array(':token' => $token));
        }
    }

?>