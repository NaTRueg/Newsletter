<?php

// Fonction création de la connexion PDO

function getPdoConnection()
{
    $dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST;
    $options = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
    $pdo->exec('SET NAMES UTF8');
    return $pdo;
}



/**
 * Récupère tous les enregistrements de la table origins
 */

function getAllOrigin()
{


    // Création de la connexion PDO (création d'un objet PDO)
    $pdo = getPdoConnection();
    
    $sql = 'SELECT *
            FROM origin
            ORDER BY origine_label';

    $query = $pdo->prepare($sql);
    $query->execute();

    return $query->fetchAll(); }
    

    function getAllInterest()
    
{
 
    // Création de la connexion PDO (création d'un objet PDO)
    $pdo = getPdoConnection();

    $sql = 'SELECT *
        FROM interest
        ORDER BY interest_label';

    $query = $pdo->prepare($sql);
    $query->execute();
    return $query->fetchAll();
    
}

function checkEmailExistence($pdo, $email)
{
    $sql = 'SELECT COUNT(*) FROM subscribers WHERE email = ?';

    $query = $pdo->prepare($sql);
    $query->execute([$email]);

    return $query->fetchColumn() > 0;
}


/**
 * Ajoute un abonné à la liste des emails
 */
function addSubscriber(string $email, string $firstName, string $lastName, int $origines)
{
    $pdo = getPdoConnection();

    $sql = 'INSERT INTO subscribers
            (email, firstName, lastName, origine_id, createThe) 
            VALUES (?,?,?,?, NOW())';

    $query = $pdo->prepare($sql);

    $query->execute([$email, $firstName, $lastName, $origines]);

    $subscribers_id = $pdo->lastInsertId();
    
    return $subscribers_id;
}


function addUserInterests(int $subscribers_id, array $interests)
{
  

    // Création de la connexion PDO (création d'un objet PDO)
    $pdo = getPdoConnection();

    // Boucle à travers les centres d'intérêt sélectionnés
    foreach ($interests as $interest_id) {
        

        // Préparation de la requête d'insertion
        $query = $pdo->prepare("INSERT INTO subscriber_interest (subscribers_id, interest_id) VALUES (?,?)");

        // Liaison des paramètres
        $query->bindParam('subscribers_id', $subscribers_id);
        $query->bindParam('interest_id', $interest_id);

        // Exécution de la requête
        $query->execute([$subscribers_id , $interest_id ]);
    }
}