<?php

require_once 'Category.php';

class Product
{
    private int $id;
    private int $category_id;
    private string $name;
    private array $photos;
    private int $price;
    private string $description;
    private int $quantity;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    // Constructeur avec paramètres optionnels (Job 03.1)
    public function __construct(
        int $id = 0,
        int $category_id = 0,
        string $name = "",
        array $photos = [],
        int $price = 0,
        string $description = "",
        int $quantity = 0,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->category_id = $category_id;
        $this->name = $name;
        $this->photos = $photos;
        $this->price = $price;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->createdAt = $createdAt ?? new DateTime();
        $this->updatedAt = $updatedAt ?? new DateTime();
    }

    // Getters et Setters
    public function getCategoryId(): int {
        return $this->category_id;
    }
    public function setCategoryId(int $category_id): void {
        $this->category_id = $category_id;
    }

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

    public function getPhotos(): array {
        return $this->photos;
    }
    public function setPhotos(array $photos): void {
        $this->photos = $photos;
    }

    public function getPrice(): int {
        return $this->price;
    }
    public function setPrice(int $price): void {
        $this->price = $price;
    }

    public function getDescription(): string {
        return $this->description;
    }
    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }
    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
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
     * Récupère l'instance de la catégorie associée à ce produit
     */
    public function getCategory(): Category
    {
        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');

        $sql = "SELECT * FROM category WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $this->category_id]);

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new Category(
                $data['id'],
                $data['name'],
                $data['description'],
                new DateTime($data['createdAt']),
                new DateTime($data['updatedAt'])
            );
        }

        return new Category();
    }

    /**
     * Trouve un produit par son ID et hydrate l'instance courante
     * @param int $id L'ID du produit à rechercher
     * @return Product|false L'instance hydratée ou false si non trouvé
     */
    public function findOneById(int $id): Product|false
    {
        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');

        $sql = "SELECT * FROM product WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            // Hydratation de l'instance courante
            $this->id = $data['id'];
            $this->category_id = $data['category_id'];
            $this->name = $data['name'];
            $this->photos = json_decode($data['photos'] ?? '[]', true);
            $this->price = $data['price'];
            $this->description = $data['description'];
            $this->quantity = $data['quantity'];
            $this->createdAt = new DateTime($data['createdAt']);
            $this->updatedAt = new DateTime($data['updatedAt']);

            return $this;
        }

        return false;
    }

    /**
     * Récupère tous les produits de la base de données
     * @return Product[] Tableau d'instances de Product
     */
    public function findAll(): array
    {
        $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8', 'root', '');

        $sql = "SELECT * FROM product";
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        $products = [];

        foreach ($results as $row) {
            $products[] = new Product(
                $row['id'],
                $row['category_id'],
                $row['name'],
                json_decode($row['photos'] ?? '[]', true),
                $row['price'],
                $row['description'],
                $row['quantity'],
                new DateTime($row['createdAt']),
                new DateTime($row['updatedAt'])
            );
        }

        return $products;
    }
}
