<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CompleteInformationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompleteInformationsController extends AbstractController
{
    /**
     * @Route("/complete-your-account", name="complete_index")
     */
    public function index()
    {
        return $this->render('complete_informations/index.html.twig');
    }

    /**
     * @param Request $request
     * @param User $user
     * @return Response
     * @Route("/complete-your-account/{id}", name="complete_form", methods={"GET", "POST"})
     */
    public function completeInformations(Request $request, User $user)
    {
        $form = $this->createForm(CompleteInformationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home_connected');
        }
        return $this->render('complete_informations/edit.html.twig', [
        'user' => $user,
        'formComplete' => $form->createView(),
    ]);
    }
}
