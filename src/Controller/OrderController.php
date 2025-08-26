<?php

namespace App\Controller;

use App\Entity\OrderItem;
use App\Entity\RentalOrder;
use App\Form\OrderItemType;
use App\Service\RentalService;
use App\Repository\RentalOrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/orders')]
class OrderController extends AbstractController
{
    public function __construct(private readonly RentalService $rentalService)
    {
    }

    #[Route('/', name: 'order_index', methods: ['GET'])]
    public function index(RentalOrderRepository $repository): Response
    {
        return $this->render('order/index.html.twig', [
            'orders' => $repository->findBy([], ['id' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'order_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $customerName = (string) $request->request->get('customerName');
            /** @var array<int,array<string,mixed>> $items */
            $items = $request->request->all('items');
            if (!is_array($items)) {
                $items = [];
            }
            $equipmentIdToQuantity = [];
            foreach ($items as $itemData) {
                $equipmentId = (int)($itemData['equipment'] ?? 0);
                $quantity = (int)($itemData['quantity'] ?? 0);
                if ($equipmentId > 0 && $quantity > 0) {
                    $equipmentIdToQuantity[$equipmentId] = ($equipmentIdToQuantity[$equipmentId] ?? 0) + $quantity;
                }
            }
            try {
                $order = $this->rentalService->createOrder($customerName, $equipmentIdToQuantity);
                $this->addFlash('success', 'Commande créée.');
                return $this->redirectToRoute('order_show', ['id' => $order->getId()]);
            } catch (\Throwable $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        // Render a small dynamic form with 3 item rows by default
        $prototype = $this->createForm(OrderItemType::class, new OrderItem())->createView();

        return $this->render('order/new.html.twig', [
            'prototype' => $prototype,
        ]);
    }

    #[Route('/{id}', name: 'order_show', methods: ['GET'])]
    public function show(RentalOrder $order): Response
    {
        $total = $this->rentalService->calculateTotalPerDay($order);
        return $this->render('order/show.html.twig', [
            'order' => $order,
            'total' => $total,
        ]);
    }

    #[Route('/{id}/edit', name: 'order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RentalOrder $order): Response
    {
        if ($request->isMethod('POST')) {
            $customerName = (string) $request->request->get('customerName');
            /** @var array<int,array<string,mixed>> $items */
            $items = $request->request->all('items');
            if (!is_array($items)) {
                $items = [];
            }
            $equipmentIdToQuantity = [];
            foreach ($items as $itemData) {
                $equipmentId = (int)($itemData['equipment'] ?? 0);
                $quantity = (int)($itemData['quantity'] ?? 0);
                if ($equipmentId > 0 && $quantity > 0) {
                    $equipmentIdToQuantity[$equipmentId] = ($equipmentIdToQuantity[$equipmentId] ?? 0) + $quantity;
                }
            }
            try {
                $this->rentalService->updateOrder($order, $customerName, $equipmentIdToQuantity);
                $this->addFlash('success', 'Commande mise à jour.');
                return $this->redirectToRoute('order_show', ['id' => $order->getId()]);
            } catch (\Throwable $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        $prototype = $this->createForm(OrderItemType::class, new OrderItem())->createView();

        return $this->render('order/edit.html.twig', [
            'order' => $order,
            'prototype' => $prototype,
        ]);
    }
    #[Route('/{id}/return', name: 'order_return', methods: ['POST'])]
    public function return(RentalOrder $order): Response
    {
        $this->rentalService->returnOrder($order);
        $this->addFlash('success', 'Commande retournée, stock rétabli.');
        return $this->redirectToRoute('order_index');
    }
}


