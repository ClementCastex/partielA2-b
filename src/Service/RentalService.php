<?php

namespace App\Service;

use App\Entity\Equipment;
use App\Entity\OrderItem;
use App\Entity\RentalOrder;
use App\Repository\EquipmentRepository;
use Doctrine\ORM\EntityManagerInterface;

class RentalService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EquipmentRepository $equipmentRepository,
    ) {
    }

    /**
     * @param array<int,int> $equipmentIdToQuantity
     */
    public function createOrder(string $customerName, array $equipmentIdToQuantity): RentalOrder
    {
        $order = new RentalOrder();
        $order->setCustomerName($customerName);

        // Validate stock first
        foreach ($equipmentIdToQuantity as $equipmentId => $quantity) {
            if ($quantity <= 0) {
                unset($equipmentIdToQuantity[$equipmentId]);
                continue;
            }
            $equipment = $this->equipmentRepository->find($equipmentId);
            if (!$equipment) {
                throw new \InvalidArgumentException('Matériel introuvable: ' . $equipmentId);
            }
            if ($equipment->getQuantityAvailable() < $quantity) {
                throw new \InvalidArgumentException(sprintf('Stock insuffisant pour "%s" (disponible: %d, demandé: %d)', $equipment->getName(), $equipment->getQuantityAvailable(), $quantity));
            }
        }

        if (count($equipmentIdToQuantity) === 0) {
            throw new \InvalidArgumentException('Aucun article sélectionné.');
        }

        // Apply stock decrement and create items
        foreach ($equipmentIdToQuantity as $equipmentId => $quantity) {
            if ($quantity <= 0) {
                continue;
            }
            /** @var Equipment $equipment */
            $equipment = $this->equipmentRepository->find($equipmentId);
            $equipment->setQuantityAvailable($equipment->getQuantityAvailable() - $quantity);

            $item = new OrderItem();
            $item->setEquipment($equipment);
            $item->setQuantity($quantity);
            $item->setUnitPrice($equipment->getPricePerDay());
            $order->addItem($item);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }

    public function returnOrder(RentalOrder $order): void
    {
        // Increment stock back
        foreach ($order->getItems() as $item) {
            $equipment = $item->getEquipment();
            $equipment->setQuantityAvailable($equipment->getQuantityAvailable() + $item->getQuantity());
        }

        // Remove the order entirely as per requirements
        $this->entityManager->remove($order);
        $this->entityManager->flush();
    }

    /**
     * @param array<int,int> $equipmentIdToQuantity
     */
    public function updateOrder(RentalOrder $order, string $customerName, array $equipmentIdToQuantity): RentalOrder
    {
        // Restore previous stock first
        foreach ($order->getItems() as $existing) {
            $equipment = $existing->getEquipment();
            $equipment->setQuantityAvailable($equipment->getQuantityAvailable() + $existing->getQuantity());
        }

        // Validate new stock
        foreach ($equipmentIdToQuantity as $equipmentId => $quantity) {
            if ($quantity <= 0) {
                unset($equipmentIdToQuantity[$equipmentId]);
                continue;
            }
            $equipment = $this->equipmentRepository->find($equipmentId);
            if (!$equipment) {
                throw new \InvalidArgumentException('Matériel introuvable: ' . $equipmentId);
            }
            if ($equipment->getQuantityAvailable() < $quantity) {
                throw new \InvalidArgumentException(sprintf('Stock insuffisant pour "%s" (disponible: %d, demandé: %d)', $equipment->getName(), $equipment->getQuantityAvailable(), $quantity));
            }
        }

        if (count($equipmentIdToQuantity) === 0) {
            throw new \InvalidArgumentException('Aucun article sélectionné.');
        }

        // Remove existing items
        foreach ($order->getItems()->toArray() as $existing) {
            $order->removeItem($existing);
            $this->entityManager->remove($existing);
        }

        // Apply decrements and add items
        foreach ($equipmentIdToQuantity as $equipmentId => $quantity) {
            if ($quantity <= 0) {
                continue;
            }
            $equipment = $this->equipmentRepository->find($equipmentId);
            $equipment->setQuantityAvailable($equipment->getQuantityAvailable() - $quantity);

            $item = new OrderItem();
            $item->setEquipment($equipment);
            $item->setQuantity($quantity);
            $item->setUnitPrice($equipment->getPricePerDay());
            $order->addItem($item);
        }

        $order->setCustomerName($customerName);
        $this->entityManager->flush();

        return $order;
    }

    public function calculateTotalPerDay(RentalOrder $order): string
    {
        $total = 0.0;
        foreach ($order->getItems() as $item) {
            $total += ((float)$item->getUnitPrice()) * $item->getQuantity();
        }
        return number_format($total, 2, '.', '');
    }
}


