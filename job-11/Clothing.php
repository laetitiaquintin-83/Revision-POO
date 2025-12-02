<?php

require_once 'Product.php';

class Clothing extends Product
{
    // Propriétés spécifiques
    private string $size;
    private string $color;
    private string $type;
    private int $material_fee;

    public function __construct(
        // Paramètres du Parent (Product)
        int $id = 0,
        int $category_id = 0,
        string $name = "",
        array $photos = [],
        int $price = 0,
        string $description = "",
        int $quantity = 0,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null,
        // Paramètres Spécifiques (Clothing)
        string $size = "",
        string $color = "",
        string $type = "",
        int $material_fee = 0
    ) {
        // 1. On appelle le constructeur du PARENT pour gérer la partie "Product"
        parent::__construct($id, $category_id, $name, $photos, $price, $description, $quantity, $createdAt, $updatedAt);

        // 2. On gère nous-même la partie spécifique
        $this->size = $size;
        $this->color = $color;
        $this->type = $type;
        $this->material_fee = $material_fee;
    }

    // --- Getters et Setters spécifiques (je t'en mets un pour l'exemple) ---
    public function getSize(): string { return $this->size; }
    public function setSize(string $size): void { $this->size = $size; }
    // ... Tu devras faire les autres (color, type, material_fee) ...

    /**
     * Surcharge de la méthode create()
     * On sauvegarde d'abord le produit générique, PUIS les infos vêtement.
     */
    public function create(): Product|false
    {
        // 1. On demande au PARENT d'enregistrer la partie commune (table product)
        // Cela va générer l'ID ($this->id)
        $parentSuccess = parent::create();

        if (!$parentSuccess) {
            return false;
        }

        // 2. Si le parent a réussi, on insère la partie spécifique dans 'clothing'
        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');
        
        $sql = "INSERT INTO clothing (product_id, size, color, type, material_fee) 
                VALUES (:product_id, :size, :color, :type, :material_fee)";
        
        $stmt = $pdo->prepare($sql);
        
        $childSuccess = $stmt->execute([
            'product_id' => $this->getId(), // On utilise l'ID généré par le parent (via getter car $id est private)
            'size' => $this->size,
            'color' => $this->color,
            'type' => $this->type,
            'material_fee' => $this->material_fee
        ]);

        // Si tout est bon, on retourne l'objet complet
        return $childSuccess ? $this : false;
    }
}