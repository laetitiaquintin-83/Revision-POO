<?php

require_once 'vendor/autoload.php';

use App\Clothing;
use App\Electronic;
use App\Category;

echo "<h1>Job 15 - Autoloading avec Composer et Namespaces</h1>";

echo "<h2>Structure des dossiers</h2>";
echo "<pre>";
echo "job-15/
├── composer.json
├── index.php
├── vendor/
│   └── autoload.php
└── src/
    ├── Abstract/
    │   └── AbstractProduct.php   (namespace App\\Abstract)
    ├── Interface/
    │   └── StockableInterface.php (namespace App\\Interface)
    ├── Category.php              (namespace App)
    ├── Clothing.php              (namespace App)
    └── Electronic.php            (namespace App)
";
echo "</pre>";

echo "<hr>";

echo "<h2>1. Test de création d'un vêtement (Clothing)</h2>";
$jean = new Clothing(
    name: "Jean Composer",
    category_id: 1,
    price: 5999,
    description: "Un jean créé via autoloader Composer",
    quantity: 25,
    size: "L",
    color: "Indigo",
    type: "Pantalon",
    material_fee: 200
);

$result = $jean->create();
if ($result) {
    echo "✅ Vêtement créé avec l'ID : " . $jean->getId() . "<br>";
} else {
    echo "❌ Erreur lors de la création du vêtement<br>";
}

echo "<h2>2. Test de création d'un appareil électronique (Electronic)</h2>";
$laptop = new Electronic(
    name: "Laptop Composer",
    category_id: 2,
    price: 129900,
    description: "Un laptop créé via autoloader Composer",
    quantity: 10,
    brand: "Dell",
    waranty_fee: 1500
);

$result = $laptop->create();
if ($result) {
    echo "✅ Électronique créé avec l'ID : " . $laptop->getId() . "<br>";
} else {
    echo "❌ Erreur lors de la création de l'électronique<br>";
}

echo "<hr>";

echo "<h2>3. Test de StockableInterface</h2>";
echo "Stock initial laptop : " . $laptop->getQuantity() . "<br>";
$laptop->addStocks(5)->removeStocks(2);
echo "Après addStocks(5)->removeStocks(2) : " . $laptop->getQuantity() . "<br>";

echo "<hr>";

echo "<h2>4. Liste des vêtements (findAll)</h2>";
$clothingFinder = new Clothing();
$allClothes = $clothingFinder->findAll();
echo "<ul>";
foreach ($allClothes as $item) {
    echo "<li><strong>" . $item->getName() . "</strong> - Taille: " . $item->getSize() . " - Couleur: " . $item->getColor() . "</li>";
}
echo "</ul>";

echo "<h2>5. Liste des électroniques (findAll)</h2>";
$electronicFinder = new Electronic();
$allElectronics = $electronicFinder->findAll();
echo "<ul>";
foreach ($allElectronics as $item) {
    echo "<li><strong>" . $item->getName() . "</strong> - Marque: " . $item->getBrand() . "</li>";
}
echo "</ul>";

echo "<hr>";
echo "<p style='color: green; font-weight: bold;'>✅ Autoloading Composer fonctionnel avec les namespaces App !</p>";
