<?php
    require 'database.php';
    $db = new Database();

    if ($_SERVER['REQUEST_METHOD'] === 'GET'){
        
    }else if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $data = json_decode(file_get_contents('php://input'), true);

        $follower = $data['follower'];
        $following = $data['following'];
        $follow = $data['follow'];

        if($follow == true){
            $query = $db->followUser(array(
                ':followerUsername' => $follower,
                ':followingUsername' => $following
            ));
            echo $query[$follower." followed ".$following];
        }else{
            $query = $db->unfollowUser(array(
                ':followerUsername' => $follower,
                ':followingUsername' => $following
            ));
            echo $follower." unfollowed ".$following;
        }
    
        
    }
?>