<?php
    require_once 'database.php';
    require_once 'util.php';

    $db = new Database();
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data["username"];
        $pass = $data["pass"];
        echo($username);

        $query = $db->selectUserByUsername(array(':username' => $username));
        $row = $query["data"]->fetch(PDO::FETCH_ASSOC);
        
        if(!$row){
            echo json_encode("success");
            $token = generateRandomString(10);
            setcookie("token", $token, 0, "/");
            $query = $db->insertUser(array(':username' => $username, ':pass' => $pass, 'token' => $token));
        }else{
            echo json_encode("Username already taken!");
        } 
    }
?>