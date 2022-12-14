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

    $category = isset($_GET["category"]) ? mysqli_real_escape_string($dbConnection, $_GET["category"]) : "all";
    $HTMLPagination .= "<input type='text' name='category' value='$category' />";
    $page = isset($_GET["page"]) ? mysqli_real_escape_string($dbConnection, $_GET["page"]) : '1';
    $page = intval($page);
    $post = $db->getPostByCategoryByPage(
        $category !== "all" ? $category : "' OR ''='",
        $page
    );
    foreach ($post as $p) {
        $HTMLPost .= "<div class='post'>
            <h3>
                <a href='commenti/?title={$p["title"]}'>{$p["title"]}</a>
            </h3>
            <p class='author'>{$p["user"]}</p>
            <p class='date'>{$p["date"]}</p>
            <p>{$p["description"]}</p>";

            if(isset($_SESSION["isValid"])&&$_SESSION["isValid"]){
              if($_SESSION["username"] == $p["user"] || $_SESSION["isAdmin"] == 1){
                $HTMLPost .= "
                  <a href='post/deletePost.php?title={$p["title"]}'>
                    <i class='fa fa-trash'></i>
                  </a>";
              }
            }

          $HTMLPost .= "</div>";
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

$loginEffettuato="";
if(isset($_SESSION["isValid"])&&$_SESSION["isValid"])
{
    $loginEffettuato="<div id=\"login-signup\"><div id='logout' class='button'><a href='./login/logout.php'>Logout</a></div>
    <div id='elimina' class='button'><a href='./deleteAccount.php'>Elimina Account</a></div></div>";
}
else
{
$loginEffettuato="<div id=\"login-signup\">
<div id=\"login\" class=\"button\">
    <a href=\"./login/login.php\">Accedi</a>
</div>
<div id=\"signup\" class=\"button\">
    <a href=\"./login/signup.php\">Registrati</a>
</div>
</div>";
}

$db->closeDBConnection();

echo UtilityFunctions::replace(
    "post/post.html",
    ["<post />" => $HTMLPost, "<pages />" => $HTMLPagination,
    "<bottoni/>"=>$loginEffettuato]
);

?>
