<?php

require_once 'Clothing.php';
// require_once 'Electronic.php'; // À décommenter quand tu l'auras fait

// On crée un nouveau vêtement
// Note la liste longue d'arguments : d'abord ceux du produit, puis ceux du vêtement
$pull = new Clothing(
    0,                  // ID
    1,                  // Catégorie ID
    "Pull en Laine",    // Nom
    ["pull.jpg"],       // Photos
    50,                 // Prix
    "Chaud pour l'hiver", // Description
    20,                 // Quantité
    new DateTime(),     // CreatedAt
    new DateTime(),     // UpdatedAt
    "XL",               // Size (Spécifique)
    "Bleu",             // Color (Spécifique)
    "Pull",             // Type (Spécifique)
    5                   // Material Fee (Spécifique)
);

// Sauvegarde
if ($pull->create()) {
    echo "✅ Vêtement créé avec succès ! ID : " . $pull->getId();
} else {
    echo "❌ Erreur.";
}