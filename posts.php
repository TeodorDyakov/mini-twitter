<?php

require_once 'database.php';

$db = new Database();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET'){

   $id = $_GET['id'];

   if(isset($_COOKIE["token"])) {
      $token = $_COOKIE["token"];
      
      $query = $db->selectUsernameByToken(array(':token' => $token));
      $row = $query["data"]->fetch(PDO::FETCH_ASSOC);
      $username = $row["username"];
      if($username){
         $query = $db->selectLatestPosts(array(':id' => $id, "username" => $username));
         $rows = $query["data"]->fetchAll(PDO::FETCH_ASSOC);
         
         echo json_encode($rows);   

      }else{
         echo json_encode("Unathourized");
         http_response_code(409);
      }
   }
}
?>