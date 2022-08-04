<?php

namespace DB;

class DBAccess {

    private const HOST_DB = "localhost";
    private const USERNAME = "root";
    private const PASSWORD = "root";
    private const DATABASE_NAME = "forumm";

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

    public function getPostsByTitle($title) {
        $query = "SELECT * FROM `POST` WHERE `title` LIKE '%$title%'";
        return $this->query($query);
    }

    public function getPostsByTitleByPage($title, $page) {
        $page = ($page - 1) * 10;
        $nextPage = $page + 10;
        $query = "SELECT `P1`.`title`, `P1`.`user`, `P1`.`date`, `P1`.`description`
            FROM (
                SELECT *
                FROM `POST`
                WHERE `title` LIKE '%$title%'
                LIMIT $nextPage
            ) AS `P1` LEFT JOIN (
                SELECT title
                FROM `POST`
                WHERE `title` LIKE '%$title%'
                LIMIT $page
            ) AS `P2` ON `P1`.`title`=`P2`.`title`
            WHERE `P2`.`title` IS NULL";
        return $this->query($query);
    }

    public function getComments($post) {
        $query = "SELECT *
            FROM `POST` JOIN `COMMENTO` ON `POST`.`title`=`COMMENTO`.`post`
            WHERE `POST`.`title`='$post' AND `COMMENTO`.`reply`='-1'
            ORDER BY `COMMENTO`.`date` DESC";
        return $this->query($query);
    }

    public function getReplyComments($post,$id) {
      $query = "SELECT * FROM (
                  SELECT `POST`.`title`,`POST`.`user`,`COMMENTO`.`text`,`COMMENTO`.`date`,`COMMENTO`.`reply`
                      FROM `POST` JOIN `COMMENTO` ON `POST`.`title`=`COMMENTO`.`post`
                      WHERE `POST`.`title`='$post'
                ) AS t1
                WHERE `t1`.`reply`='$id'
                ORDER BY `t1`.`date` DESC";
      return $this->query($query);
    }

    public function reply($post, $user, $text, $id) {
      $query = "INSERT INTO `COMMENTO` (`post`, `user`, `text`, `reply`) VALUES
              ('$post', '$user', '$text', '$id')";
      return mysqli_query($this->connection, $query);
    }
}

?>
