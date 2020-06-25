<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Patient;
use App\Form\CompleteInformationDoctorType;
use App\Form\CompleteInformationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompleteInformationsController extends AbstractController
{
    /**
     * @Route("/profile", name="complete_index")
     */
    public function index()
    {
        return $this->render('frontend/complete_informations/index.html.twig');
    }

    /**
     * @param Request $request
     * @param Patient $patient
     * @return Response
     * @Route("/profile/{id}", name="complete_form", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function completeInformations(Request $request, Patient $patient)
    {
        if (!$patient->getSocialNumber()){
            $form = $this->createForm(CompleteInformationType::class, $patient);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('profile_new_report', ['id' => $this->getUser()->getId()]);
            }


            return $this->render('frontend/complete_informations/edit.html.twig', [
                'user' => $patient,
                'formComplete' => $form->createView(),
            ]);
        }else {
            return $this->redirectToRoute('profile_new_report', ['id' => $this->getUser()->getId()]);
        }

    }

    /**
     * @param Request $request
     * @param Doctor $doctor
     * @return Response
     * @Route("/profile/doc/{id}", name="complete_form_doctor", methods={"GET", "POST"})
     */
    public function completeDoctorInformations(Request $request, Doctor $doctor)
    {
        if (!$doctor->getNumberLicense())
        {
            $form = $this->createForm(CompleteInformationDoctorType::class, $doctor);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('admin_home');
            }

            return $this->render('frontend/complete_informations/edit.html.twig', [
                'user' => $doctor,
                'formCompleteDoctor' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('admin_home');
        }

    }
}
