<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once "../utils/dbConnection.php";
use DB\DBAccess;

$db = new DBAccess();
$dbConnection = $db->openDBConnection();
if ($dbConnection) {
    $user = $_SESSION["email"];
    $post = $_GET["title"];
    if ($user && $post) {
        $comment = $_GET["id"] !== null ? $_GET["id"] : -1;
        $text = $_POST["reply-".$comment]
            ? : $_POST["comment"]
                ? : null;
        if ($text)
            $reply = $db->reply($post, $user, $text, $comment);
    }
}

$db->closeDBConnection();

include "index.php";

?>
