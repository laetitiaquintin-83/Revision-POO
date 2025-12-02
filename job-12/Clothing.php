<?php

require_once 'Product.php';

class Clothing extends Product
{
    private string $size;
    private string $color;
    private string $type;
    private int $material_fee;

    public function __construct(
        int $id = 0, int $category_id = 0, string $name = "", array $photos = [], int $price = 0,
        string $description = "", int $quantity = 0, ?DateTime $createdAt = null, ?DateTime $updatedAt = null,
        // Paramètres spécifiques
        string $size = "", string $color = "", string $type = "", int $material_fee = 0
    ) {
        parent::__construct($id, $category_id, $name, $photos, $price, $description, $quantity, $createdAt, $updatedAt);
        $this->size = $size;
        $this->color = $color;
        $this->type = $type;
        $this->material_fee = $material_fee;
    }

    // Getters et Setters spécifiques
    public function getSize(): string { return $this->size; }
    public function setSize(string $size): void { $this->size = $size; }

    public function getColor(): string { return $this->color; }
    public function setColor(string $color): void { $this->color = $color; }

    public function getType(): string { return $this->type; }
    public function setType(string $type): void { $this->type = $type; }

    public function getMaterialFee(): int { return $this->material_fee; }
    public function setMaterialFee(int $material_fee): void { $this->material_fee = $material_fee; }

    // --- MÉTHODES SURCHARGÉES (POLYMORPHISME) ---

    public function create(): Product|false
    {
        if (!parent::create()) return false; // On crée d'abord le produit parent

        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');
        $sql = "INSERT INTO clothing (product_id, size, color, type, material_fee) VALUES (:id, :size, :color, :type, :fee)";
        $stmt = $pdo->prepare($sql);
        
        return $stmt->execute([
            'id' => $this->getId(),
            'size' => $this->size,
            'color' => $this->color,
            'type' => $this->type,
            'fee' => $this->material_fee
        ]) ? $this : false;
    }

    public function update(): Product|false
    {
        if (!parent::update()) return false; // On met à jour le parent

        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');
        $sql = "UPDATE clothing SET size = :size, color = :color, type = :type, material_fee = :fee WHERE product_id = :id";
        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'id' => $this->getId(),
            'size' => $this->size,
            'color' => $this->color,
            'type' => $this->type,
            'fee' => $this->material_fee
        ]) ? $this : false;
    }

    public function findOneById(int $id): Product|false
    {
        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');
        $sql = "SELECT * FROM product INNER JOIN clothing ON product.id = clothing.product_id WHERE product.id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) return false;

        // Hydratation via setters (car propriétés du parent sont private)
        $this->setId($data['id']);
        $this->setCategoryId($data['category_id']);
        $this->setName($data['name']);
        $this->setPhotos(json_decode($data['photos'], true));
        $this->setPrice($data['price']);
        $this->setDescription($data['description']);
        $this->setQuantity($data['quantity']);
        $this->setCreatedAt(new DateTime($data['createdAt']));
        $this->setUpdatedAt(new DateTime($data['updatedAt']));
        $this->size = $data['size'];
        $this->color = $data['color'];
        $this->type = $data['type'];
        $this->material_fee = $data['material_fee'];

        return $this;
    }

    public function findAll(): array
    {
        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');
        $sql = "SELECT * FROM product INNER JOIN clothing ON product.id = clothing.product_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $clothes = [];
        foreach ($results as $row) {
            $clothes[] = new Clothing(
                $row['id'], $row['category_id'], $row['name'], json_decode($row['photos'], true),
                $row['price'], $row['description'], $row['quantity'], new DateTime($row['createdAt']), new DateTime($row['updatedAt']),
                $row['size'], $row['color'], $row['type'], $row['material_fee']
            );
        }
        return $clothes;
    }
}