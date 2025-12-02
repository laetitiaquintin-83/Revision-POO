<?php

require_once 'Product.php';
require_once 'Category.php';

echo "<h1>Job 10 - Méthode update()</h1>";

// 1. Récupérer un produit existant (ID 7)
echo "<h2>Récupération du produit ID 7 :</h2>";

$product = new Product();
$product->findOneById(7);

echo "Avant modification :<br>";
echo "Nom : " . $product->getName() . "<br>";
echo "Prix : " . $product->getPrice() . " centimes<br>";
echo "Quantité : " . $product->getQuantity() . "<br>";

// 2. Modification des propriétés via les setters
echo "<hr>";
echo "<h2>Modification du produit :</h2>";

$product->setName("Pull Over Modifié");
$product->setPrice(4999);
$product->setQuantity(30);

// 3. Sauvegarde avec update()
$result = $product->update();

if ($result) {
    echo "<p style='color:green'>✅ Produit mis à jour avec succès !</p>";
    echo "Après modification :<br>";
    echo "Nom : " . $product->getName() . "<br>";
    echo "Prix : " . $product->getPrice() . " centimes<br>";
    echo "Quantité : " . $product->getQuantity() . "<br>";
    echo "Date de mise à jour : " . $product->getUpdatedAt()->format('Y-m-d H:i:s') . "<br>";
} else {
    echo "<p style='color:red'>❌ Erreur lors de la mise à jour.</p>";
}

// 4. Vérification en re-récupérant depuis la BDD
echo "<hr>";
echo "<h2>Vérification depuis la BDD :</h2>";

$productVerif = new Product();
$productVerif->findOneById(7);

echo "Nom en BDD : " . $productVerif->getName() . "<br>";
echo "Prix en BDD : " . $productVerif->getPrice() . " centimes<br>";
echo "Quantité en BDD : " . $productVerif->getQuantity() . "<br>";