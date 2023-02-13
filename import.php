<?php


require 'config.php';
require 'functions.php';
$pdo = getPdoConnection();
$filename = $argv[1];

if (!file_exists($filename)) {
    echo "Erreur : fichier '$filename' introuvable";
    exit; // On arrête l'exécution du script
}

$file = fopen($filename, "r");
$row = fgetcsv($file);
$email = $row[2];

if (checkEmailExistence($pdo, $email)) {
    echo "L'email $email est déjà utilisé. Veuillez vérifier le fichier CSV.";
    exit;
}

$pdoStatement = $pdo->prepare('INSERT INTO subscribers (firstName , lastName, email) VALUES (?,?,?)');
while ($row = fgetcsv($file)) {
    $firstName = ucfirst($row[0]);
    $lastName = ucfirst($row[1]);
    $email = $row[2];
    $pdoStatement->execute([$firstName, $lastName,$email]);
}

echo 'Import terminé!';

    