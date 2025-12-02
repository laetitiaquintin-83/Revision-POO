<?php

namespace App;

use DateTime;
use PDO;

class Category
{
    private int $id;
    private string $name;
    private string $description;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(
        int $id = 0,
        string $name = "",
        string $description = "",
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = $createdAt ?? new DateTime();
        $this->updatedAt = $updatedAt ?? new DateTime();
    }

    // Getters et Setters
    public function getId(): int {
        return $this->id;
    }
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }
    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getDescription(): string {
        return $this->description;
    }
    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }
    public function setCreatedAt(DateTime $createdAt): void {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): DateTime {
        return $this->updatedAt;
    }
    public function setUpdatedAt(DateTime $updatedAt): void {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Récupère tous les produits liés à cette catégorie
     * @return Clothing[]|Electronic[] Tableau de produits
     */
    public function getProducts(): array
    {
        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');

        $sql = "SELECT * FROM product WHERE category_id = :id";
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $this->id]);

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        $products = [];

        foreach ($results as $row) {
            // On retourne un tableau associatif car on ne peut pas instancier AbstractProduct
            $products[] = $row;
        }

        return $products;
    }
}
