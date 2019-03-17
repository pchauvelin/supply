<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Sale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sale|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sale|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sale[]    findAll()
 * @method Sale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sale::class);
    }

    /**
     * Get stock sold by product
     *
     * @param Product $product
     *
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getStockSold(Product $product): ?int
    {
        $query = $this->createQueryBuilder('sale')
            ->select('SUM(item.quantity)')
            ->join('sale.items', 'item')
            ->join('item.product', 'product')
            ->where('product.id = :productId')
            ->setParameter('productId', $product->getId())
            ->getQuery();

        return (int) $query->getSingleScalarResult()?: 0;
    }

    /**
     * Get number of customer sales by product between two dates
     *
     * @param Product $product
     * @param \DateTime $dateStart
     * @param \DateTime $dateEnd
     *
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getNbSales(Product $product, \DateTime $dateStart, \DateTime $dateEnd): ?int
    {
        $query = $this->createQueryBuilder('sale')
            ->select('SUM(item.quantity)')
            ->join('sale.items', 'item')
            ->join('item.product', 'product')
            ->where('product.id = :productId')
            ->andWhere('sale.createdAt BETWEEN :dateStart AND :dateEnd')
            ->setParameters(
                [
                    'productId' => $product->getId(),
                    'dateStart' => $dateStart->format('Y-m-d'),
                    'dateEnd' => $dateEnd->format('Y-m-d')
                ]
            )
            ->getQuery();

        return (int) $query->getSingleScalarResult()?: 0;
    }
}
