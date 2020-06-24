<?php

namespace App\Controller;

use App\Entity\Messaging;
use App\Form\MessagingType;
use App\Repository\MessagingRepository;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param Request $request
     * @param MessagingRepository $messagingRepository
     * @return RedirectResponse|Response
     */
    public function index(Request $request, MessagingRepository $messagingRepository)
    {
        $messaging = new Messaging();
        $form = $this->createForm(MessagingType::class, $messaging);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($messaging);
            $entityManager->flush();
        }

        return $this->render('home/index.html.twig', [
            'messaging' => $messaging,
            'form' => $form->createView(),
            'messagings' => $messagingRepository->findAll()
        ]);
    }

    /**
     * @Route("/connected", name="home_connected")
     */
    public function indexConnected()
    {
        return $this->render('home/indexConnected.html.twig');
    }
}
