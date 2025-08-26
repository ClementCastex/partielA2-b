<?php

namespace App\Entity;

use App\Repository\EquipmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EquipmentRepository::class)]
#[ORM\Table(name: 'equipment')]
#[ORM\UniqueConstraint(name: 'uniq_equipment_name', columns: ['name'])]
class Equipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\GreaterThanOrEqual(0)]
    private int $quantityAvailable = 0;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0)]
    private string $pricePerDay = '0.00';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getQuantityAvailable(): int
    {
        return $this->quantityAvailable;
    }

    public function setQuantityAvailable(int $quantityAvailable): self
    {
        $this->quantityAvailable = $quantityAvailable;
        return $this;
    }

    public function getPricePerDay(): string
    {
        return $this->pricePerDay;
    }

    public function setPricePerDay(string $pricePerDay): self
    {
        $this->pricePerDay = $pricePerDay;
        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }
}


