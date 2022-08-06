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

        echo UtilityFunctions::replace("./signup.html", $nuovo);
    }
}
$db->closeDBConnection();
?>