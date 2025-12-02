<?php

require_once 'AbstractProduct.php';

class Clothing extends AbstractProduct
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

        // Insertion dans la table clothing

        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');
        $sql = "INSERT INTO clothing (product_id, size, color, type, material_fee) VALUES (:id, :size, :color, :type, :fee)";
        $stmt = $pdo->prepare($sql);
        
        return $stmt->execute([
            'id' => $this->id,
            'size' => $this->size,
            'color' => $this->color,
            'type' => $this->type,
            'fee' => $this->material_fee
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

        // Mise à jour de la table clothing

        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');
        $sql = "UPDATE clothing SET size = :size, color = :color, type = :type, material_fee = :fee WHERE product_id = :id";
        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'id' => $this->id,
            'size' => $this->size,
            'color' => $this->color,
            'type' => $this->type,
            'fee' => $this->material_fee
        ]) ? $this : false;
    }

    public function findOneById(int $id): AbstractProduct|false
    {
        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');
        $sql = "SELECT * FROM product INNER JOIN clothing ON product.id = clothing.product_id WHERE product.id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) return false;

        // Hydratation manuelle (Parent + Enfant)
        $this->id = $data['id'];
        $this->category_id = $data['category_id'];
        $this->name = $data['name'];
        $this->photos = json_decode($data['photos'], true);
        $this->price = $data['price'];
        $this->description = $data['description'];
        $this->quantity = $data['quantity'];
        $this->createdAt = new DateTime($data['createdAt']);
        $this->updatedAt = new DateTime($data['updatedAt']);
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