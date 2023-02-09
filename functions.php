<?php

/**
 * Récupère tous les enregistrements de la table origins
 */
function getAllOrigin()
{
    // Construction du Data Source Name
    $dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST;

    // Tableau d'options pour la connexion PDO
    $options = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];

    // Création de la connexion PDO (création d'un objet PDO)
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
    $pdo->exec('SET NAMES UTF8');

    $sql = 'SELECT *
            FROM origin
            ORDER BY origine_label';

    $query = $pdo->prepare($sql);
    $query->execute();

    return $query->fetchAll(); }
    

    function getAllInterest()
    
{
    // Construction du Data Source Name
    $dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST;

    // Tableau d'options pour la connexion PDO
    $options = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];

    // Création de la connexion PDO (création d'un objet PDO)
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
    $pdo->exec('SET NAMES UTF8');

    $sql = 'SELECT *
        FROM interest
        ORDER BY interest_label';

    $query = $pdo->prepare($sql);
    $query->execute();
    return $query->fetchAll();
    
}



/**
 * Ajoute un abonné à la liste des emails
 */
function addSubscriber(string $email, string $firstName, string $lastName, int $origines)
{
    // Construction du Data Source Name
    $dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST;

    // Tableau d'options pour la connexion PDO
    $options = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];

    // Création de la connexion PDO (création d'un objet PDO)
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
    $pdo->exec('SET NAMES UTF8');

    // Insertion de l'email dans la table subscribers
    $sql = 'INSERT INTO subscribers
            (email, firstName, lastName, origine_id, createThe) 
            VALUES (?,?,?,?, NOW())';

    $query = $pdo->prepare($sql);
    $query->execute([$email, $firstName, $lastName, $origines]);

    $subscribers_id = $pdo -> lastInsertId();
    return $subscribers_id;


}


function addUserInterests(int $subscribers_id, array $interests)
{
    // Construction du Data Source Name
    $dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST;
  
    // Tableau d'options pour la connexion PDO
    $options = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];

    // Création de la connexion PDO (création d'un objet PDO)
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
    $pdo->exec('SET NAMES UTF8');

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