<?php

require_once 'database.php';
require_once 'auth.php';

$db = new Database();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET'){

   $gt = $_GET['gt'];

   $auth_res = authorize($db);
   
   if($auth_res["success"]){
      $username = $auth_res["username"];

      $query = $db->selectLatestPosts(array(':id' => $gt, "username" => $username));
      $rows = $query["data"]->fetchAll(PDO::FETCH_ASSOC);
         
      echo json_encode($rows);   
   }else{
      echo json_encode("Unathourized");
      http_response_code(409);
   }
}
?>