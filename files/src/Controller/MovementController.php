<?php
namespace App\Controller;

use App\Entity\Movement;
use App\Form\MovementType;
use App\Enum\MovementType as MT;
use App\Repository\CategoryRepository;
use App\Repository\MovementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/movimenti')]
class MovementController extends AbstractController
{
    #[Route('/', name: 'movement_index', methods: ['GET'])]
    public function index(
        Request $request,
        MovementRepository $movementRepo,
        CategoryRepository $categoryRepo
    ): Response {
        $categoryId = $request->query->get('category'); // null o id
        $category = $categoryId ? $categoryRepo->find($categoryId) : null;

        $movements = $movementRepo->findByFilter(category: $category);

        return $this->render('movement/index.html.twig', [
            'movements' => $movements,
            'categories' => $categoryRepo->findBy([], ['name' => 'ASC']),
            'selectedCategory' => $category?->getId(),
        ]);
    }

    #[Route('/nuovo', name: 'movement_new', methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $movement = new Movement();
        $movement->setType(MT::EXPENSE);

        $form = $this->createForm(MovementType::class, $movement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($movement);
            $em->flush();

            $this->addFlash('success', 'Movimento salvato con successo.');
            // ➜ dopo il salvataggio, vai al riepilogo
            return $this->redirectToRoute('movement_index');
        }

        // 🔹 Mostra gli errori del form come flash di debug
        if ($form->isSubmitted() && !$form->isValid()) {
            foreach ($form->getErrors(true) as $error) {
                $this->addFlash('danger', $error->getMessage());
            }
        }

        return $this->render('movement/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/modifica', name: 'movement_edit', methods: ['GET','POST'])]
    public function edit(Movement $movement, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(MovementType::class, $movement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Movimento aggiornato.');
            return $this->redirectToRoute('movement_index');
        }

        return $this->render('movement/edit.html.twig', [
            'form' => $form->createView(),
            'movement' => $movement,
        ]);
    }

    #[Route('/{id}', name: 'movement_delete', methods: ['POST'])]
    public function delete(Movement $movement, Request $request, EntityManagerInterface $em): Response
    {
        $csrfId = 'delete_movement_'.$movement->getId();
        if ($this->isCsrfTokenValid($csrfId, $request->request->get('_token'))) {
            $em->remove($movement);
            $em->flush();
            $this->addFlash('success', 'Movimento eliminato.');
        } else {
            $this->addFlash('danger', 'Token CSRF non valido. Riprova.');
        }

        return $this->redirectToRoute('movement_index');
    }
}