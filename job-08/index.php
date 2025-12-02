<?php

require_once 'Product.php';
require_once 'Category.php';

echo "<h1>Job 08 - Méthode findAll()</h1>";

// 1. Création d'une instance vide de Product
$product = new Product();

// 2. Utilisation de findOneById() pour hydrater l'instance
echo "<h2>Recherche du produit ID 7 :</h2>";
$result = $product->findOneById(7);

if ($result) {
    echo "Produit trouvé !<br><br>";
    echo "ID : " . $product->getId() . "<br>";
    echo "Nom : " . $product->getName() . "<br>";
    echo "Prix : " . $product->getPrice() . " centimes<br>";
    echo "Description : " . $product->getDescription() . "<br>";
    echo "Quantité : " . $product->getQuantity() . "<br>";

    // On peut aussi utiliser getCategory() sur ce produit
    echo "<h3>Catégorie associée :</h3>";
    $category = $product->getCategory();
    echo "Catégorie : " . $category->getName() . "<br>";
} else {
    echo "<p style='color:red'>Produit non trouvé.</p>";
}

// 3. Test avec un ID qui n'existe pas
echo "<hr>";
echo "<h2>Recherche d'un produit inexistant (ID 999) :</h2>";
$product2 = new Product();
$result2 = $product2->findOneById(999);

if ($result2 === false) {
    echo "<p style='color:orange'>Produit ID 999 non trouvé (retourne false).</p>";
}

// 4. Affichage complet
echo "<hr>";
echo "<h2>var_dump du produit ID 7 :</h2>";
var_dump($product);

// 5. Test de findAll() - Récupérer tous les produits
echo "<hr>";
echo "<h1>Test de findAll()</h1>";

$productInstance = new Product();
$allProducts = $productInstance->findAll();

echo "<h2>Liste de tous les produits (" . count($allProducts) . " produits) :</h2>";
echo "<ul>";
foreach ($allProducts as $p) {
    echo "<li>";
    echo "<strong>" . $p->getName() . "</strong> - " . $p->getPrice() . " centimes (Quantité: " . $p->getQuantity() . ")";
    echo "</li>";
}
echo "</ul>";

echo "<h2>var_dump de tous les produits :</h2>";
var_dump($allProducts);