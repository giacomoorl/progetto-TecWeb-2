<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once "../utils/dbConnection.php";
use DB\DBAccess;

$db = new DBAccess();
$dbConnection = $db->openDBConnection();
if ($dbConnection) {
    $user = $_SESSION["username"];
    $title = $_GET["title"];
    if ($user) {
      $reply = $db->deletePost($title);
    }
}

$db->closeDBConnection();

header("Location: ../index.php");

?>
