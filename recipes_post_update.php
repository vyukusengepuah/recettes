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

// 2. Vérifier que les données sont valides
if (
    !isset($postData['id'])
    || !is_numeric($postData['id'])
    || empty($postData['title'])
    || empty($postData['recipe'])
    || trim(strip_tags($postData['title'])) === ''
    || trim(strip_tags($postData['recipe'])) === ''
) {
    echo 'Il manque des informations pour permettre l\'édition du formulaire.';
    return;
}

$id = (int)$postData['id'];
$title = trim(strip_tags($postData['title']));
$recipe = trim(strip_tags($postData['recipe']));

// 3. Récupérer la recette pour vérifier l'auteur
$sql = 'SELECT * FROM recipes WHERE recipe_id = :id';
$stmt = $mysqlClient->prepare($sql);
$stmt->execute([':id' => $id]);
$recipe_data = $stmt->fetch();

// 4. Vérifier que la recette existe
if ($recipe_data === false) {
    echo 'Cette recette n\'existe pas.';
    return;
}

// 5. ⭐ VÉRIFICATION CRITIQUE : l'utilisateur est-il l'auteur ?
if ($recipe_data['author'] !== $_SESSION['LOGGED_USER']['email']) {
    echo 'Vous n\'êtes pas autorisé à modifier cette recette.';
    return;
}

// 6. Mettre à jour
$updateRecipeStatement = $mysqlClient->prepare('UPDATE recipes SET title = :title, recipe = :recipe WHERE recipe_id = :id');
$updateRecipeStatement->execute([
    ':title' => $title,
    ':recipe' => $recipe,
    ':id' => $id,
]);

// 7. Rediriger
redirectToUrl('index.php');