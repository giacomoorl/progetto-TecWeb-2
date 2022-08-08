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
        $creazioneOK = $db->newAccount($user, $pass);
        if($creazioneOK)
        {
            $_SESSION["isValid"]=true;
            $_SESSION["isAdmin"]=false;
            $_SESSION["username"] = $user;
            $db->closeDBConnection();
            header("Location: ../index.php");
        }
        else
        {
            require_once "../utils/utilityFunctions.php";
            $messaggio = "<p class=\"alert-box danger\" id=\"datiNonCorretti\">Nome utente già in uso!</p>";
            $nuovo = array(
                "<msgErrore />" => $messaggio
            );

            echo UtilityFunctions::replace("../login/signup.html", $nuovo);
        }
    } else {
        echo UtilityFunctions::replace("../login/signup.html", array("<msgErrore />" => ""));
    }
}
$db->closeDBConnection();
?>