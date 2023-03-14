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

// ETAPE 2 : J'ai choisi l'extension Mysqli, je créé une connexion avec ma base de données, pour cela, j'instancie un objet de type mysqli et je passe en paramètres au constructeur de mon objet mysqli, le nom ou l'adresse du serveur sur lequel se trouve ma BDD, le nom de l'utilisateur ayant accès à cette base de donnée, son mot de passe et le nom de la base de données à laquelle je souhaite accéder.
// Créer une connexion à la BDD
$connexion = new mysqli($servername, $username, $password, $dbname);

// connect_error est une propriété de mon objet mysqli me permettant d'afficher s'il y a une erreur déclenchée lors de la connexion. S'il y a une erreur j'arrête mon script et j'affiche l'erreur, sinon j'affiche un message comme quoi tout s'est bien passé.
if($connexion->connect_error){
    die("La connexion n'a pas fonctionné car".$connexion->connect_error);
}else{
    echo "Je suis bien connecté à la BDD, bravo !";
}

/* ETAPE 3 : je veux accéder et faire des opérations sur mes données en base de données = je veux développer des composants d'accès aux données = je veux réaliser des opérations de CRUD sur ma base de données
exemple ici : je veux faire un READ, c'est-à-dire je veux accéder en lecture à mes données.

Je prépare ma requête SQL et sur mon objet mysqli, j'appelle la méthode query à laquelle je transmets en paramètre ma requête SQL.
*/

$sql = "SELECT * from article";
$result = $connexion->query($sql);

// Je stocke dans la variable result, le retour de ma méthode query()
var_dump($result);
// ma variable result stocke un objet de type mysqli_result sur lequel je vais appeler la méthode fetch_assoc qui me permet de récupérer l'intégralité des données contenus dans chacune des lignes de ma base de données et de les récupérer sous la forme d'un tableau associatif dans lequel mes clés correspondent aux champs de ma table et mes valeurs, aux valeurs contenus dans ma table.
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


// ETAPE 4 : je ferme la connexion à ma base de données car je n'en ai plus besoin
$connexion->close();


/* 
    Exemple de connexion et de récupération des informations contenues dans ma BDD avec cette fois-ci l'extension PDO
*/

/* ETAPE 2 (bis) : Cette fois-çi, je fais la même chose mais avec l'extension PDO.
j'ai les mêmes infos que vu précédemment, la différence est le DSN. Le DSN est une chaîne de caractère qui contient le type (ou pilote) de moteur de ma base de données (nous c'est mysql), ensuite l'host = le serveur de ma base de données(nom ou adresse), ensuite le nom de ma base données sous le format dbname=nomdemaBDD
*/

$dbname = "sandbox";
//   1) DSN
$dsn = "mysql:host=mysql;dbname=".$dbname;
//    2) le user de connexion à la base de données
$username = "sandbox";
//    4) le mot de passe de la base données
$password = "sandbox";

/* Créer une connexion avec PDO
De préférence, avec PDO on utilise une structure de contrôle de type try/catch. Le try essaie de faire la connexion et si jamais ça foire, le catch se déclenche. 
Pour créer la connexion, j'instancie un objet de type PDO auquel je transmets les paramètres vus plus haut à son constructeur. 
PDOException est une classe qui me permet de récupérer les erreurs qui pourraient apparaître à la connexion
*/

try{
    $connexionPDO = new PDO($dsn, $username, $password);
    echo 'La connexion s\'est bien passée';
} catch (PDOException $e) {
    print "Erreur !: $e->getMessage() <br/>";
    die();
}

/* 
Je veux faire des opérations de CRUD sur ma BDD.
Je prépare ma requête et j'utilise la méthode query avec en paramètre ma requête sur l'objet PDO
*/
$sql = "SELECT * from article";
// je stocke le retour de ma méthode query dans une variable $results qui elle est un objet de type PDOStatement. Dans mon objet PDOStatement, j'ai le nombre de lignes affectées par ma requête.
$results = $connexionPDO->query($sql);
echo '<br>';
//var_dump($results);
echo '<br>';

/* Sur mon objet PDOStatement, je vais pouvoir récupérer les résultats de la requête grâce aux méthodes fetch ou fetchAll.
La différence entre fetch et fetchAll est que fetch me renvoie les résultats ligne par ligne tandis que fetchAll me renvoie tous les résultats d'un coup. Si j'ai beaucoup de données à récupérer, j'utilise fetch car ça consomme moins de mémoire.

Fetch et fetchAll peuvent prendre différents paramètres (comme par exemple ci-dessous PDO::FETCH_ASSOC) qui me permettent de contrôler sous quelle forme sont retournés mes résultats (ici je les récupère grâce à PDO::FETCH_ASSOC sous la forme d'un tableau associatif où la clé correspond au champ dans ma table et la valeur à la valeur contenue dans ma table);
Lien vers la documentation de tous les paramètres que peuvent prendre fetch et fetchAll : https://www.php.net/manual/fr/pdostatement.fetch.php

*/
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

/* 
MAINTENANT, on a envie de faire des requêtes en modification dans sa base données Donc soit des INSERT, des DELETE, ou bien des UPDATE
*/

$sql = "INSERT INTO article (`title`, `content`, `author`, `date`, `category`) VALUES ('Mon chien est débile, que faire ?', 'tralalalal lalala', 'Mathilde Renversade', '2023-03-14', 'Conseil')";

$results_modif = $connexionPDO->exec($sql);

var_dump($results_modif);
echo '<br>';

$title = 'Je suis un chien gentil';
$author = "Manon des Sources";
$content = "ludofififif fifififif";
$category = "Agility";
$date = "2023-03-13";

$sql = "INSERT into article (`title`, `content`, `author`, `date`, `category`) VALUES ('$title', '{$content}', '$author', '$date', '$category');";

$results_modif_deux = $connexionPDO->exec($sql);

var_dump($results_modif_deux);
echo '<br>';

$sql = "DELETE FROM article WHERE id=2";

$results_suppression = $connexionPDO->exec($sql);

// Attention, bien penser à intégrer dans son code, la gestion des erreurs, c'est-à-dire que se passe-t-il si jamais le retour de ma méthode exec ou query est égal à 0 ? 
var_dump($results_suppression);

/*
LES REQUETES PREPAREES

Dès lors que je manipule des données qui m'ont été fournies par un utilisateur (via un formulaire par exemple) et que je veux les utiliser dans ma base de données, j'utilise les requêtes préparées. Elles sont un moyen de sécuriser mon code et de ne pas subir d'injection SQL

Exemple de mise en place d'une requête préparée
*/

$title = 'Je suis un titre écrit par un utilisateur';
$content = "Mon contenu venant d'un input";
$author = 'Utilisateur connecté';
$date = '2023-03-14';
$category = 'Soin';

$sql = "INSERT into article (`title`, `content`, `author`, `date`, `category`) VALUES (:title, :content, :author, '2023-03-14', 'Loisirs');";

$result_prepare = $connexionPDO->prepare($sql);
$result_prepare->bindParam(':title', $title);
$result_prepare->bindParam(':content', $content);
$result_prepare->bindParam(':author', $author);
$result_prepare_done = $result_prepare->execute();

echo '<br>';
var_dump($result_prepare_done);

/* RECAPITULATIF

Pour me connecter à une base de données et développer mes composants d'accès aux données, je peux utiliser via la programmation orientée objet 2 classe : PDO et MySQLI

Pour créer la connexion, j'instancie mon objet et je lui passe en paramètres les "moyens" de connexion : l'adresse du serveur de base données, (parfois le port, exemple pour un serveur mysql c'est souvent 3306 et pour mariabd c'est souvent 3308), le nom de la BDD, le user de la BDD, du mot de passe de ce user, et dans le cas de PDO, on a aussi besoin du type de moteur de BDD (mysql, postgred, etc)

- Si je veux LIRE les données contenues dans ma BDD, j'utilise la méthode query(). Si je veux MODIFIER ma BDD, j'utilise la méthode exec()

- Une fois query() ou exec() exécuté, je récupère un autre type d'objet (PDOStatement, mysqli_result), sorte de "boîte de résultats".

- C'est sur cette "boîte de résultats" que j'utilise les méthodes fetch ou fetchAll (en fonction du nombre de résultats que je veux récupérer)
Et via les paramètres de ces 2 méthodes, je peux préciser le format dans lequel je veux ces résultats. 

- Dans tous les cas, où j'exécute des requêtes SQL dont des valeurs sont issues d'inputs utilisateurs, j'utilise les requêtes préparées et donc les méthodes
1) prepare()
2) bindValue() / bindParam()
3) execute()
NEVER TRUST THE USER INPUT 
Les requêtes préparées nous protègent des injections SQL.
*/
;?>