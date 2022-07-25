<?php

require_once "../utils/dbConnection.php";
use DB\DBAccess;

require_once "../utils/utilityFunctions.php";
use UtilityFunctions\UtilityFunctions;

$db = new DBAccess();
$dbConnection = $db->openDBConnection();

$HTMLPost = "";

if ($dbConnection) {

    $title = $_GET["title"] ? mysqli_real_escape_string($dbConnection, $_GET["title"]) : "";
    $post = $db->getPostByTitleContains($title);
    foreach ($post as $p) {
        $HTMLPost .= "<div>
            <h3>
                <a href='../commenti/?title={$p["title"]}'>{$p["title"]}</a>
            </h3>
            <p class='author'>{$p["user"]}</p>
            <p class='date'>{$p["date"]}</p>
            <p>{$p["description"]}</p>
        </div>";
    }
}

$db->closeDBConnection();

echo UtilityFunctions::replace(
    "ricerca.html",
    ["<search />" => $_GET["title"], "<post />" => $HTMLPost]
);

?>