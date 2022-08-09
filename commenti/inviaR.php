<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once "../utils/dbConnection.php";
use DB\DBAccess;

$db = new DBAccess();
$dbConnection = $db->openDBConnection();
if ($dbConnection) {
    if (isset($_SESSION["username"]) && isset($_GET["title"])) {
        $user = $_SESSION["username"];
        $post = $_GET["title"];
        $comment = isset($_GET["id"]) ? $_GET["id"] : -1;
        $text = isset($_POST["reply-".$comment]) ? $_POST["reply-".$comment]: isset($_POST["comment"]) ? $_POST["comment"]: null;
        if ($text){
            $reply = $db->reply($post, $user, $text, $comment);
    }
}

$db->closeDBConnection();

header("Location: index.php?title={$_GET["title"]}");

?>
