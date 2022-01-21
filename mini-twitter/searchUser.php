<?php
    /*
    -Teodor
    tedy
    -teodora
    pesho
    ?searchQuery=teo
    */
    require 'database.php';

    $db = new Database();

    $searchQuery = $_GET['searchQuery'];
    $username = $_GET['username'];

    $query = $db->searchUser(array(':query' => $searchQuery."%"));

    $rows = $query["data"]->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as &$value) {
        $isFollowed = $db->isFollowed(array(
            ':followerUsername' =>$username,
             ":followingUsername" => $value["username"]))
             ["data"]->fetch(PDO::FETCH_ASSOC) ? true : false;
        $value["isFollowed"] = $isFollowed;
    }
    echo (json_encode($rows));
?>