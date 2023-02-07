<?php


// Inclusion des dépendances
require 'config.php';
require 'functions.php';

$errors = [];
$success = null;
$email = '';
$firstName = '';
$lastName = '';

// Si le formulaire a été soumis...
if (!empty($_POST)) {

    // On récupère les données
    $email = trim($_POST['email']);
    $firstName = trim($_POST['prenom']);
    $lastName = trim($_POST['nom']);


    // On récupère l'origine
    $originSelected = $_POST['origin'];

    // Validation 
    if (!$email) {
        $errors['email'] = "Merci d'indiquer une adresse mail";
    }

    if (!$firstName) {
        $errors['prenom'] = "Merci d'indiquer un prénom";
    }

    if (!$lastName) {
        $errors['nom'] = "Merci d'indiquer un nom";
    }

    // Si tout est OK (pas d'erreur)
    if (empty($errors)) {

        // Ajout de l'email dans le fichier csv
        addSubscriber($email, $firstName, $lastName, $originSelected);

        // Message de succès
        $success  = 'Merci de votre inscription';
    }
}

//////////////////////////////////////////////////////
// AFFICHAGE DU FORMULAIRE ///////////////////////////
//////////////////////////////////////////////////////

// Sélection de la liste des origines
$origines = getAllorigin();

// Inclusion du template
include 'index.phtml';
