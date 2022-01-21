<?php
class Database
{
  private $connection;
  private $select_username_by_token;
  private $update_user_token;
  private $select_user_by_username;
  private $insert_user;
  private $select_latest_posts;
  private $update_user_img_URL;
  private $increment_likes;
  private $like_post;
  private $select_like;
  private $set_token_null;
  private $search_user;
  private $follow_user;
  private $is_followed;

  public function __construct()
  {
    $config = parse_ini_file('config/config.ini', true);

    $type = $config['db']['type'];
    $host = $config['db']['host'];
    $name = $config['db']['name'];
    $user = $config['db']['user'];
    $password = $config['db']['password'];

    $this->init($type, $host, $name, $user, $password);
  }

  private function init($type, $host, $name, $user, $password)
  {
    try {
      $this->connection = new PDO(
        "$type:host=$host;dbname=$name",
        $user,
        $password,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
      );

      $this->prepareStatements();
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  }

  private function prepareStatements()
  {
    $sql = "SELECT * FROM user WHERE username = :username";
    $this->select_user_by_username = $this->connection->prepare($sql);

    $sql = "UPDATE user SET token = NULL WHERE token = :token";
    $this->set_token_null = $this->connection->prepare($sql);

    $sql = "INSERT INTO likes (username, post_id) VALUES (:username, :post_id)";
    $this->like_post = $this->connection->prepare($sql);

    $sql = "UPDATE post SET likes = likes+1 WHERE id = :id";
    $this->increment_likes = $this->connection->prepare($sql);

    $sql = "UPDATE user SET imgURL = :imgURL WHERE username = :username";
    $this->update_user_img_URL = $this->connection->prepare($sql);

    $sql = "UPDATE user SET token = :token WHERE username = :username";
    $this->update_user_token = $this->connection->prepare($sql);

    $sql = "INSERT INTO user (username, pass, token) VALUES (:username, :pass, :token)";
    $this->insert_user = $this->connection->prepare($sql);
    
    $sql = "SELECT username FROM user WHERE token = :token";
    $this->select_username_by_token = $this->connection->prepare($sql);

    $sql = "INSERT into post (title, content, date, username, img) VALUES(:title, :content, :date, :username, :img)";
    $this->insert_post = $this->connection->prepare($sql);

    $sql = "SELECT * FROM post P LEFT JOIN followers F ON P.username = F.following WHERE F.follower = :username AND P.id > :id";
    $this->select_latest_posts = $this->connection->prepare($sql);

    $sql = "SELECT likes FROM post WHERE id=:id";
    $this->select_like = $this->connection->prepare($sql);

    $sql = "SELECT username FROM user WHERE username LIKE :query";
    $this->search_user = $this->connection->prepare($sql);

    $sql = "INSERT INTO followers (follower, following) VALUES (:followerUsername, :followingUsername)";
    $this->follow_user = $this->connection->prepare($sql);

    $sql = "SELECT * FROM followers WHERE follower = :followerUsername AND following = :followingUsername";
    $this->is_followed = $this->connection->prepare($sql);
  } 

  public function isFollowed($data)
  {
    try {
      $this->is_followed->execute($data);
      return ["success" => true, "data" => $this->is_followed];
    } catch (PDOException $e) {
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  }

  public function followUser($data)
  {
    try {
      $this->follow_user->execute($data);
      return ["success" => true, "data" => $this->follow_user];
    } catch (PDOException $e) {
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  }

  public function searchUser($data)
  {
    try {
      $this->search_user->execute($data);
      return ["success" => true, "data" => $this->search_user];
    } catch (PDOException $e) {
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  } 

  public function setTokenNull($data)
  {
    try {
      $this->set_token_null->execute($data);
      return ["success" => true, "data" => $this->set_token_null];
    } catch (PDOException $e) {
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  }

  public function selectLikes($data)
  {
    try {
      $this->select_like->execute($data);

      return ["success" => true, "data" => $this->select_like];
    } catch (PDOException $e) {
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  }

  public function updateUserImgURL($data)
  {
    try {
      $this->update_user_img_URL->execute($data);

      return ["success" => true, "data" => $this->update_user_img_URL];
    } catch (PDOException $e) {
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  }

  public function likePost($data)
  {
    if($this->like_post->execute($data)){
      return ["success" => true, "data" => $this->like_post];
    }else{
      return ["success" => false];
    }
  }

  public function selectLatestPosts($data)
  {
    try {
      $this->select_latest_posts->execute($data);

      return ["success" => true, "data" => $this->select_latest_posts];
    } catch (PDOException $e) {
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  }

  public function incrementLikes($data)
  {
    try {
      $this->increment_likes->execute($data);

      return ["success" => true, "data" => $this->increment_likes];
    } catch (PDOException $e) {
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  }

  public function selectUserByUsername($data)
  {
    try {
      $this->select_user_by_username->execute($data);

      return ["success" => true, "data" => $this->select_user_by_username];
    } catch (PDOException $e) {
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  }

  public function insertPost($data)
  {
    try {
      $this->insert_post->execute($data);

      return ["success" => true, "data" => $this->insert_post];
    } catch (PDOException $e) {
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  }

  public function updateUserTokenByUsername($data)
  {
    try {
      $this->update_user_token->execute($data);

      return ["success" => true, "data" => $this->update_user_token];
    } catch (PDOException $e) {
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  }

  public function insertUser($data)
  {
    try {
      $this->insert_user->execute($data);

      return ["success" => true, "data" => $this->insert_user];
    } catch (PDOException $e) {
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  }

  public function selectUsernameByToken($data)
  {
    try {
      $this->select_username_by_token->execute($data);

      return ["success" => true, "data" => $this->select_username_by_token];
    } catch (PDOException $e) {
      return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    }
  }
  /**
   * Close the connection to the DB
   */
  function __destruct()
  {
    $this->connection = null;
  }
}