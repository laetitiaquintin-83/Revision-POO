<?php

namespace App;

use App\Abstract\AbstractProduct;
use App\Interface\StockableInterface;
use DateTime;
use PDO;

class Electronic extends AbstractProduct implements StockableInterface
{
    private string $brand;
    private int $waranty_fee;

    public function __construct(
        int $id = 0, int $category_id = 0, string $name = "", array $photos = [], int $price = 0,
        string $description = "", int $quantity = 0, ?DateTime $createdAt = null, ?DateTime $updatedAt = null,
        string $brand = "", int $waranty_fee = 0
    ) {
        parent::__construct($id, $category_id, $name, $photos, $price, $description, $quantity, $createdAt, $updatedAt);
        $this->brand = $brand;
        $this->waranty_fee = $waranty_fee;
    }

    // Getters et Setters spécifiques
    public function getBrand(): string { return $this->brand; }
    public function setBrand(string $brand): void { $this->brand = $brand; }

    public function getWarantyFee(): int { return $this->waranty_fee; }
    public function setWarantyFee(int $waranty_fee): void { $this->waranty_fee = $waranty_fee; }

    // --- MÉTHODES SURCHARGÉES ---

    public function create(): AbstractProduct|false
    {
        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');

        // Insertion dans la table product
        $sql = "INSERT INTO product (category_id, name, photos, price, description, quantity, createdAt, updatedAt) 
                VALUES (:category_id, :name, :photos, :price, :description, :quantity, :createdAt, :updatedAt)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            'category_id' => $this->category_id,
            'name' => $this->name,
            'photos' => json_encode($this->photos),
            'price' => $this->price,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s')
        ]);
        if (!$result) return false;
        $this->id = (int) $pdo->lastInsertId();

        $sql = "INSERT INTO electronic (product_id, brand, waranty_fee) VALUES (:id, :brand, :fee)";
        $stmt = $pdo->prepare($sql);
        
        return $stmt->execute([
            'id' => $this->id,
            'brand' => $this->brand,
            'fee' => $this->waranty_fee
        ]) ? $this : false;
    }

    public function update(): AbstractProduct|false
    {
        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');
        $this->updatedAt = new DateTime();

        // Mise à jour de la table product
        $sql = "UPDATE product SET category_id = :category_id, name = :name, photos = :photos, price = :price, 
                description = :description, quantity = :quantity, updatedAt = :updatedAt WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'photos' => json_encode($this->photos),
            'price' => $this->price,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s')
        ]);
        if (!$result) return false;

        $sql = "UPDATE electronic SET brand = :brand, waranty_fee = :fee WHERE product_id = :id";
        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'id' => $this->id,
            'brand' => $this->brand,
            'fee' => $this->waranty_fee
        ]) ? $this : false;
    }

    public function findOneById(int $id): AbstractProduct|false
    {
        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');
        $sql = "SELECT * FROM product INNER JOIN electronic ON product.id = electronic.product_id WHERE product.id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) return false;

        $this->id = $data['id'];
        $this->category_id = $data['category_id'];
        $this->name = $data['name'];
        $this->photos = json_decode($data['photos'], true);
        $this->price = $data['price'];
        $this->description = $data['description'];
        $this->quantity = $data['quantity'];
        $this->createdAt = new DateTime($data['createdAt']);
        $this->updatedAt = new DateTime($data['updatedAt']);
        $this->brand = $data['brand'];
        $this->waranty_fee = $data['waranty_fee'];

        return $this;
    }

    public function findAll(): array
    {
        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');
        $sql = "SELECT * FROM product INNER JOIN electronic ON product.id = electronic.product_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $electronics = [];
        foreach ($results as $row) {
            $electronics[] = new Electronic(
                $row['id'], $row['category_id'], $row['name'], json_decode($row['photos'], true),
                $row['price'], $row['description'], $row['quantity'], new DateTime($row['createdAt']), new DateTime($row['updatedAt']),
                $row['brand'], $row['waranty_fee']
            );
        }
        return $electronics;
    }

    /**
     * Ajoute du stock à l'appareil électronique
     */
    public function addStocks(int $stock): self
    {
        $this->quantity += $stock;
        return $this;
    }

    /**
     * Retire du stock à l'appareil électronique
     */
    public function removeStocks(int $stock): self
    {
        $this->quantity = max(0, $this->quantity - $stock);
        return $this;
    }
}
