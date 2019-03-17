<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SaleRepository")
 */
class Sale
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $saleNumber;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="SaleItem", mappedBy="sale", cascade={"persist", "remove"})
     */
    private $items;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $isLocked = false;

    /**
     * Sale constructor.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

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
    public function getSaleNumber(): ?string
    {
        return $this->saleNumber;
    }

    /**
     * @param string $saleNumber
     * @return Sale
     */
    public function setSaleNumber(string $saleNumber): self
    {
        $this->saleNumber = $saleNumber;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     * @return Sale
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|SaleItem[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * @param SaleItem $item
     * @return Sale
     */
    public function addItem(SaleItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setSale($this);
        }

        return $this;
    }

    /**
     * @param SaleItem $item
     * @return Sale
     */
    public function removeItem(SaleItem $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getSale() === $this) {
                $item->setSale(null);
            }
        }

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isLocked(): ?bool
    {
        return $this->isLocked;
    }

    /**
     * @param bool $isLocked
     * @return Sale
     */
    public function setIsLocked(bool $isLocked): self
    {
        $this->isLocked = $isLocked;

        return $this;
    }
}
