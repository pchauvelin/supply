<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Purchase;
use App\Entity\Sale;
use App\Entity\SaleItem;
use Doctrine\ORM\EntityManager;

/**
 * Class SaleService
 *
 * @package App\Service
 */
class SaleService
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
     * Import sales in the system
     *
     * @param string $pathFile
     *
     * @return void
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     */
    public function importSales(string $pathFile)
    {
        $file = fopen($pathFile, "r");
        $counter = 0;

        while (!feof($file)) {
            $line = fgets($file);

            if ($counter == 0) {
                $counter++;
                continue;
            }

            if ($this->addSale($line)) {
                $counter++;

                /*
                 * Performance issue
                 */
                if ($counter%10 == 0) {
                    $this->entityManager->clear();
                }
            }
        }

        fclose($file);

        unlink($pathFile);
    }

    /**
     * Add a sale
     *
     * @param string $line
     *
     * @return bool
     */
    private function addSale($line): ?bool
    {
        /*
         * [0] : sale number
         * [1] : sale creation date
         * [2] : sku
         * [3] : product name
         * [4] : quantity ordered
         */
        $data = explode(';', $line);

        if (count($data) < 5) {
            return false;
        }

        $quantity = (int) rtrim($data[4]);

        $product = $this->entityManager->getRepository(Product::class)->findOneBySku($data[2]);
        $sale = $this->entityManager->getRepository(Sale::class)->findOneBySaleNumber($data[0]);

        if (!$product instanceof Product) {
            return false;
        }

        try {
            if (!$sale instanceof Sale) {
                $sale = new Sale();
                $sale->setSaleNumber($data[0]);
                $sale->setCreatedAt(new \DateTime($data[1]));
                $this->entityManager->persist($sale);
            }

            if ($sale->isLocked() === false) {
                $item = new SaleItem();
                $item->setProduct($product);
                $item->setQuantity($quantity);
                $this->entityManager->persist($item);
                $sale->addItem($item);
            }

            $this->entityManager->flush();

            return true;
        } catch (\Exception $e) {
            // add log
            return false;
        }
    }
}
