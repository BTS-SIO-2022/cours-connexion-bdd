<?php

/*
Pour pouvoir manipuler mes bases de données via PHP, je vais devoir réaliser un certain nombre d'étapes.

ETAPE 1 : Choisir avec quelle extension PHP, je vais me connecter

- extension PHP PDO (PHP DATA OBJECT)
https://www.php.net/manual/fr/book.pdo.php

- extension PHP MySQLi
https://www.php.net/manual/fr/book.mysqli.php

Attention, il existe aussi l'extension PHP MySQL mais elle est aujourd'hui dépréciée et dangereuse à utiliser.

Dans les 2 cas (MySQLi et PDO), je vais avoir besoin des "mêmes informations" pour me connecter, à savoir :
    1) le serveur de la base de données 
    2) le user de connexion à la base de données
    3) le nom de la base de données
    4) le mot de passe de la base données
    Dans le cas de PDO, je vais avoir besoin d'une informations en plus qui est : 
        - le type de moteur de la base de données


        Exemple de connexion à ma BDD avec mysqli
*/
//1) le serveur de la base de données 
$servername = "mysql";
//2) le user de connexion à la base de données
$username = "sandbox";
//3) le nom de la base de données
$dbname = "sandbox";
//4) le mot de passe de la base données
$password = "sandbox";

// Créer une connexion à la BDD
$connexion = new mysqli($servername, $username, $password, $dbname);

if($connexion->connect_error){
    die("La connexion n'a pas fonctionné car".$connexion->connect_error);
}else{
    echo "Je suis bien connecté à la BDD, bravo !";
}


$sql = "SELECT * from article";

$result = $connexion->query($sql);

var_dump($result);
echo '<br>';
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        echo '<br>';
        echo $row['id'];
        echo '<br>';
        echo $row['title'];
        echo '<br>';
        echo $row['content'];
        echo '<br>';
        echo $row['author'];
    }
}else{
    echo 'Je n\'ai trouvé aucun résultat';
}

$connexion->close();


/* 
    Exemple de connexion et de récupération des informations contenues dans ma BDD avec cette fois-ci l'extension PDO
*/
$dbname = "sandbox";
//   1) DSN
$dsn = "mysql:host=mysql;dbname=".$dbname;
//    2) le user de connexion à la base de données
$username = "sandbox";
//    4) le mot de passe de la base données
$password = "sandbox";

// Créer une connexion avec PDO

try{
    $connexionPDO = new PDO($dsn, $username, $password);
    echo 'La connexion s\'est bien passée';
} catch (PDOException $e) {
    print "Erreur !: $e->getMessage() <br/>";
    die();
}

$sql = "SELECT * from article";

$results = $connexionPDO->query($sql);
echo '<br>';
//var_dump($results);
echo '<br>';
$mesdonnees = $results->fetchAll(PDO::FETCH_ASSOC);

foreach($mesdonnees as $montableau) {
    //var_dump($montableau);
    echo '<br>';
    echo $montableau['id'];
    echo '<br>';
    echo $montableau['title'];
    echo '<br>';
    echo $montableau['content'];
    echo '<br>';
    echo $montableau['author'];
}

//var_dump($mesdonnees);




;?>