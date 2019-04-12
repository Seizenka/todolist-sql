<?php
//on se connecte à notre database
try{
    $bdd = new PDO ('mysql:host=localhost; dbname=becode; charset=utf8', 'root', 'user');
}
catch (Exception $e){
    die("Erreur ".$e->getmessage());
}

?>