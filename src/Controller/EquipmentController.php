<?php

namespace App\Controller;

use App\Entity\Equipment;
use App\Form\EquipmentType;
use App\Repository\EquipmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/equipment')]
class EquipmentController extends AbstractController
{
    #[Route('/', name: 'equipment_index', methods: ['GET'])]
    public function index(EquipmentRepository $equipmentRepository): Response
    {
        return $this->render('equipment/index.html.twig', [
            'equipments' => $equipmentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'equipment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $equipment = new Equipment();
        $form = $this->createForm(EquipmentType::class, $equipment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($equipment);
            $em->flush();
            $this->addFlash('success', 'Matériel créé.');
            return $this->redirectToRoute('equipment_index');
        }

        return $this->render('equipment/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'equipment_edit', methods: ['GET', 'POST'])]
    public function edit(Equipment $equipment, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EquipmentType::class, $equipment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Matériel modifié.');
            return $this->redirectToRoute('equipment_index');
        }

        return $this->render('equipment/edit.html.twig', [
            'form' => $form,
            'equipment' => $equipment,
        ]);
    }
}


