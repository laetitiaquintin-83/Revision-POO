<?php

require_once 'Product.php';
require_once 'Category.php';

echo "<h1>Job 05 - Méthode getCategory()</h1>";

// 1. Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');

// 2. Requête pour récupérer le produit avec l'id 7
$sql = "SELECT * FROM product WHERE id = :id";
$statement = $pdo->prepare($sql);
$statement->execute(['id' => 7]);

// 3. Récupération sous forme de tableau associatif
$data = $statement->fetch(PDO::FETCH_ASSOC);

if ($data) {
    // 4. Hydratation du produit
    $product = new Product(
        $data['id'],
        $data['category_id'],
        $data['name'],
        json_decode($data['photos'] ?? '[]', true),
        $data['price'],
        $data['description'],
        $data['quantity'],
        new DateTime($data['createdAt']),
        new DateTime($data['updatedAt'])
    );

    echo "<h2>Produit ID 7 :</h2>";
    echo "Nom : " . $product->getName() . "<br>";
    echo "Category ID : " . $product->getCategoryId() . "<br>";

    // 5. Utilisation de getCategory() pour récupérer la catégorie complète
    echo "<h2>Catégorie associée (via getCategory()) :</h2>";
    $category = $product->getCategory();
    
    var_dump($category);
    
    echo "<br><br>";
    echo "Nom de la catégorie : <strong>" . $category->getName() . "</strong><br>";
    echo "Description : " . $category->getDescription() . "<br>";
} else {
    echo "<p style='color:red'>Aucun produit trouvé avec l'ID 7.</p>";
}
