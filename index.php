<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once "./utils/dbConnection.php";
use DB\DBAccess;

require_once "./utils/utilityFunctions.php";
use UtilityFunctions\UtilityFunctions;

$db = new DBAccess();
$dbConnection = $db->openDBConnection();

$HTMLPost = "";
$HTMLPagination = "";

if ($dbConnection) {

    $category = $_GET["category"] ? mysqli_real_escape_string($dbConnection, $_GET["category"]) : "all";
    $HTMLPagination .= "<input type='text' name='category' value=$category />";
    $page = $_GET["page"] ? mysqli_real_escape_string($dbConnection, $_GET["page"]) : '1';
    $page = intval($page);
    $post = $db->getPostByCategoryByPage(
        $category !== "all" ? $category : "' OR ''='",
        $page
    );
    foreach ($post as $p) {
        $HTMLPost .= "<div class='post'>
            <h3>
                <a href='commenti/comments.php?post={$p["title"]}'>{$p["title"]}</a>
            </h3>
            <p class='author'>{$p["user"]}</p>
            <p class='date'>{$p["date"]}</p>
            <p>{$p["description"]}</p>
        </div>";
    }
    $post = $db->getPostByCategory($category !== "all" ? $category : "' OR ''='");
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

echo UtilityFunctions::replace(
    "./post/post.html",
    ["<post />" => $HTMLPost, "<pages />" => $HTMLPagination]
);

?>
