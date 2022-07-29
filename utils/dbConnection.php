<?php

namespace DB;

class DBAccess {

    private const HOST_DB = "localhost";
    private const USERNAME = "root";
    private const PASSWORD = "root";
    private const DATABASE_NAME = "forum";

    private $connection;

    public function openDBConnection() {
        $this->connection = mysqli_connect(
            DBAccess::HOST_DB,
            DBAccess::USERNAME,
            DBAccess::PASSWORD,
            DBAccess::DATABASE_NAME
        );
        if ($this->connection)
            mysqli_query($this->connection, "SET lc_time_names = 'it_IT'");
        return $this->connection;
    }

    private function query($query) {
        $queryResult = mysqli_query($this->connection, $query);
        $result = array();
        while ($row = mysqli_fetch_assoc($queryResult)) {
            $result[] = $row;
        }
        return $result;
    }

    public function closeDBConnection() {
            if ($this->connection)
                mysqli_close($this->connection);
    }

    public function getPostByCategory($category) {
        $query = "SELECT * FROM `POST` WHERE `category`='$category'";
        return $this->query($query);
    }

    public function getPostByCategoryByPage($category, $page) {
        $page = ($page - 1) * 10;
        $nextPage = $page + 10;
        $query = "SELECT `P1`.`title`, `P1`.`user`, `P1`.`date`, `P1`.`description`
            FROM (
                SELECT *
                FROM `POST`
                WHERE `category`='$category'
                LIMIT $nextPage
            ) AS `P1` LEFT JOIN (
                SELECT title
                FROM `POST`
                WHERE `category`='$category'
                LIMIT $page
            ) AS `P2` ON `P1`.`title`=`P2`.`title`
            WHERE `P2`.`title` IS NULL";
        return $this->query($query);
    }

    public function getPostByTitle($title) {
        $query = "SELECT * FROM `POST` WHERE `title`='$title'";
        return $this->query($query);
    }

    public function getComments($post) {
        $query = "SELECT *
            FROM `POST` JOIN `COMMENTO` ON `POST`.`id`=`COMMENTO`.`post`
            WHERE `POST`.`title`='$post' AND `COMMENTO`.`reply`='0';
            ORDER BY `COMMENTO`.`date`";
        return $this->query($query);
    }

    public function getReplyComments($post,$id) {
      $query = "SELECT * FROM (
                  SELECT *
                      FROM `POST` JOIN `COMMENTO` ON `POST`.`id`=`COMMENTO`.`post`
                      WHERE `POST`.`title`='$post'
                )
                WHERE `COMMENTO`.`reply`='$id'
                ORDER BY `COMMENTO`.`date`";
      return $this->query($query);
    }

    public function commenta($post,$user,$text) {
      $query = "INSERT INTO `COMMENTO` (`post`, `user`, `text`) VALUES
              ('$post', '$user', '$text')";
      return $this->query($query);
    }

    public function rispondi($post,$user,$text,$id) {
      $query = "INSERT INTO `COMMENTO` (`post`, `user`, `text`,`id`) VALUES
              ('$post', '$user', '$text',`$id`)";
      return $this->query($query);
    }

    public function getNumPost($user) {
      $query = "SELECT COUNT * FROM (
                  SELECT *
                  FROM `USER` JOIN `POST` ON `USER`.`username` = `POST`.`user`
                ) WHERE `POST`.`user` = $user";
      return $this->query($query);
    }

}

?>
