<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Purchase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Collections\Collection;

/**
 * @method Purchase|null find($id, $lockMode = null, $lockVersion = null)
 * @method Purchase|null findOneBy(array $criteria, array $orderBy = null)
 * @method Purchase[]    findAll()
 * @method Purchase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchaseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Purchase::class);
    }

    /**
     * Get stock purchased by product
     *
     * @param Product $product
     *
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getStockPurchased(Product $product): ?int
    {
        $query = $this->createQueryBuilder('purchase')
            ->select('SUM(item.quantity)')
            ->join('purchase.items', 'item')
            ->join('item.product', 'product')
            ->where('product.id = :productId')
            ->setParameter('productId', $product->getId())
            ->getQuery();

        return (int) $query->getSingleScalarResult()?: 0;
    }
}
