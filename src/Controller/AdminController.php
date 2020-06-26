<?php

namespace App\Controller;

use messagingDoctor;
use App\Entity\Messaging;
use App\Entity\Patient;
use App\Form\MessagingType;
use App\Repository\DoctorRepository;
use App\Repository\MessagingRepository;

use App\Form\NotificationsType;
use App\Repository\NotificationsRepository;
use Doctrine\ORM\EntityManager;

use App\Repository\PatientRepository;
use DateTime;
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

    /**
     * @Route("/messages", name="messagery")
     * @param PatientRepository $patientRepository
     * @return Response
     */
    public function messages(PatientRepository $patientRepository) {
        $doctor = $this->getUser();
        $patients = $patientRepository->findAll();

        return $this->render('admin/messagery.html.twig', ['patients' => $patients]);
    }

    /**
     * @Route("/messages/{id}", name="messages")
     * @param Patient $patient
     * @param MessagingRepository $messagingRepository
     * @param Request $request
     * @param DoctorRepository $doctorRepository
     * @return Response
     */
    public function messagesPatient(Patient $patient, MessagingRepository $messagingRepository, Request $request, DoctorRepository $doctorRepository){
        $doctor = $this->getUser();
        $messages = $messagingRepository->findBy(array('patient' => $patient, 'doctor' => $doctor));

        $doctor = $doctorRepository->find($doctor);
        $messaging = new Messaging();
        $form = $this->createForm(MessagingType::class, $messaging);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $date = new DateTime('now');
            $messaging->setAuthor("doctor");
            $messaging->setPatient($patient);
            $messaging->setDoctor($doctor);
            $messaging->setDate($date);
            $entityManager->persist($messaging);
            $entityManager->flush();

            $id = $patient->getId();
            return $this->redirectToRoute("admin_messages", ['id' => $id]);
        }

        return $this->render('admin/messages.html.twig',
            ['messages' => $messages,
                'form' => $form->createView()]);
    }
}
