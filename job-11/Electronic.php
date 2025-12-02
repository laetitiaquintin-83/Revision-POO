<?php

class Electronic extends Product
{
    private ?string $brand = null;
    private float $waranty_fee = 0;

    public function __construct(
        ?int $id = null,
        ?string $name = null,
        ?array $photos = null,
        ?int $price = null,
        ?string $description = null,
        ?int $quantity = null,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null,
        ?int $category_id = null,
        ?string $brand = null,
        float $waranty_fee = 0
    ) {
        parent::__construct($id, $name, $photos, $price, $description, $quantity, $createdAt, $updatedAt, $category_id);
        $this->brand = $brand;
        $this->waranty_fee = $waranty_fee;
    }

    // Getters
    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function getWarantyFee(): float
    {
        return $this->waranty_fee;
    }

    // Setters
    public function setBrand(?string $brand): self
    {
        $this->brand = $brand;
        return $this;
    }

    public function setWarantyFee(float $waranty_fee): self
    {
        $this->waranty_fee = $waranty_fee;
        return $this;
    }

    /**
     * Crée un produit électronique en base de données
     */
    public function create(): Product|false
    {
        // D'abord créer le produit parent
        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $pdo->beginTransaction();

            // Insérer dans la table product
            $sql = "INSERT INTO product (name, photos, price, description, quantity, created_at, updated_at, category_id) 
                    VALUES (:name, :photos, :price, :description, :quantity, :created_at, :updated_at, :category_id)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => $this->getName(),
                ':photos' => json_encode($this->getPhotos()),
                ':price' => $this->getPrice(),
                ':description' => $this->getDescription(),
                ':quantity' => $this->getQuantity(),
                ':created_at' => $this->getCreatedAt()?->format('Y-m-d H:i:s'),
                ':updated_at' => $this->getUpdatedAt()?->format('Y-m-d H:i:s'),
                ':category_id' => $this->getCategoryId()
            ]);

            $productId = (int) $pdo->lastInsertId();
            $this->setId($productId);

            // Insérer dans la table electronic
            $sqlElectronic = "INSERT INTO electronic (product_id, brand, waranty_fee) VALUES (:product_id, :brand, :waranty_fee)";
            $stmtElectronic = $pdo->prepare($sqlElectronic);
            $stmtElectronic->execute([
                ':product_id' => $productId,
                ':brand' => $this->brand,
                ':waranty_fee' => $this->waranty_fee
            ]);

            $pdo->commit();
            return $this;

        } catch (PDOException $e) {
            $pdo->rollBack();
            return false;
        }
    }

    /**
     * Met à jour un produit électronique en base de données
     */
    public function update(): Product|false
    {
        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $pdo->beginTransaction();

            // Mettre à jour la table product
            $sql = "UPDATE product SET name = :name, photos = :photos, price = :price, description = :description, 
                    quantity = :quantity, updated_at = :updated_at, category_id = :category_id WHERE id = :id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id' => $this->getId(),
                ':name' => $this->getName(),
                ':photos' => json_encode($this->getPhotos()),
                ':price' => $this->getPrice(),
                ':description' => $this->getDescription(),
                ':quantity' => $this->getQuantity(),
                ':updated_at' => (new DateTime())->format('Y-m-d H:i:s'),
                ':category_id' => $this->getCategoryId()
            ]);

            // Mettre à jour la table electronic
            $sqlElectronic = "UPDATE electronic SET brand = :brand, waranty_fee = :waranty_fee WHERE product_id = :product_id";
            $stmtElectronic = $pdo->prepare($sqlElectronic);
            $stmtElectronic->execute([
                ':product_id' => $this->getId(),
                ':brand' => $this->brand,
                ':waranty_fee' => $this->waranty_fee
            ]);

            $pdo->commit();
            return $this;

        } catch (PDOException $e) {
            $pdo->rollBack();
            return false;
        }
    }
}
