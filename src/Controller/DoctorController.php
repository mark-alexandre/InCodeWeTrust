<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Patient;
use App\Form\AddPatientFormType;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/doctor", name="doctor_")
 */
class DoctorController extends AbstractController
{
    /**
     * @Route("/{id}", name="index")
     * @param PatientRepository $patients
     * @param Doctor $doctor
     * @return Response
     */

    public function index(PatientRepository $patients, Doctor $doctor, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(
            AddPatientFormType::class,$doctor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('doctor_index',['id'=>$doctor->getId()]);
        }
        return $this->render('admin/doctor/index.html.twig', [
            'myPatients' => $patients->findAll(),
            'doctor' => $doctor,
            'form'=>$form->createView(),
        ]);
    }
}

