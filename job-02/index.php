<?php

require_once 'Product.php';
require_once 'Category.php';

echo "<h1>Job 02 - Test des classes Product et Category</h1>";

// Test de la classe Category
$category = new Category(
    1,
    "Vêtements",
    "Tous nos vêtements",
    new DateTime(),
    new DateTime()
);

echo "<h2>Catégorie :</h2>";
var_dump($category);

// Test de la classe Product avec category_id
$product = new Product(
    1,
    1, // category_id = 1 (Vêtements)
    "T-shirt Noir",
    ["photo1.jpg", "photo2.jpg"],
    2999,
    "Un super t-shirt noir en coton",
    50,
    new DateTime(),
    new DateTime()
);

echo "<h2>Produit avec category_id :</h2>";
var_dump($product);

echo "<h2>Vérification du category_id :</h2>";
echo "Category ID du produit : " . $product->getCategoryId() . "<br>";
