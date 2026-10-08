<?php
require_once(__DIR__ . '/isConnect.php');

// Vérifier que recipe_id est présent dans l'URL
if (!isset($_GET['recipe_id']) || empty($_GET['recipe_id'])) {
    echo "Erreur : Aucune recette spécifiée.";
    exit();
}

// Récupérer l'ID
$recipe_id = $_GET['recipe_id'];
?>

<form action="comments_post_create.php" method="POST">
    <div class="mb-3 visually-hidden">
        <input class="form-control" type="text" name="recipe_id" value="<?php echo htmlspecialchars($recipe_id); ?>" />
    </div>
    
    <div class="mb-3">
        <label for="comment" class="form-label">Postez un commentaire</label>
        <textarea class="form-control" placeholder="Soyez respectueux/se, nous sommes humain(e)s." id="comment" name="comment"></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Envoyer</button>
</form>