<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $sku;

    /**
     * @ORM\Column(type="smallint", options={"default" : 0})
     */
    private $stock = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Supplier", inversedBy="products")
     */
    private $supplier;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isNearlyOutOfStock = false;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $inventoryTurnover;

    /**
     * @ORM\Column(type="smallint", options={"default" : 0})
     */
    private $initialStock = 0;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Product
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     * @return Product
     */
    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getStock(): ?int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     * @return Product
     */
    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return Supplier|null
     */
    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    /**
     * @param Supplier|null $supplier
     * @return Product
     */
    public function setSupplier(?Supplier $supplier): self
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * @return null|string
     */
    public function __toString(): ?string
    {
        return $this->name . '(' . $this->sku . ')';
    }

    /**
     * @return bool|null
     */
    public function getIsNearlyOutOfStock(): ?bool
    {
        return $this->isNearlyOutOfStock;
    }

    /**
     * @param bool $isNearlyOutOfStock
     * @return Product
     */
    public function setIsNearlyOutOfStock(bool $isNearlyOutOfStock): self
    {
        $this->isNearlyOutOfStock = $isNearlyOutOfStock;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getInventoryTurnover(): ?float
    {
        return $this->inventoryTurnover;
    }

    /**
     * @param float|null $inventoryTurnover
     * @return Product
     */
    public function setInventoryTurnover(?float $inventoryTurnover): self
    {
        $this->inventoryTurnover = $inventoryTurnover;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getInitialStock(): ?int
    {
        return $this->initialStock;
    }

    /**
     * @param int $initialStock
     * @return Product
     */
    public function setInitialStock(int $initialStock): self
    {
        $this->initialStock = $initialStock;

        return $this;
    }
}
