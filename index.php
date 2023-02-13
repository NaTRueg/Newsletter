<?php


// Inclusion des dépendances
require 'config.php';
require 'functions.php';

$errors = [];
$success = null;
$email = '';
$firstName = '';
$lastName = '';


$conn = mysqli_connect(DB_HOST, DB_USER , DB_PASSWORD , DB_NAME );

// Si le formulaire a été soumis...
if (!empty($_POST)) {

    // On récupère les données
    $email = trim($_POST['email']);
    $firstName = trim($_POST['Firstname']);
    $lastName = trim($_POST['Lastname']);

    $lastName = ucwords(strtolower($lastName));
    $firstName = ucwords(strtolower($firstName));


    // On récupère l'origine
    $originSelected = $_POST['origin'];
    if (isset($_POST['interest'])) {
        $interests = $_POST['interest'];
      } else {
        $interests = [];
      }
    
    // Vérifiez si un abonné existe déjà avec la même adresse email
    $query = "SELECT * FROM subscribers WHERE email = '$email'";

    if (checkEmailExistence($email)) {
    // L'abonné existe déjà, affichez un message d'erreur
        $errors['email'] = "L'adresse email est déjà utilisée. <br> Veuillez en choisir une autre.";
    } 
    
    // Validation 
    if (!$email) {
        $errors['email'] = "Merci d'indiquer une adresse mail";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "L'adresse email n'est pas valide.";
      }

    if (!$firstName) {
        $errors['Firstname'] = "Merci d'indiquer un prénom";
    }

    if (!$lastName) {
        $errors['Lastname'] = "Merci d'indiquer un nom";
    }
    

    if (!isset($interests) || count($interests) == 0) {
        $errors['interests'] = "Merci de choisir au moins un centre d'intérêt.";
    }



    // Si tout est OK (pas d'erreur)
    if (empty($errors)) {

        // Ajout de l'email dans le fichier csv
        
        $subscribers_id = addSubscriber($email, $firstName, $lastName, $originSelected); //$interests);
        addUserInterests($subscribers_id, $interests);

        // Message de succès
        $success  = 'Merci de votre inscription';
        if ($query) {
            header("Location: success.php");
            exit;
          }
    }
}

//////////////////////////////////////////////////////
// AFFICHAGE DU FORMULAIRE ///////////////////////////
//////////////////////////////////////////////////////

// Sélection de la liste des origines
$origines = getAllorigin();
$interestsSelected = getAllInterest();


// Inclusion du template
include 'index.phtml';
