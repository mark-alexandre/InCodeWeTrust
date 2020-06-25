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
     * @return RedirectResponse|Response
     */
    public function index(Request $request)
    {
        if ($this->getUser()){
            return $this->redirectToRoute('home_connected');
        }
        return $this->render('frontend/index.html.twig');
    }

    /**
     * @Route("/connected", name="home_connected")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param MessagingRepository $messagingRepository
     * @param UserRepository $userRepository
     * @param DoctorRepository $doctorRepository
     * @return RedirectResponse|Response
     */
    public function indexConnected(Request $request, EntityManagerInterface $entityManager, MessagingRepository $messagingRepository, UserRepository $userRepository, DoctorRepository $doctorRepository)
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

        $messaging = new Messaging();
        $formChat = $this->createForm(MessagingType::class, $messaging);
        $formChat->handleRequest($request);

        if ($formChat->isSubmitted() && $formChat->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
                $patient = $this->getUser();
            $patient = $patient->getPatient();
            $doctor = $patient->getDoctor();

            $date = new DateTime('now');
            $messaging->setAuthor("patient");
            $messaging->setPatient($patient);
            $messaging->setDoctor($doctor);
            $messaging->setDate($date);
            $entityManager->persist($messaging);
            $entityManager->flush();
            unset($formChat);
            unset($messaging);
            $messaging = new Messaging();
            $formChat = $this->createForm(MessagingType::class, $messaging);
        }

        return $this->render('frontend/indexConnected.html.twig', [
            'messaging' => $messaging,
            'form' => $form->createView(),
            'formChat' => $formChat->createView(),
            'messagings' =>         $messagings = $messagingRepository->findBy(array("patient" => 1), null, 10)

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
