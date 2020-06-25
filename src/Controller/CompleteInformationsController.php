<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Patient;
use App\Form\CompleteInformationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompleteInformationsController extends AbstractController
{
    /**
     * @Route("/complete-your-account", name="complete_index")
     */
    public function index()
    {
        return $this->render('frontend/complete_informations/index.html.twig');
    }

    /**
     * @param Request $request
     * @param Patient $patient
     * @return Response
     * @Route("/complete-your-account/{id}", name="complete_form", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function completeInformations(Request $request, Patient $patient)
    {
        $form = $this->createForm(CompleteInformationType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home_connected');
        }


        return $this->render('frontend/complete_informations/edit.html.twig', [
        'user' => $patient,
        'formComplete' => $form->createView(),
    ]);
    }

    /**
     * @param Request $request
     * @param Doctor $doctor
     * @return Response
     * @Route("/complete-doctor-account/{id}", name="complete_form_doctor", methods={"GET", "POST"})
     */
    public function completeDoctorInformations(Request $request, Doctor $doctor)
    {
        $form = $this->createForm(CompleteInformationDoctorType::class, $doctor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home_connected');
        }

        return $this->render('complete_informations/edit.html.twig', [
            'user' => $doctor,
            'formCompleteDoctor' => $form->createView(),
        ]);
    }
}
