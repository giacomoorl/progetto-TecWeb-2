<?php


require_once "./utils/dbConnection.php";
use DB\DBAccess;

require_once "./utils/utilityFunctions.php";
use UtilityFunctions\UtilityFunctions;

$db = new DBAccess();
$dbConnection = $db->openDBConnection();

$HTML = "";

if ($dbConnection) {

    $post = $_GET["post"];
    $mainPost = $db->getPostByTitle($post);
    $comments = $db->getComments($mainPost);
    $nPost = $db->getNumPost($mainPost["user"]);

    $HTML .= "<div class='post'>

              <div class='topic-container'>
                <div class='head'><div clasas='content'>{$mainPost["title"]}</div></div>
                <div class='body'>
                  <div class='author'>
                    <div class='username'><p>{$mainPost["user"]}</p></div>
                    <div>Post : <u>$nPost</u></div>
                    <div>{$mainPost["date"]}</div>
                  </div>
                  <div class='content'>
                    <p>{$mainPost["description"]}</p>
                    <div class='comment'>
                      <button id='comment'>Commenta</button>
                    </div>
                  </div>
               </div>

              <div class='comment-area'>
                <form action='commenta.php' method='post'>
                  <textarea name='comment' placeholder='commenta qui . . . '></textarea>
                  <input type='submit' value='invia'>
                </form>
              </div>";
if($commenti){
    foreach ($comments as $c) {
        $id = $c["id"];
        $reply = $db->getReplyComments($c["post"],$id);
        $nPost = $db->getNumPost($c["user"]);

        $HTML .= "<div class='comment-container'>
                    <div class='body'>
                      <div class='author'>
                        <div class='username'><p>{$c["user"]}</p></div>
                        <div>Post : <u>$nPost</u></div>
                        <div>{$c{"date"}}</div>
                      </div>
                      <div class='content'>
                        <p>{$c["text"]}</p>
                        <div class='comment'>
                          <button id='comment'>Commenta</button>
                        </div>
                      </div>";

                      if($reply){
                        foreach ($reply as $a) {
                          $HTML .= "<div class='reply'>
                                      <p>{$a["user"]}: {$a["text"]}</p>
                                      <p class='date'>{$a["date"]}</p>
                                    </div>";
                        }
                      }
                  $HTML .="
                          </div>
                        </div>";

        $HTML .= "<div class='reply-area'>
                    <form action='commenta.php' method='post'>
                      <textarea name='reply' placeholder='commenta qui . . . '></textarea>
                      <input type='submit' value='invia'>
                    </form>
                  </div>";

    }}
    $HTML .="</div>";
} else {
  $HTML .= "<div>Errore connessione</div>";
}

$db->closeDBConnection();

echo UtilityFunctions::replace(
    "comments.html",
    ["<comments />" => $HTML]
);

?>
