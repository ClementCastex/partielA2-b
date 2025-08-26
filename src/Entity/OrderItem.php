<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
#[ORM\Table(name: 'order_item')]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: RentalOrder::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?RentalOrder $order = null;

    #[ORM\ManyToOne(targetEntity: Equipment::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Equipment $equipment = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\GreaterThan(0)]
    private int $quantity = 1;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Assert\GreaterThanOrEqual(0)]
    private string $unitPrice = '0.00';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?RentalOrder
    {
        return $this->order;
    }

    public function setOrder(?RentalOrder $order): self
    {
        $this->order = $order;
        return $this;
    }

    public function getEquipment(): ?Equipment
    {
        return $this->equipment;
    }

    public function setEquipment(Equipment $equipment): self
    {
        $this->equipment = $equipment;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getUnitPrice(): string
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(string $unitPrice): self
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }
}


