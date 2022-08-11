<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once "../utils/dbConnection.php";
use DB\DBAccess;

require_once "../utils/utilityFunctions.php";
use UtilityFunctions\UtilityFunctions;

if(isset($_SESSION["isValid"])&&$_SESSION["isValid"])
{
    header("Location: ../index.php");
}

$db = new DBAccess();
$dbConnection = $db->openDBConnection();

if ($dbConnection) {


    if (isset($_POST["username"]) && isset($_POST["pwd"])) {
        $user = $_POST["username"];
        $pass = $_POST["pwd"];
        $loginOK = $db->getLogin($user, $pass);
        $_SESSION["isValid"] = $loginOK["isValid"];
        if ($_SESSION["isValid"]) {
            $_SESSION["username"] = $loginOK["user"];
            $_SESSION["isAdmin"] = $loginOK["isAdmin"];
            $db->closeDBConnection();
            header("Location: ../index.php");
        } else {
            $messaggio = "<p class=\"alert-box danger\" id=\"datiNonCorretti\">Dati non corretti!</p>";
            $nuovo = array(
                "<msgErrore />" => $messaggio
            );
            echo UtilityFunctions::replace("../login/login.html", $nuovo);
        }
    } else {
        echo UtilityFunctions::replace("../login/login.html", array("<msgErrore />" => ""));
    }
}

$db->closeDBConnection();

?>