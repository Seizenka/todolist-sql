<?php

include 'sqlconnect.php';

try{
    $bdd = new PDO ('mysql:host=localhost; dbname=becode; charset=utf8', 'root', 'user');
}
catch (Exception $e){
    die("Erreur ".$e->getmessage());
}

$ask = $bdd->query("SELECT * FROM archive");
$req = $bdd->query("SELECT * FROM todolist");

//Pour afficher le contenu de la base de données todolist
    function afficher(){
    $bdd = new PDO ('mysql:host=localhost; dbname=becode; charset=utf8', 'root', 'user');
    $req = $bdd->query("SELECT * FROM todolist");
    $affiche=$req->fetchAll();
    //Parcourir le tableau
    echo '<h1> Ma todolist !</h1>';
    //Barre de recherche
    echo '<form action="index.php" method="POST">';
    echo '<input type="search" class="q" name="q" placeholder="Search..." />';
    echo '<input type="submit" class="valider" name ="valider" value="valider" />';
    echo '</form>';
    echo '<div class="check" class="dropper">';
    foreach($affiche as $component){
        echo '<p class="draggable"><input name="check[]" type="checkbox" value="'.$component['task'].'">'.$component["task"].'</p><p></p>'; //check[] parce qu'on peut check plusieurs en même temps
       
           
    }
    echo '</div>';
}

    //Barre de recherche
    if(isset($_GET['q']) AND !empty($_POST['q'])) {
        $q = htmlspecialchars($_POST['q']);
        $articles = $bdd->query('SELECT done FROM archive WHERE done LIKE "%'.$q.'%" ORDER BY id ASC');
        
     }

    //Pour afficher le contenu de la base de données archive
    function archivage(){
        $bdd = new PDO ('mysql:host=localhost; dbname=becode; charset=utf8', 'root', 'user');
        $ask = $bdd->query("SELECT * FROM archive");
        $show=$ask->fetchAll();
        //Parcourir le tableau
        echo '<h3>ARCHIVE</h3>';
        foreach($show as $composent){
            echo '<p class="done">'.$composent["done"].'</p>'; //check[] parce qu'on peut check plusieurs en même temps
          
        }    
    }
    //-----La sanitisation-----//
    //Nettoyer les champs
    $sanitisation = array (
        'task' => FILTER_SANITIZE_STRING, FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'q' => FILTER_SANITIZE_STRING, FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    );
    $result = filter_input_array(INPUT_POST, $sanitisation);
    //Si le résultat retourné après le filtre est vide ou erreur
    if ($result !=null AND $result!=FALSE){
        echo "ok";
    }


    //Ajout des données dans la base de données
    if (isset($_POST["ajout"])){ //Vaut un onclick 
        $task = $_POST['task'];
        $insertion = $bdd->exec("INSERT INTO todolist(task) VALUES ('$task')");
    }
    //Bouton supprimer des données
    if(isset($_POST['supprimer'])){
        foreach($_POST['check'] as $check)
            $bdd->exec('DELETE from todolist WHERE task= "'.$check.'"');//prepare : préparer une requête -------Les deux points représentent une autre variables
    }

    //Archiver des données dans une deuxième base de données
    if(isset($_POST['archive'])){
        foreach($_POST['check'] as $check){
            $bdd->exec('INSERT INTO archive(done) VALUES("'.$check.'")');
            $bdd->exec('DELETE FROM todolist WHERE task= "'.$check.'"');
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>TODOLIST</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="assets/scss/style.scss">
</head>
<body>

<!--les tâches à enregistrer représentées dans un premier bloc -->
    <fieldset class="btn">
    
        <form action="index.php" method="POST">
        <?php afficher()?> <!-- On rappelle la fonction -->
        <input class="sup" type="submit" value="supprimer" name="supprimer">
        <input class="archive" type="submit" value="archive" name="archive"><p></p>
        </form>
    </fieldset>

    <fieldset class="dropper">
        <?php archivage()?><!-- On rappelle la fonction-->
    </fieldset>


<!-- Deuxième bloc où on retrouve le formulaire et le bouton ajouter -->
    <fieldset class="form">
        <form action="index.php" method="POST">
            <label for="task">La tâche à ajouter</label>
            <input class="rajout" name="task" type="text"required placeholder="rajouter une tâche">
            <input class="ajout" type="submit" value="ajouter" name="ajout">
        </form>
    </fieldset>
    <script src="assets/js/main.js"></script>
</body>
</html>