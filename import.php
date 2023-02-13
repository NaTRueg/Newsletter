<?php

require 'functions.php';
require 'config.php';

$filename = $argv[1];

if (!file_exists($filename)) {
    echo "Erreur : fichier '$filename' introuvable";
    exit; // On arrête l'exécution du script
}

$file = fopen($filename, "r");

$pdo = getPdoConnection();

$email = $row[2];

$checkStatement = $pdo->prepare("SELECT * FROM subscribers WHERE email = ?");
$checkStatement->execute([$email]);

if ($checkStatement->rowCount() > 0) {
    echo "L'email $email est déjà utilisé. Veuillez vérifier le fichier CSV.";
    exit;
}


$pdoStatement = $pdo->prepare('INSERT INTO subscribers (firstName , lastName, email,interests) VALUES (?,?,?,?)');


while ($row = fgetcsv($file)) {

   
    $firstName = ucfirst($row[0]);
    $lastName = ucfirst($row[1]);
    $email = $row[2];
    $interests = $row[3];


  
    $pdoStatement->execute([$firstName, $lastName,$email,$interests]);
}

echo 'Import terminé!';


    