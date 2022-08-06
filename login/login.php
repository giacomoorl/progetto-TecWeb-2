<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once "../utils/dbConnection.php";
use DB\DBAccess;

require_once "../utils/utilityFunctions.php";
use UtilityFunctions\UtilityFunctions;

$db = new DBAccess();
$dbConnection = $db->openDBConnection();

if ($dbConnection) {
    $user = $_POST["username"];
    $pass = $_POST["pwd"];

    if ($user && $pass) {
        $loginOK = $db->getLogin($user, $pass);
        $_SESSION["isValid"] = $loginOK["isValid"];
        $_SESSION["isAdmin"] = $loginOK["isAdmin"];
        if ($_SESSION["isValid"]) {
            $_SESSION["username"] = $loginOK["user"];
            $db->closeDBConnection();
            header("Location: ../index.php");
        } else {
            $messaggio = "<p class=\"alert-box danger\" id=\"datiNonCorretti\">Dati non corretti!</p>";
            $nuovo = array(
                "<msgErrore />" => $messaggio
            );
            echo UtilityFunctions::replace("login.html", $nuovo);
        }
    } else {
        echo UtilityFunctions::replace("login.html", array("<msgErrore />" => ""));
    }
}

$db->closeDBConnection();

?>