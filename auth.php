<?php

    function authorize($db){
        if(isset($_COOKIE["token"])) {
            $token = $_COOKIE["token"];
            if(!$token){
                return array("success" => false, "username" => null);
            }
            $query = $db->selectUsernameByToken(array(':token' => $token));
            $row = $query["data"]->fetch(PDO::FETCH_ASSOC);
            
            $name = $row["username"];
            $res = array("success" => true, "username" => $name);
    
            if(!$name){
                $res["success"] = false;
            }
            return $res;
        }
    }
?>