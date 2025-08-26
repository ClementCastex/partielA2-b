<?php

namespace App\Controller;

use App\Repository\EquipmentRepository;
use App\Repository\RentalOrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home_index', methods: ['GET'])]
    public function index(EquipmentRepository $equipmentRepository, RentalOrderRepository $orderRepository): Response
    {
        $equipmentCount = $equipmentRepository->count([]);
        $openOrders = $orderRepository->count(['status' => \App\Entity\RentalOrder::STATUS_OPEN]);

        return $this->render('home/index.html.twig', [
            'equipmentCount' => $equipmentCount,
            'openOrders' => $openOrders,
        ]);
    }
}
