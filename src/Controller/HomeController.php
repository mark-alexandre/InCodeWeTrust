<?php

namespace App\Controller;

use App\Entity\Messaging;
use App\Entity\Notifications;
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
     * @param ReportRepository $reportRepository
     * @param MessagingRepository $messagingRepository
     * @param UserRepository $userRepository
     * @param DoctorRepository $doctorRepository
     * @return RedirectResponse|Response
     */
    public function indexConnected(Request $request,
                                   EntityManagerInterface $entityManager,
                                   ReportRepository $reportRepository,
                                   MessagingRepository $messagingRepository,
                                   UserRepository $userRepository,
                                   DoctorRepository $doctorRepository)
    {
        $report = new Report();
        $patient = $this->getUser();
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);
        $today = new DateTime('now');

        if($form->isSubmitted() && $form->isValid()) {

            $report->setUser($patient);
            $entityManager->persist($report);

            $hasDoctor = $this->getUser()->getPatient()->getDoctor();
            if ($hasDoctor != null) {
                $notif = new Notifications();
                $notif->setReport($report);
                $notif->setDate($today);
                $patientN = $patient->getPatient();
                $notif->setPatient($patientN);
                $doctor = $patientN->getDoctor();
                $notif->setDoctor($doctor[0]);
                if($report->getResult() < 135 ) {
                    $notif->setType("success");
                } else if ($report->getResult() > 180 ) {
                    $notif->setType("danger");
                    $notif->setState("waiting");
                } else {
                    $notif->setType("warning");
                    $notif->setState("waiting");
                }
                $entityManager->persist($notif);

            }

            $entityManager->flush();
            return $this->redirectToRoute('home_connected');

        }

        $reportQuodidien = $reportRepository->findOneBy(array('user' => $patient), array('id' => "DESC"));
        if ($reportQuodidien != null) {
            $dateReport = $reportQuodidien->getDate()->format('YY "/" mm "/" dd');

            if( $dateReport != $today->format('YY "/" mm "/" dd') ) {
                $this->addFlash('danger', 'Pensez à remplir votre rapport Quotidien!');
            } else {
                $this->addFlash('success', "C'est parfait pour aujourd'hui! Vous avez déjà rempli votre rapport");
            }
        } else {
                $this->addFlash('danger', 'Pensez à remplir votre rapport Quotidien! Il faut commencer maintenant');
        }

        $patient = $patient->getPatient();

        $messaging = new Messaging();
        $formChat = $this->createForm(MessagingType::class, $messaging);
        $formChat->handleRequest($request);

        if ($formChat->isSubmitted() && $formChat->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $doctor = $patient->getDoctor();

            $date = new DateTime('now');
            $messaging->setAuthor("patient");
            $messaging->setPatient($patient);
            $messaging->setDoctor($doctor[0]);
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
            'messagings' => $messagings = $messagingRepository->findBy(array("patient" => 1), null, 10)

        ]);

    }

    /**
     * @param ReportRepository $reportRepository
     * @return Response
     * @Route("/past-report", name="past_report")
     */
    public function showAllReport(ReportRepository $reportRepository, Request $request):Response
    {
        $userId = $this->getUser()->getPatient()->getId();

        if($reportRepository->findBy(['user'=>$userId]))
        {
            $pastReport = $reportRepository->findBy(['patient'=>$userId]);
        } else {
            $pastReport = 'There is no past report';
        }

        $hasDoctor = $this->getUser()->getPatient()->getDoctor();
        if ($hasDoctor != null ) {
            $messaging = new Messaging();
            $formChat = $this->createForm(MessagingType::class, $messaging);
            $formChat->handleRequest($request);

            if ($formChat->isSubmitted() && $formChat->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $patient = $this->getUser()->getPatient();
                $doctor = $patient->getDoctor();

                $date = new DateTime('now');
                $messaging->setAuthor("patient");
                $messaging->setPatient($patient);
                $messaging->setDoctor($doctor[0]);
                $messaging->setDate($date);
                $entityManager->persist($messaging);
                $entityManager->flush();
                unset($formChat);
                unset($messaging);
                $messaging = new Messaging();
                $formChat = $this->createForm(MessagingType::class, $messaging);
            }
            return $this->render('frontend/report/index.html.twig',
                [
                    'reports' => $pastReport,
                    'formChat' => $formChat->createView()
                ]);
        }

        return $this->render('frontend/report/index.html.twig',
        [
            'reports' => $pastReport
        ]);
    }
}
