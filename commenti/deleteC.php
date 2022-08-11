<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once "../utils/dbConnection.php";
use DB\DBAccess;

$db = new DBAccess();
$dbConnection = $db->openDBConnection();
if ($dbConnection) {
    $user = $_SESSION["username"];
    $id = $_GET["id"];
    if ($user) {
      $reply = $db->deleteReply($id);
      $comment = $db->deleteComment($id);
    }
}

$db->closeDBConnection();

header("Location: index.php?title={$_GET["title"]}");

?>
