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
        
        if($row && $username == $row["username"] && $row["pass"] == $pass){
            echo json_encode("success");
            $token = generateRandomString(10);
            
            setcookie("token", $token, 0, "/");
            $query = $db->updateUserTokenByUsername(array(':username' => $username, ':token' => $token));
        }
        else{
            echo json_encode("You are not logged in");
            http_response_code(409);
        }
    }

?>