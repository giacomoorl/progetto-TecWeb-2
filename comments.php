<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once "./utils/dbConnection.php";
use DB\DBAccess;

require_once "./utils/utilityFunctions.php";
use UtilityFunctions\UtilityFunctions;

$db = new DBAccess();
$dbConnection = $db->openDBConnection();

$HTML = "";
$HTMLPagination = "";

if ($dbConnection) {

    $post = $_GET["post"];
    $mainPost = $db->getPostByTitle($post);
    $comments = $db->getComments($mainPost);

    $HTML .= "<div class='post'>
                <h2>
                    <a>$mainPost["title"]</a>
                </h2>
                <p class='author'>$mainPost["user"]</p>
                <p class='date'>$mainPost["date"]</p>
                <p>$mainPost["description"]</p>
              "

    foreach ($comments as $c) {
        $id = $c["id"];
        $answer = $db->getReplyComments($c,$id);
        $HTML .= "<div class='comment'>
            <p>{$c["text"]}</p>
            <p class='date'>{$c["date"]}</p>";
        if($answer){
          foreach ($answer as $a) {
            $HTML .= "<div class='answer'>
                      <p>@{$a["user"]}: {$a["text"]}</p>
                      <p class='date'>{$a["date"]}</p>"
          }
        }

        $HTML .="</div>";
    }
    $HTML .="</div>";
}

$db->closeDBConnection();

echo UtilityFunctions::replace(
    "./comment/comments.html",
    "<comment />" => $HTML
);

?>
