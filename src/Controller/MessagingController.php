<?php

namespace App\Controller;

use App\Entity\Messaging;
use App\Form\MessagingType;
use App\Repository\DoctorRepository;
use App\Repository\MessagingRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/messaging")
 */
class MessagingController extends AbstractController
{
    /**
     * @Route("/", name="messaging_index", methods={"GET","POST"})
     * @param MessagingRepository $messagingRepository
     * @return Response
     */
    public function index(MessagingRepository $messagingRepository): Response
    {
        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
            $patient = 1;

        $messagings = $messagingRepository->findBy(array("patient" => $patient), null, 10);
        $jsonMessages = $serializer->serialize($messagings, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        return new Response($jsonMessages, 200, ['Content-Type' => 'application/json']);
    }

//
//    /**
//     * @Route("/new", name="messaging_new", methods={"GET","POST"})
//     * @param Request $request
//     * @param UserRepository $userRepository
//     * @param DoctorRepository $doctorRepository
//     * @return Response
//     */
//    public function new(Request $request, UserRepository $userRepository, DoctorRepository $doctorRepository): Response
//    {
//        $messaging = new Messaging();
//        $form = $this->createForm(MessagingType::class, $messaging);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $patient = $userRepository->find(1);
//            $doctor = $doctorRepository->find(1);
//            $date = new DateTime('now');
//            $messaging->setAuthor("patient");
//            $messaging->setPatient($patient);
//            $messaging->setDoctor($doctor);
//            $messaging->setDate($date);
//            $entityManager->persist($messaging);
//            $entityManager->flush();
//        }
//
//        return $this->render('messaging/new.html.twig', [
//            'messaging' => $messaging,
//            'form' => $form->createView(),
//        ]);
//    }

    /**
     * @Route("/{id}", name="messaging_show", methods={"GET"})
     */
    public function show(Messaging $messaging): Response
    {
        return $this->render('messaging/show.html.twig', [
            'messaging' => $messaging,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="messaging_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Messaging $messaging): Response
    {
        $form = $this->createForm(MessagingType::class, $messaging);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('messaging_index');
        }

        return $this->render('messaging/edit.html.twig', [
            'messaging' => $messaging,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="messaging_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Messaging $messaging): Response
    {
        if ($this->isCsrfTokenValid('delete'.$messaging->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($messaging);
            $entityManager->flush();
        }

        return $this->redirectToRoute('messaging_index');
    }
}
