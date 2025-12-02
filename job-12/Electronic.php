<?php

require_once 'Product.php';

class Electronic extends Product
{
    private string $brand;
    private int $waranty_fee;

    public function __construct(
        int $id = 0, int $category_id = 0, string $name = "", array $photos = [], int $price = 0,
        string $description = "", int $quantity = 0, ?DateTime $createdAt = null, ?DateTime $updatedAt = null,
        // Paramètres spécifiques
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

    public function create(): Product|false
    {
        if (!parent::create()) return false;

        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');
        $sql = "INSERT INTO electronic (product_id, brand, waranty_fee) VALUES (:id, :brand, :fee)";
        $stmt = $pdo->prepare($sql);
        
        return $stmt->execute([
            'id' => $this->getId(),
            'brand' => $this->brand,
            'fee' => $this->waranty_fee
        ]) ? $this : false;
    }

    public function update(): Product|false
    {
        if (!parent::update()) return false;

        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');
        $sql = "UPDATE electronic SET brand = :brand, waranty_fee = :fee WHERE product_id = :id";
        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'id' => $this->getId(),
            'brand' => $this->brand,
            'fee' => $this->waranty_fee
        ]) ? $this : false;
    }

    public function findOneById(int $id): Product|false
    {
        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');
        $sql = "SELECT * FROM product INNER JOIN electronic ON product.id = electronic.product_id WHERE product.id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) return false;

        $this->setId($data['id']);
        $this->setCategoryId($data['category_id']);
        $this->setName($data['name']);
        $this->setPhotos(json_decode($data['photos'], true));
        $this->setPrice($data['price']);
        $this->setDescription($data['description']);
        $this->setQuantity($data['quantity']);
        $this->setCreatedAt(new DateTime($data['createdAt']));
        $this->setUpdatedAt(new DateTime($data['updatedAt']));
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
}