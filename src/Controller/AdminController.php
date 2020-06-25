<?php

namespace App\Controller;

use App\Form\NotificationsType;
use App\Repository\DoctorRepository;
use App\Repository\NotificationsRepository;
use Doctrine\ORM\EntityManager;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param NotificationsRepository $notificationsRepository
     * @param DoctorRepository $doctorRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function index(NotificationsRepository $notificationsRepository,
                          DoctorRepository $doctorRepository,
                          EntityManagerInterface $em)
    {
        $doctor = $this->getUser();
        $notifs = $notificationsRepository->findBy(array('doctor' =>$doctor->getDoctor(), 'state'=>'waiting'), null, 15);
        $result = $notificationsRepository->countNotifs($doctor->getDoctor()->getId());
        if ($result == null)
        {
            $number = "0";
        } else {
            $number = $result;
        }

        return $this->render('admin/index.html.twig', [
            'notifs' => $notifs,
            'number' => $number
        ]);
    }

    /**
     * @Route("/patients/", name="patients")
     */
    public function admin_patients(DoctorRepository $doctor):response
    {

    }
}
