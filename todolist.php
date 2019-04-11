<?php

//on se connecte à notre database
  try{
    $bdd = new PDO ('mysql:host=localhost; dbname=becode; charset=utf8', 'root', 'user');
}
catch (Exception $e){
    die("Erreur ".$e->getmessage());
}
//Pour afficher le contenu de la base de données
    function afficher(){
    $bdd = new PDO ('mysql:host=localhost; dbname=becode; charset=utf8', 'root', 'user');
    $req = $bdd->query("SELECT * FROM todolist");
    $affiche=$req->fetchAll();
    //Parcourir le tableau
    echo '<h1> Ma todolist !</h1>';
    echo '<div class="check">';
    foreach($affiche as $component){
        echo '<td><input name="check[]" type="checkbox" value="'.$component['id'].'">'.$component["task"].'</td><p></p>'; //check[] parce qu'on peut check plusieurs en même temps
      
    }
    echo '</div>';
}

    //Pour afficher le contenu de la base de données
    function archivage(){
        $bdd = new PDO ('mysql:host=localhost; dbname=becode; charset=utf8', 'root', 'user');
        $ask = $bdd->query("SELECT * FROM Archive");
        $show=$ask->fetchAll();
        //Parcourir le tableau
        echo '<h3>ARCHIVE</h3>';
        foreach($show as $composent){
            echo '<p>'.$composent["done"].'</p>'; //check[] parce qu'on peut check plusieurs en même temps
          
        }    
    }
    //Nettoyer les champs
    $sanitisation = array (
        'task' => FILTER_SANITIZE_STRING, FILTER_SANITIZE_FULL_SPECIAL_CHARS,
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
        header("location:todolist.php");//raffraîchir la page juste en faisant F5
    }
    //Supprimer des données
    if(isset($_POST['supprimer'])){
        $check = $_POST['check']; //Nouvelle variable
        $delete = $bdd->prepare("DELETE from todolist WHERE id= :id");//prepare : préparer uen requête -------Les deux points représentent une autre variables
        foreach ($check as $id){
            $delete->execute(array(":id"=> $id));
        }
        header("location:todolist.php");
    }

    //Archiver des données dans une deuxième base de données
    if(isset($_POST['archive'])){
        $check = $_POST['check']; //Nouvelle variable
        $inserer = $bdd->exec("INSERT INTO Archivage VALUE ('.$check.')");//prepare : préparer uen requête -------Les deux points représentent une autre variables
        $delete = $bdd->prepare("DELETE from todolist WHERE id= '.$check.'");
        header("location:todolist.php");
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
        <form action="#" method="POST">
        <?php afficher()?> <!-- On rappelle la fonction -->
        <input class="sup" type="submit" value="supprimer" name="supprimer"><p></p>
        <input class="archive" type="submit" value="archive" name="archive"><p></p>
        </form>
    </fieldset>

    <fieldset>
        <?php archivage()?><!-- On rappelle la fonction-->
    </fieldset>


<!-- Deuxième bloc où on retrouve le formulaire et le bouton ajouter -->
    <fieldset class="form">
        <form action="#" method="POST">
            <label for="task">La tâche à ajouter</label>
            <input class="rajout" name="task" type="text"required placeholder="rajouter une tâche">
            <input class="ajout" type="submit" value="ajouter" name="ajout">
        </form>
    </fieldset>

</body>
</html>