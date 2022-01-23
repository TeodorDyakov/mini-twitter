<?php
    require_once 'database.php';
    require_once 'util.php';

    $db = new Database();
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $data = json_decode(file_get_contents('php://input'), true);
        $username = null;

        if(isset($_GET['username'])){
            $username = $_GET['username'];
        }

        if(!$username && isset($_COOKIE["token"])) {
            $token = $_COOKIE["token"];
            $username = $db->selectUsernameByToken(array(':token' => $token))["data"]->fetch()["username"];
        }
                
        $query = $db->selectUserByUsername(array(':username' => $username));
        $row = $query["data"]->fetch(PDO::FETCH_ASSOC);
        echo json_encode($row);
    }
?>