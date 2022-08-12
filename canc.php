<?php
	// richiamo il file di configurazione

require 'config.php';

// richiamo lo script responsabile della connessione a MySQL
require 'connect.php';

if($_POST)
{
   $ids = isset($_POST['id']) ? $_POST['id'] : array();
   elimina_record($ids);
}
elseif(isset($_GET['id']))
{
   elimina_record(array($_GET['id']));
}
else
   mostra_lista();

function mostra_lista()
{
   // mostro un eventuale messaggio
   if(isset($_GET['msg']))
       echo '<b>'.htmlentities($_GET['msg']).'</b><br /><br />';

   // preparo la query
   $query = "SELECT id,nome FROM utenti";

   // invio la query
   $result = mysql_query($query);

   // controllo l'esito
   if (!$result) {
       die("Errore nella query $query: " . mysql_error());
   }

   echo '
   <form name="form1" method="post" action="">
   <table border="1">
       <tr>
           <th>&nbsp;</th>
           <th>Nome</th>
           <th>&nbsp;</th>
       </tr>';

   while ($row = mysql_fetch_assoc($result))
   {
       $nome = htmlentities($row['nome']);

       // preparo il link per la modifica dei dati del record
       $link = $_SERVER['PHP_SELF'].'?id=' . $row['id'];

       echo "<tr>
               <td><input name=\"id[]\" type=\"checkbox\" value=\"$row[id]\" /></td>
               <td>$nome</td>
               <td><a href=\"$link\">elimina</a></td>
           </tr>";
   }

   echo '</table>
       <br />
       <input type="submit" name="Submit" value="Elimina record selezionati" />
       </form>';

   // libero la memoria di PHP occupata dai record estratti con la SELECT
   mysql_free_result($result);

   // chiudo la connessione a MySQL
   mysql_close();
}

function elimina_record($ids)
{
   // verifico che almeno un id sia stato selezionato
   if(count($ids) < 1)
   {
       $messaggio = urlencode("Nessun record selezionato!");
       header('location: '.$_SERVER['PHP_SELF'].'?msg='.$messaggio);
       exit;
   }

   // per precauzione converto gli ID in interi
   $ids = array_map('intval',$ids);

   // creo una lista di ID per la query
   $ids = implode(',',$ids);

   // preparo la query
   $query = "DELETE FROM utenti WHERE id IN ($ids)";

   // invio la query
   $result = mysql_query($query);

   // controllo l'esito
   if (!$result) {
       die("Errore nella query $query: " . mysql_error());
   }

   // conto il numero di record cancellati
   $num_record = mysql_affected_rows();

   // chiudo la connessione a MySQL
   mysql_close();

   $messaggio = urlencode("Numero record cancellati: $num_record");
   header('location: '.$_SERVER['PHP_SELF'].'?msg='.$messaggio);
}
?>