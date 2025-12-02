<?php

require_once 'AbstractProduct.php';
require_once 'Clothing.php';
require_once 'Electronic.php';
require_once 'Category.php';

echo "<h1>Job 13 - Classe AbstractProduct</h1>";

// ---------------------------------------------------
// TEST 0 : On ne peut PAS instancier AbstractProduct
// ---------------------------------------------------
echo "<h2>0. Test de la classe abstraite</h2>";
echo "<p>La classe <code>AbstractProduct</code> ne peut pas être instanciée directement.</p>";
echo "<p>Essayer <code>new AbstractProduct()</code> provoquerait une erreur fatale :</p>";
echo "<pre style='color:red'>Cannot instantiate abstract class AbstractProduct</pre>";

// ---------------------------------------------------
// TEST 1 : Création d'un Vêtement (via classe concrète)
// ---------------------------------------------------
echo "<h2>1. Création d'un Vêtement (classe Clothing)</h2>";
$jean = new Clothing(
    0, 1, "Jean Slim Abstrait", ["jean.jpg"], 4500, "Un super jean via AbstractProduct", 50, new DateTime(), new DateTime(),
    "M", "Bleu", "Pantalon", 200
);

if ($jean->create()) {
    echo "✅ Jean créé avec l'ID : " . $jean->getId() . "<br>";
} else {
    echo "❌ Erreur création Jean.<br>";
}

// ---------------------------------------------------
// TEST 2 : Création d'un Électronique (via classe concrète)
// ---------------------------------------------------
echo "<h2>2. Création d'un appareil Électronique (classe Electronic)</h2>";
$iphone = new Electronic(
    0, 2, "Smartphone Abstrait", ["phone.jpg"], 99900, "Dernier modèle via AbstractProduct", 10, new DateTime(), new DateTime(),
    "Apple", 5000
);

if ($iphone->create()) {
    echo "✅ Smartphone créé avec l'ID : " . $iphone->getId() . "<br>";
} else {
    echo "❌ Erreur création Smartphone.<br>";
}

echo "<hr>";

// ---------------------------------------------------
// TEST 3 : findAll() sur Clothing
// ---------------------------------------------------
echo "<h2>3. Liste de tous les vêtements (findAll)</h2>";
$clothingInstance = new Clothing();
$allClothes = $clothingInstance->findAll();

echo "<ul>";
foreach ($allClothes as $c) {
    echo "<li><strong>" . $c->getName() . "</strong> - Taille: " . $c->getSize() . " - Couleur: " . $c->getColor() . "</li>";
}
echo "</ul>";

// ---------------------------------------------------
// TEST 4 : findAll() sur Electronic
// ---------------------------------------------------
echo "<h2>4. Liste de tous les électroniques (findAll)</h2>";
$electronicInstance = new Electronic();
$allElectronics = $electronicInstance->findAll();

echo "<ul>";
foreach ($allElectronics as $e) {
    echo "<li><strong>" . $e->getName() . "</strong> - Marque: " . $e->getBrand() . "</li>";
}
echo "</ul>";