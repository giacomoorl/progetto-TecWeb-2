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
            <p>{$c['text']}</p>
          </div>";
      $messaggio .= "
        <div class='replies $class'>
      ";
      foreach ($reply as $r) {
        $messaggio .= "
          <div class='reply'>
            <p class='author'>{$r["user"]} in risposta a {$c["user"]}</p>
            <p class='date'>{$r["date"]}</p>
            <p>{$r["text"]}</p>
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


echo UtilityFunctions::replace("comments.html", array("<comments />" => $messaggio));

?>
