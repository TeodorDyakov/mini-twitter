<?php
     require_once 'database.php';
     require_once 'auth.php';

     $db = new Database();
     header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $data = json_decode(file_get_contents('php://input'), true);
        $postId = $data["postId"];

        $auth_res = authorize($db);
        
        if($auth_res["success"]){
            $username = $auth_res["username"];
            
            $query = $db->likePost(array(':username' => $username, ':post_id' =>  $postId));
            
            if($query["success"]){
                $query = $db->incrementLikes(array(':id'=>$postId));
            }

            $likes = $db->selectLikes(array(':id' => $postId))["data"]->fetch();
            
            echo json_encode($likes);
        }else{
            echo json_encode("Unauthorized");
            http_response_code(409);
        }
    }

?>