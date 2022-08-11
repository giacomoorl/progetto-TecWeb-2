<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once "../utils/dbConnection.php";
use DB\DBAccess;

require_once "../utils/utilityFunctions.php";
use UtilityFunctions\UtilityFunctions;

$db = new DBAccess();
$dbConnection = $db->openDBConnection();

$messaggio = '';

if ($dbConnection) {
  if ($_GET["title"]) {
    $title = mysqli_real_escape_string($dbConnection, $_GET["title"]);
    $mainPost = $db->getPostByTitle($title);
    $comments = $db->getComments($mainPost[0]["title"]);
    $messaggio .= "
      <div id='head'>
        <h2>
            {$mainPost[0]["title"]}
        </h2>
        <p class='author'>{$mainPost[0]["user"]}</p>
        <p class='date'>{$mainPost[0]["date"]}</p>
        <p>{$mainPost[0]["description"]}</p>
      </div>
      <div id='body'>";

    foreach ($comments as $i => $c) {
      $reply = $db->getReplyComments($c["post"], $c["id"]);
      $class = $i % 2 === 0 ? "left" : "right";
      $messaggio .= "
        <div class='comment-container $class'>
          <div class='comment $class'>
            <p class='author'>{$c["user"]}</p>
            <p class='date'>{$c["date"]}</p>
            <p>{$c['text']}</p>";
            if(isset($_SESSION["isValid"])&&$_SESSION["isValid"]){
              if($_SESSION["username"] == $c["user"] || $_SESSION["isAdmin"] == 1)
                $messaggio .= "
                  <a href='deleteC.php?title={$mainPost[0]["title"]}&id={$c["id"]}'>
                    <i class='fa fa-trash'></i>
                  </a>";
            }
            $messaggio .="
          </div>";
      $messaggio .= "
        <div class='replies $class'>
      ";
      foreach ($reply as $r) {
        $messaggio .= "
          <div class='reply'>
            <p class='author'>{$r["cUser"]} in risposta a {$c["user"]}</p>
            <p class='date'>{$r["date"]}</p>
            <p>{$r["text"]}</p>";
            if(isset($_SESSION["isValid"])&&$_SESSION["isValid"]){
              if($_SESSION["username"] == $r["cUser"] || $_SESSION["username"] == 1)
                $messaggio .= "
                  <a href='deleteR.php?title={$mainPost[0]["title"]}&idC={$c["id"]}&idR={$r["cId"]}'>
                    <i class='fa fa-trash' ></i>
                  </a>";
            }
            $messaggio .="
          </div>
        ";
      }
      $messaggio .= "
            <div class='reply-form'>
              <form action='inviaR.php?title={$mainPost[0]["title"]}&id={$c["id"]}' method='post'>
                <textarea name='reply-{$c["id"]}' placeholder='Rispondi a {$c["user"]}...' ></textarea>
                <input class='button' type='submit' name='{$c["id"]}' value='Invia' />
              </form>
            </div>
          </div>
        </div>";
    }
    $messaggio .= "
      </div>
      <div id='form'>
        <form action='inviaR.php?title={$mainPost[0]["title"]}' method='post'>
          <textarea name='comment' placeholder='Commenta qui...' ></textarea>
          <input class='button' type='submit' value='Invia' />
          <a class='button' href='#head'>Torna su</a>
        </form>
      </div>";
  }
} else {
    $messaggio = '<div class="subcontainer danger"><p>Errore nella connessione al server. Per favore riprova più tardi.</p></div>';
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
    <a href=\"../login/login.php\">Accedi</a>
</div>
<div id=\"signup\" class=\"button\">
    <a href=\"../login/signup.php\">Registrati</a>
</div>
</div>";
}
echo UtilityFunctions::replace("comments.html", 
[
  "<comments />" => $messaggio,
  "<bottoni/>"=>$loginEffettuato
      ]);

?>
