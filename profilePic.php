    <?php
    require_once 'database.php';
    require_once 'util.php';

    $db = new Database();
    

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = json_decode(file_get_contents('php://input'), true);
   
        if(isset($_COOKIE["token"])) {
            $token = $_COOKIE["token"];
            $query = $db->selectUsernameByToken(array(':token' => $token));
            $row = $query["data"]->fetch(PDO::FETCH_ASSOC);
            if($row){
                $username = $row["username"];
                $imgURL = $data["imgURL"];
                echo $imgURL;
                $db->updateUserImgURL(array(':imgURL' => $imgURL, ':username' => $username));
            } 
        }
    }
?>