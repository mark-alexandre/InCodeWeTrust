<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Repository\PatientRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/doctor", name="doctor_")
 */
class DoctorController extends AbstractController
{
    /**
     * @Route("/{id}", name="index")
     * @param PatientRepository $patients
     * @param Doctor $doctor
     * @return Response
     */
    public function index(PatientRepository $patients, Doctor $doctor)
    {

        return $this->render('admin/doctor/index.html.twig', [
            'myPatients' => $patients->findBy(['doctor'=>$doctor->getId()]),
            'doctor' => $doctor
        ]);
    }

    /**
     * @Route("/add-patient/{id}", name="add_patient")
     * @param Request $request
     * @param PatientRepository $patientRepository
     * @param Doctor $doctor
     * @return Response
     */
    public function addPatient(Request $request, PatientRepository
    $patientRepository, Doctor $doctor)
    {
        return $this->render('admin/doctor/addPatient.html.twig', [
            'allPatients' => $patientRepository->findAll(),
            'doctor' => $doctor
        ]);
    }
}
