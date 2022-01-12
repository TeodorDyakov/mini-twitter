<?php
    require_once 'database.php';
    require 'util.php';
    $db = new Database();
    // header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = json_decode(file_get_contents('php://input'), true);
        $title = $data["title"];
        $content = $data["content"];
        $img = $data["img"];

        $name = generateRandomString(20).".jpeg";
        $file = base64_to_jpeg($img,$name); 

        if(isset($_COOKIE["token"])) {
            $token = $_COOKIE["token"];
            
            $query = $db->selectUsernameByToken(array(':token' => $token));
            $row = $query["data"]->fetch(PDO::FETCH_ASSOC);
            

            $data = explode( ',', $img );
            $base64 = $data[1];
            sendImgJSONtoMLserver($base64, $name);

            if($row){
                $username = $row["username"];
                $query = $db->insertPost(array(':username' => $username, ':title' => $title, ':content' => $content,
                ':date' => date("Y-m-d H:i:s"), ':img' => $name));

                if(!$query["success"]){
                    echo $query["errors"];
                }
            }
        }else{
            http_response_code(409);
        }
    }

    function sendImgJSONtoMLserver($base64, $name){
        
        $service_port = 12345;

        $address = "172.24.126.137";

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) {
            echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
        } else {
            echo "OK.\n";
        }

        echo "Attempting to connect to '$address' on port '$service_port'...";
        $result = socket_connect($socket, $address, $service_port);
        if ($result === false) {
            echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
        } else {
            echo "OK.\n";
        }

        $in = json_encode(array("Base64" => $base64, "Name" => $name));

        socket_write($socket, $in, strlen($in));

        socket_close($socket);
    }

    function base64_to_jpeg($base64_string, $output_file) {
        // open the output file for writing
        $ifp = fopen( $output_file, 'wb' ); 
        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode( ',', $base64_string );
        // we could add validation here with ensuring count( $data ) > 1
        fwrite( $ifp, base64_decode( $data[ 1 ] ) );
    
        // clean up the file resource
        fclose( $ifp ); 
    
        return $output_file; 
    }
?>