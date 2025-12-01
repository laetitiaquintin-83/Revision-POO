<?php

require_once 'Product.php';
require_once 'Category.php';

echo "<h1>Job 06 - Méthode getProducts() dans Category</h1>";

// 1. Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');

// 2. Récupérer une catégorie (par exemple ID 1 = Vêtements)
$sql = "SELECT * FROM category WHERE id = :id";
$statement = $pdo->prepare($sql);
$statement->execute(['id' => 1]);
$data = $statement->fetch(PDO::FETCH_ASSOC);

if ($data) {
    $category = new Category(
        $data['id'],
        $data['name'],
        $data['description'],
        new DateTime($data['createdAt']),
        new DateTime($data['updatedAt'])
    );

    echo "<h2>Catégorie : " . $category->getName() . "</h2>";

    // 3. Utilisation de getProducts() pour récupérer tous les produits de cette catégorie
    $products = $category->getProducts();

    echo "<h2>Produits de cette catégorie :</h2>";

    if (empty($products)) {
        echo "<p>Aucun produit dans cette catégorie.</p>";
    } else {
        echo "<ul>";
        foreach ($products as $product) {
            echo "<li>";
            echo "<strong>" . $product->getName() . "</strong> - " . $product->getPrice() . " centimes";
            echo "</li>";
        }
        echo "</ul>";
    }

    echo "<hr><h2>Détail (var_dump) :</h2>";
    var_dump($products);
} else {
    echo "<p style='color:red'>Catégorie non trouvée.</p>";
}
