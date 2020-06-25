<?php

namespace App\Controller;

use App\Entity\Report;
use App\Form\ReportType;
use App\Repository\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig');
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

        return $this->render('home/indexConnected.html.twig', [
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


        return $this->render('report/index.html.twig',
        [
         'reports' => $pastReport
        ]);
    }

}
