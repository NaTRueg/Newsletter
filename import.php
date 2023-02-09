<?php

require 'config.php';

$filename = $argv[1];

if (!file_exists($filename)) {
    echo "Erreur : fichier '$filename' introuvable";
    exit; // On arrête l'exécution du script
}

$file = fopen($filename, "r");

$pdo = getPdoConnection();
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdoStatement = $pdo->prepare('INSERT INTO subscribers (firstName , lastName, email,interests) VALUES (?,?,?,?)');


while ($row = fgetcsv($file)) {

   
    $firstName = ucfirst($row[0]);
    $lastName = ucfirst($row[1]);
    $email = $row[2];
    $interests = $row[3];


  
    $pdoStatement->execute([$firstName, $lastName,$email,$interests]);
}

echo 'Import terminé!';


    