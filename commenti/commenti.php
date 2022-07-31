<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

    require_once "../utils/DBconnection.php";
    use DB\DBAccess;

    require_once "../utils/utilityFunctions.php";
    use UtilityFunctions\UtilityFunctions;


      $db = new DBAccess();
      $dbConnection = $db->openDBConnection();

        $messaggio = '';

        if($dbConnection) {
            $post = $_GET["post"] ? mysqli_real_escape_string($dbConnection, $_GET["post"]) : "";
            $mainPost = $db->getPostByTitle($post);
            $nPost = $db->getNumPost($mainPost[0]["user"]);
            $comments = $db->getComments($mainPost[0]["title"]);

            $messaggio .= "
              <div class='topic-container'>
                <div class='head'><div class='content'>{$mainPost[0]['title']}</div></div>
                <div class='body'>
                  <div class='authors'>
                    <div class='username'><p>{$mainPost[0]['user']}</p></div>
                    <div>Post : <u>{$nPost[0]['COUNT(*)']}</u></div>
                    <div>{$mainPost[0]['date']}</div>
                  </div>
                  <div class='description'>
                    <p>{$mainPost[0]['description']}</p>
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
              </div></div>";

                 foreach ($comments as $c) {
                    $id = $c["id"];
                    $user = $c["user"];
                    $reply = $db->getReplyComments($c["post"],$id);
                    $nPost = $db->getNumPost($c["user"]);


                  $messaggio .= "<div class='comment-container'>
                                <div class='body'>
                                  <div class='authors'>
                                    <div class='username'><p>{$c['user']}</p></div>
                                    <div>Post : <u>{$nPost[0]['COUNT(*)']}</u></div>
                                    <div>{$c['date']}</div>
                                  </div>
                                  <div class='description'>
                                    <p>{$c['text']}</p>

                                  ";

                                    foreach ($reply as $a) {
                                      $messaggio .= "<div class='reply'>
                                                  <p class='content'>{$a['user']}: {$a['text']}</p>
                                                  <p class='date'>{$a['date']}</p>
                                                </div>";
                                    }

                              $messaggio .="<div class='comment'>
                                              <button id='comment'>Commenta</button>
                                            </div>
                                          </div>
                                      </div>
                                    </div>";

                    $messaggio .= "<div class='reply-area'>
                                <form action='commenta.php' method='post'>
                                  <textarea name='reply' placeholder='commenta qui . . . '></textarea>
                                  <input type='submit' value='invia'>
                                </form>
                              </div>";

                }
        } else {
            $messaggio = '<div class="subcontainer danger"><p>Errore nella connessione al server. Per favore riprova più tardi.</p></div>';
        }




    $url = "comments.html";

    echo UtilityFunctions::replace($url, array("<comments />" => $messaggio));
?>
