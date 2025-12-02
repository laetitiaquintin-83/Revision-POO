<?php

require_once 'AbstractProduct.php';
require_once 'StockableInterface.php';
require_once 'Clothing.php';
require_once 'Electronic.php';
require_once 'Category.php';

echo "<h1>Job 14 - Interface StockableInterface</h1>";

// ---------------------------------------------------
// TEST 1 : Gestion des stocks sur un Vêtement
// ---------------------------------------------------
echo "<h2>1. Gestion des stocks - Clothing</h2>";

$tshirt = new Clothing(
    0, 1, "T-shirt Stock Test", ["tshirt.jpg"], 1999, "Test des stocks", 50, new DateTime(), new DateTime(),
    "L", "Rouge", "Haut", 100
);

echo "Stock initial : " . $tshirt->getQuantity() . "<br>";

// Ajout de stock
$tshirt->addStocks(20);
echo "Après addStocks(20) : " . $tshirt->getQuantity() . "<br>";

// Retrait de stock
$tshirt->removeStocks(15);
echo "Après removeStocks(15) : " . $tshirt->getQuantity() . "<br>";

// Test du chaînage (fluent interface)
$tshirt->addStocks(10)->removeStocks(5)->addStocks(3);
echo "Après chaînage addStocks(10)->removeStocks(5)->addStocks(3) : " . $tshirt->getQuantity() . "<br>";

// Test : ne pas descendre en dessous de 0
$tshirt->removeStocks(1000);
echo "Après removeStocks(1000) : " . $tshirt->getQuantity() . " (protégé contre les valeurs négatives)<br>";

echo "<hr>";

// ---------------------------------------------------
// TEST 2 : Gestion des stocks sur un Electronic
// ---------------------------------------------------
echo "<h2>2. Gestion des stocks - Electronic</h2>";

$laptop = new Electronic(
    0, 2, "Laptop Stock Test", ["laptop.jpg"], 89900, "Test des stocks électronique", 30, new DateTime(), new DateTime(),
    "Dell", 3000
);

echo "Stock initial : " . $laptop->getQuantity() . "<br>";

$laptop->addStocks(50)->removeStocks(10);
echo "Après addStocks(50)->removeStocks(10) : " . $laptop->getQuantity() . "<br>";

echo "<hr>";

// ---------------------------------------------------
// TEST 3 : Vérifier que les classes implémentent l'interface
// ---------------------------------------------------
echo "<h2>3. Vérification de l'interface</h2>";

if ($tshirt instanceof StockableInterface) {
    echo "✅ Clothing implémente StockableInterface<br>";
}

if ($laptop instanceof StockableInterface) {
    echo "✅ Electronic implémente StockableInterface<br>";
}