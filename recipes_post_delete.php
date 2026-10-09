<?php

session_start();

require_once(__DIR__ . '/isConnect.php');
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/functions.php');

/**
 * On ne traite pas les super globales provenant de l'utilisateur directement,
 * ces données doivent être testées et vérifiées.
 */
$postData = $_POST;

// 1. Vérifier que la méthode est bien POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'Méthode non autorisée.';
    return;
}

// 2. Vérifier que l'ID est valide
if (!isset($postData['id']) || !is_numeric($postData['id'])) {
    echo 'Il faut un identifiant valide pour supprimer une recette.';
    return;
}

$id = (int)$postData['id'];

// 3. Récupérer la recette pour vérifier l'auteur
$sql = 'SELECT * FROM recipes WHERE recipe_id = :id';
$stmt = $mysqlClient->prepare($sql);
$stmt->execute([':id' => $id]);
$recipe = $stmt->fetch();

// 4. Vérifier que la recette existe
if ($recipe === false) {
    echo 'Cette recette n\'existe pas.';
    return;
}

// 5. ⭐ VÉRIFICATION CRITIQUE : l'utilisateur est-il l'auteur ?
if ($recipe['author'] !== $_SESSION['LOGGED_USER']['email']) {
    echo 'Vous n\'êtes pas autorisé à supprimer cette recette.';
    return;
}

// 6. Supprimer
$deleteRecipeStatement = $mysqlClient->prepare('DELETE FROM recipes WHERE recipe_id = :id');
$deleteRecipeStatement->execute([
    ':id' => $id,
]);

// 7. Rediriger
redirectToUrl('index.php');