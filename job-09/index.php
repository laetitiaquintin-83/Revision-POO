<?php

require_once 'Product.php';
require_once 'Category.php';

echo "<h1>Job 09 - Méthode create()</h1>";

// 1. Test de create() - Créer un nouveau produit
echo "<h2>Création d'un nouveau produit :</h2>";

$newProduct = new Product(
    0,                      // ID = 0 car il sera auto-généré
    1,                      // category_id = 1 (Vêtements)
    "Nouveau T-shirt Test",
    ["nouveau_tshirt.jpg"],
    2499,
    "Un nouveau t-shirt créé via la méthode create()",
    75,
    new DateTime(),
    new DateTime()
);

echo "Avant insertion - ID : " . $newProduct->getId() . "<br>";

$result = $newProduct->create();

if ($result) {
    echo "<p style='color:green'>✅ Produit créé avec succès !</p>";
    echo "Après insertion - ID : " . $newProduct->getId() . "<br>";
    echo "Nom : " . $newProduct->getName() . "<br>";
    echo "Prix : " . $newProduct->getPrice() . " centimes<br>";
} else {
    echo "<p style='color:red'>❌ Erreur lors de la création du produit.</p>";
}

// 2. Vérification avec findAll()
echo "<hr>";
echo "<h2>Liste de tous les produits (après création) :</h2>";

$productInstance = new Product();
$allProducts = $productInstance->findAll();

echo "<ul>";
foreach ($allProducts as $p) {
    echo "<li>";
    echo "<strong>" . $p->getName() . "</strong> (ID: " . $p->getId() . ") - " . $p->getPrice() . " centimes";
    echo "</li>";
}
echo "</ul>";