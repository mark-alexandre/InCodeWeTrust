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
use App\Entity\Report;
use App\Form\ReportType;
use App\Repository\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param Request $request
     * @param MessagingRepository $messagingRepository
     * @param UserRepository $userRepository
     * @param DoctorRepository $doctorRepository
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

        return $this->render('frontend/index.html.twig', [
            'messaging' => $messaging,
            'form' => $form->createView(),
            'messagings' => $messagingRepository->findAll()
        ]);
        return $this->render('frontend/index.html.twig');
    }

    /**
     * @Route("/connected", name="home_connected")
     */
    public function indexConnected(Request $request, EntityManagerInterface $entityManager)
    {
        $report = new Report();
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $report->setUser($this->getUser());
            $entityManager->persist($report);
            $entityManager->flush();

            return $this->redirectToRoute('home_connected');
        }

        return $this->render('frontend/indexConnected.html.twig', [
            'form'=>$form->createView(),
        ]);

    }

    /**
     * @param ReportRepository $reportRepository
     * @return Response
     * @Route("/past-report", name="past_report")
     */
    public function showAllReport(ReportRepository $reportRepository):Response
    {
        $userId = $this->getUser()->getId();

        if($reportRepository->findBy(['user'=>$userId]))
        {
            $pastReport = $reportRepository->findBy(['user'=>$userId]);
        } else {
            $pastReport = 'There is no past report';
        }


        return $this->render('frontend/report/index.html.twig',
        [
         'reports' => $pastReport
        ]);
    }

}
