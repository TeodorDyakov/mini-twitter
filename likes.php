<?php
     require_once 'database.php';

     $db = new Database();
     header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $data = json_decode(file_get_contents('php://input'), true);
        $postId = $data["postId"];
        
        if(isset($_COOKIE["token"])) {
            $token = $_COOKIE["token"];
            $username = $db->selectUsernameByToken(array(':token' => $token))["data"]->fetch()["username"];
            $query = $db->likePost(array(':username' => $username, ':post_id' =>  $postId));
            if($query["success"]){
                $query = $db->incrementLikes(array(':id'=>$postId));
            }
            $likes = $db->selectLikes(array(':id' => $postId))["data"]->fetch();
            echo json_encode($likes);
        }else{
            echo json_encode("you are not logged in!");
        }
    }

?>