<?php

namespace App\Controller;

use App\Entity\Messaging;
use App\Form\MessagingType;
use App\Repository\DoctorRepository;
use App\Repository\MessagingRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param Request $request
     * @param MessagingRepository $messagingRepository
     * @return RedirectResponse|Response
     */
    public function index(Request $request, MessagingRepository $messagingRepository, UserRepository $userRepository, DoctorRepository $doctorRepository)
    {
        $messaging = new Messaging();
        $form = $this->createForm(MessagingType::class, $messaging);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $patient = $userRepository->find(1);
            $doctor = $doctorRepository->find(1);
            $date = new DateTime('now');
            $messaging->setAuthor("patient");
            $messaging->setPatient($patient);
            $messaging->setDoctor($doctor);
            $messaging->setDate($date);
            $entityManager->persist($messaging);
            $entityManager->flush();
            unset($form);
            unset($messaging);
            $messaging = new Messaging();
            $form = $this->createForm(MessagingType::class, $messaging);
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
