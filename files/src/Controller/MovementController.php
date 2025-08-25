<?php
namespace App\Controller;

use App\Entity\Movement;
use App\Form\MovementType;
use App\Enum\MovementType as MT;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/movimenti')]
class MovementController extends AbstractController
{
    #[Route('/nuovo', name: 'movement_new', methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $movement = new Movement();
        $movement->setType(MT::EXPENSE); // default UI: Uscita (così mostra la categoria)

        $form = $this->createForm(MovementType::class, $movement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($movement);
            $em->flush();

            $this->addFlash('success', 'Movimento salvato con successo.');
            return $this->redirectToRoute('movement_new'); // resta sulla pagina di inserimento
        }

        return $this->render('movement/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
