<?php

namespace App\Interface;

/**
 * Interface pour la gestion des stocks
 * Toute classe implémentant cette interface doit gérer l'ajout et le retrait de stocks
 */
interface StockableInterface
{
    /**
     * Ajoute du stock à l'article
     * @param int $stock Quantité à ajouter
     * @return self L'instance courante pour le chaînage
     */
    public function addStocks(int $stock): self;

    /**
     * Retire du stock à l'article
     * @param int $stock Quantité à retirer
     * @return self L'instance courante pour le chaînage
     */
    public function removeStocks(int $stock): self;
}
