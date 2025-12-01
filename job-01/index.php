<?php

require_once 'Product.php';

// Création d'une instance de Product
$product = new Product(
    1,
    "T-shirt Noir",
    ["photo1.jpg", "photo2.jpg"],
    2999,
    "Un super t-shirt noir en coton",
    50,
    new DateTime(),
    new DateTime()
);

// Test des getters
echo "<h1>Job 01 - Test de la classe Product</h1>";

echo "<h2>Valeurs initiales :</h2>";
var_dump($product);

echo "<h2>Test des getters :</h2>";
echo "ID : " . $product->getId() . "<br>";
echo "Nom : " . $product->getName() . "<br>";
echo "Prix : " . $product->getPrice() . " centimes<br>";
echo "Description : " . $product->getDescription() . "<br>";
echo "Quantité : " . $product->getQuantity() . "<br>";

// Test des setters
echo "<h2>Après modification avec les setters :</h2>";
$product->setName("T-shirt Blanc");
$product->setPrice(3499);
$product->setQuantity(100);

echo "Nouveau nom : " . $product->getName() . "<br>";
echo "Nouveau prix : " . $product->getPrice() . " centimes<br>";
echo "Nouvelle quantité : " . $product->getQuantity() . "<br>";
