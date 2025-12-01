<?php

require_once 'Product.php';
require_once 'Category.php';

echo "<h1>Job 04 - Récupération du produit ID 7 depuis la BDD</h1>";

// 1. Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');

// 2. Requête pour récupérer le produit avec l'id 7
$sql = "SELECT * FROM product WHERE id = :id";
$statement = $pdo->prepare($sql);
$statement->execute(['id' => 7]);

// 3. Récupération sous forme de tableau associatif
$data = $statement->fetch(PDO::FETCH_ASSOC);

echo "<h2>Données brutes de la BDD (tableau associatif) :</h2>";
var_dump($data);

// 4. Hydratation : Création d'une instance Product avec les données de la BDD
if ($data) {
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

    echo "<h2>Instance Product hydratée :</h2>";
    var_dump($product);

    echo "<h2>Accès via les getters :</h2>";
    echo "ID : " . $product->getId() . "<br>";
    echo "Nom : " . $product->getName() . "<br>";
    echo "Prix : " . $product->getPrice() . " centimes<br>";
    echo "Category ID : " . $product->getCategoryId() . "<br>";
} else {
    echo "<p style='color:red'>Aucun produit trouvé avec l'ID 7. Vérifie que tu as exécuté les requêtes SQL du job-03.</p>";
}
