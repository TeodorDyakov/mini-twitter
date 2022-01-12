<?php
    require_once 'database.php';

    $db = new Database();
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $query = $db->selectAllPosts();
        $rows = $query["data"]->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($rows);
    }

?>