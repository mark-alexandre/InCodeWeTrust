<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\User;
use App\Form\AddPatientType;
use App\Repository\UserRepository;
use App\Service\SearchBar;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/doctor", name="doctor_")
 */
class DoctorController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(UserRepository $users)
    {
        return $this->render('doctor/index.html.twig', [
            'myPatients' => $users->findBy(['doctor'=>$this->getUser()->getId()])
        ]);
    }

    /**
     * @Route("/add-patient", name="add_patient")
     */
    public function addPatient(Request $request, UserRepository $userRepository)
    {

        return $this->render('doctor/addPatient.html.twig', [
            'allPatients' => $userRepository->findAll(),
        ]);
    }

}
