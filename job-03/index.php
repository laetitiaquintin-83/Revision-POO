<?php

require_once 'Product.php';
require_once 'Category.php';

echo "<h1>Job 03 - Base de données + Constructeurs optionnels</h1>";

// Test Job 03.1 : Constructeurs optionnels
echo "<h2>Test des constructeurs optionnels :</h2>";

// Instanciation sans paramètres
$productVide = new Product();
echo "Produit vide :<br>";
var_dump($productVide);

// Instanciation avec tous les paramètres
$productComplet = new Product(
    1,
    1,
    "T-shirt",
    ["photo.jpg"],
    1999,
    "Description",
    10,
    new DateTime(),
    new DateTime()
);
echo "<br>Produit complet :<br>";
var_dump($productComplet);

echo "<hr>";
echo "<h2>SQL pour créer les tables :</h2>";
echo "<pre>";
echo "
-- Création de la base de données
CREATE DATABASE IF NOT EXISTS `draft-shop`;
USE `draft-shop`;

-- Table category
CREATE TABLE `category` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `createdAt` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table product
CREATE TABLE `product` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT,
    `name` VARCHAR(255) NOT NULL,
    `photos` JSON,
    `price` INT NOT NULL,
    `description` TEXT,
    `quantity` INT DEFAULT 0,
    `createdAt` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `category`(`id`)
);

-- Insertions de test
INSERT INTO `category` (`name`, `description`) VALUES 
('Vêtements', 'Tous nos vêtements'),
('Chaussures', 'Toutes nos chaussures'),
('Accessoires', 'Tous nos accessoires');

INSERT INTO `product` (`category_id`, `name`, `photos`, `price`, `description`, `quantity`) VALUES 
(1, 'T-shirt Noir', '[\"tshirt1.jpg\"]', 1999, 'T-shirt en coton noir', 50),
(1, 'T-shirt Blanc', '[\"tshirt2.jpg\"]', 1999, 'T-shirt en coton blanc', 30),
(1, 'Jean Slim', '[\"jean1.jpg\"]', 4999, 'Jean slim fit', 25),
(2, 'Sneakers', '[\"sneakers1.jpg\"]', 8999, 'Sneakers confortables', 15),
(2, 'Bottes', '[\"bottes1.jpg\"]', 12999, 'Bottes en cuir', 10),
(3, 'Casquette', '[\"casquette1.jpg\"]', 1499, 'Casquette ajustable', 100),
(1, 'Pull Over', '[\"pull1.jpg\"]', 3999, 'Pull chaud pour hiver', 20);
";
echo "</pre>";
