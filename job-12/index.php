<?php

require_once 'Clothing.php';
require_once 'Electronic.php';
require_once 'Category.php'; // Utile si tu veux vérifier les liaisons

echo "<h1>Tests des classes Héritées</h1>";

// ---------------------------------------------------
// TEST 1 : Création et Sauvegarde d'un Vêtement
// ---------------------------------------------------
echo "<h2>1. Création d'un Vêtement</h2>";
$jean = new Clothing(
    0, 1, "Jean Slim", ["jean.jpg"], 40, "Un super jean", 50, new DateTime(), new DateTime(),
    "M", "Bleu", "Pantalon", 2 // Taille M, Bleu, Pantalon, Frais 2€
);

if ($jean->create()) {
    echo "✅ Jean créé avec l'ID : " . $jean->getId() . "<br>";
} else {
    echo "❌ Erreur création Jean.<br>";
}

// ---------------------------------------------------
// TEST 2 : Création et Sauvegarde d'un Électronique
// ---------------------------------------------------
echo "<h2>2. Création d'un appareil Électronique</h2>";
$iphone = new Electronic(
    0, 2, "Smartphone X", ["phone.jpg"], 999, "Dernier modèle", 10, new DateTime(), new DateTime(),
    "Apple", 50 // Marque Apple, Garantie 50€
);

if ($iphone->create()) {
    echo "✅ Smartphone créé avec l'ID : " . $iphone->getId() . "<br>";
} else {
    echo "❌ Erreur création Smartphone.<br>";
}

echo "<hr>";

// ---------------------------------------------------
// TEST 3 : Récupération et Modification (Clothing)
// ---------------------------------------------------
echo "<h2>3. Récupération et Modification (Vêtement)</h2>";

// On simule qu'on veut récupérer le jean qu'on vient de créer
// (Utilise un ID existant si tu as relancé la page plusieurs fois, sinon utilise $jean->getId())
$idToFind = $jean->getId(); 

$monVetement = new Clothing();
$trouve = $monVetement->findOneById($idToFind);

if ($trouve) {
    echo "Produit trouvé : <strong>" . $monVetement->getName() . "</strong><br>";
    echo "Taille : " . $monVetement->getSize() . " (Spécifique Clothing)<br>";
    
    // Modification
    $monVetement->setColor("Noir"); // On change la couleur
    $monVetement->update();
    echo "Couleur modifiée en Noir et sauvegardée !<br>";
} else {
    echo "Produit introuvable.";
}

echo "<hr>";

// ---------------------------------------------------
// TEST 4 : Lister tous les produits Électroniques
// ---------------------------------------------------
echo "<h2>4. Liste de tous les produits Électroniques</h2>";
$managerElectro = new Electronic();
$allElectro = $managerElectro->findAll();

if (empty($allElectro)) {
    echo "Aucun produit électronique trouvé.";
} else {
    echo "<ul>";
    foreach ($allElectro as $e) {
        echo "<li>ID: " . $e->getId() . " - " . $e->getName() . " (Marque : " . $e->getBrand() . ")</li>";
    }
    echo "</ul>";
}