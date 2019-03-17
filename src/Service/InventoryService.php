<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Purchase;
use App\Entity\Sale;
use Doctrine\ORM\EntityManager;

/**
 * Class InventoryService
 *
 * @package App\Service
 */
class InventoryService
{
    /**
     * @var EntityManager $entityManager
     */
    private $entityManager;

    /**
     * InventoryService constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Update products stock at this time
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateStocks(): void
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        foreach ($products as $product) {
            $this->updateStockByProduct($product);
        }
    }

    /**
     * Update product
     *
     * @param Product $product
     *
     * @return void
     */
    public function updateStockByProduct(Product &$product)
    {
        //TODO: improve later
        $startDate = new \DateTime('2018-10-01');
        $endDate = new \DateTime();

        // Get number of product purchase
        $stockPurchased = $this->getStockPurchased($product);
        // Get number of customer sales
        $stockSold = $this->getStockSold($product);
        $stockDiff = $stockPurchased - $stockSold;
        $turnover = $this->calculateInventoryTurnover($product, $startDate, $endDate);
        $hasRisk = $this->calculateOutOfStockRisk($product, $turnover);

        $product->setStock($product->getInitialStock() + $stockDiff);
        $product->setInventoryTurnover($turnover);
        $product->setIsNearlyOutOfStock($hasRisk);

        $this->entityManager->flush();
    }

    /**
     * Get stock purchased
     *
     * @param Product $product
     *
     * @return int
     */
    public function getStockPurchased(Product $product): ?int
    {
        return $this->entityManager->getRepository(Purchase::class)->getStockPurchased($product);
    }

    /**
     * Get stock sold (customer sales)
     *
     * @param Product $product
     *
     * @return int
     */
    public function getStockSold(Product $product): ?int
    {
        return $this->entityManager->getRepository(Sale::class)->getStockSold($product);
    }

    public function calculateInventoryTurnover(Product $product, \DateTime $dateStart, \DateTime $dateEnd)
    {
        $nbSales = $this->entityManager->getRepository(Sale::class)->getNbSales($product, $dateStart, $dateEnd);

        $interval = date_diff($dateStart, $dateEnd);
        $nbDays = $interval->format('%a');

        //TODO: improve later with configuration
        return $nbSales / $nbDays * 7;
    }

    /**
     * @param Product $product
     * @param float   $turnover
     *
     * @return bool
     */
    public function calculateOutOfStockRisk(Product $product, float $turnover): ?bool
    {
        //TODO: improve later with configuration
        $threshold = $turnover * 2;

        if ($product->getStock() <= $threshold) {
            return true;
        }

        return false;
    }
}
