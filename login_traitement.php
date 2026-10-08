<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Pour l'instant, on simule (plus tard on utilisera la base de données)
    $email_valide = 'mukamana@exemple.com';
    $password_valide = '1234';
    
    if ($email === $email_valide && $password === $password_valide) {
        // Stocker en session
        $_SESSION['umukoresha'] = 'Mukamana';
        $_SESSION['email'] = $email;
        $_SESSION['iyinjira'] = true;
        
        // Rediriger vers l'accueil
        header('Location: index.php');
        exit();
    } else {
        echo "<p>Email ou mot de passe incorrect.</p>";
        echo "<p><a href='login.php'>Réessayer</a></p>";
    }
} else {
    echo "<p>Accès non autorisé.</p>";
    echo "<p><a href='login.php'>Retour</a></p>";
}
?>