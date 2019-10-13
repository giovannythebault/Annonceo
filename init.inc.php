<?php
    // connexion BDD
$host_db = 'mysql:host=localhost;dbname=annonceo';
$user = 'root';
$password = '';
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, 
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
$pdo = new PDO($host_db, $user, $password,$options);

// variable destinée à afficher des messages utilisateur
$msg = '';

// ouverture d'une session
session_start();

// appel de nos fonctions
include_once('fonction.inc.php'); // once verifie si le fichier n'a pas déja été appeller

// création d'une constante représentant la racine site (l'url absolue)
define('URL', 'http://localhost/Annonceo/'); // changer l'url lors d"un changement de seveur

// cération d'une constante dymanique pour la copie des photos (fichiers média) du formulaire sur gestion_produit.php
define('RACINE_SERVEUR', $_SERVER['DOCUMENT_ROOT'] . '/Annonceo/');








// 1 etape = modélisation de la base
// 2 = arboresence du dossier
// 3 = création du template
// 4 = découpes des fichiers (include)
// 5 = fichier init (connexion BDD)