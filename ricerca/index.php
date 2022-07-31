<?php

require_once "../utils/dbConnection.php";
use DB\DBAccess;

require_once "../utils/utilityFunctions.php";
use UtilityFunctions\UtilityFunctions;

$db = new DBAccess();
$dbConnection = $db->openDBConnection();

$HTMLPost = "";
$HTMLPagination = "";
$post = array();

if ($dbConnection) {

    $title = $_GET["title"] ? mysqli_real_escape_string($dbConnection, $_GET["title"]) : "";
    $HTMLPagination .= "<input type='text' name='title' value='$title' />";
    $page = $_GET["page"] ? mysqli_real_escape_string($dbConnection, $_GET["page"]) : '1';
    $page = intval($page);
    $post = $db->getPostsByTitleByPage($title, $page);
    foreach ($post as $p) {
        $HTMLPost .= "<div class='post'>
            <h3>
                <a href='../commenti/?title={$p["title"]}'>{$p["title"]}</a>
            </h3>
            <p class='author'>{$p["user"]}</p>
            <p class='date'>{$p["date"]}</p>
            <p>{$p["description"]}</p>
        </div>";
    }
    $post = $db->getPostsByTitle($title);
    for ($pagination = 1; $pagination <= (count($post) / 10) + 1; ++$pagination) {
        $HTMLPagination .= "<input 
            type='submit'
            name='page'
            value=$pagination
            class='button'
            ".($pagination === $page ? "disabled='disabled'" : "")."
        />";
    }
}

$db->closeDBConnection();
$count = count($post);
$search = "<p>Risultati della ricerca di {$_GET["title"]} ($count elementi):</p>";
$HTMLPost = $search.$HTMLPost;

echo UtilityFunctions::replace(
    "ricerca.html",
    [
        "<post />" => $HTMLPost,
        "<pages />" => $HTMLPagination
    ]
);

?>