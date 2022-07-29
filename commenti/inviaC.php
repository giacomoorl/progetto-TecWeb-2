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
    $user = $_SESSION['email'];
    $post = $_GET['post'];
    $text = $_GET['comment'];
    $risultatoQuery = $db->commenta($post, $user, $text);
    if ($risultatoQuery){
      $HTML .= "<div class='risultato'><p>Commento Inviato</p></div>";
    } else {
      $HTML .= "<div class='risultato'><p>Errore nell'inserimento</p></div>";
    }

} else {
    $HTML .= "<div class='risultato'><p>Errore di connessione</p></div>";
}

$db->closeDBConnection();

echo UtilityFunctions::replace(
    "./comment/comments.html",
    "<comment />" => $HTML
);

?>
