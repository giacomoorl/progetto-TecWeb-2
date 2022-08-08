<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once "./utils/dbConnection.php";
use DB\DBAccess;

if(isset($_SESSION["isValid"])&&isset($_SESSION["username"])&&$_SESSION["isValid"])
{
    $db = new DBAccess();
    $dbConnection = $db->openDBConnection();

    if ($dbConnection) {
        $user=$_SESSION["username"];
        $del=$db->deleteAccount($user);
        if($del)
        {
            ///////////account eliminato con successo
        }
        else
        {
        }
    }
        
    $db->closeDBConnection();
}
header("Location: ./index.php");
?>