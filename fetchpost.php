<?php

require_once 'database.php';

$db = new Database();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET'){

   $id = $_GET['id'];

   $query = $db->selectLatestPosts(array(':id' => $id));
   $rows = $query["data"]->fetchAll(PDO::FETCH_ASSOC);

   echo json_encode($rows);
}
?>