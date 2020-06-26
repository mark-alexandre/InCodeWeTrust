<?php

namespace App\Controller;

use App\Entity\Drugs;
use App\Entity\Patient;
use App\Form\DrugsType;
use App\Repository\DrugsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/drugs")
 */
class DrugsController extends AbstractController
{
    /**
     * @Route("/", name="drugs_index", methods={"GET"})
     */
    public function index(DrugsRepository $drugsRepository): Response
    {
        return $this->render('admin/drugs/index.html.twig', [
            'drugs' => $drugsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/new", name="drugs_new", methods={"GET","POST"})
     * @param Request $request
     * @param Patient $patient
     * @return Response
     */
    public function new(Patient $patient, Request $request): Response
    {
        $drug = new Drugs();

        $form = $this->createForm(DrugsType::class, $drug);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($drug);
            $drug->setPatient($patient);
            $entityManager->flush();

            return $this->redirectToRoute('drugs_show', [
                'id'=>$patient->getId()
            ]);
        }

        return $this->render('admin/drugs/new.html.twig', [
            'drug' => $drug,
            'patient' => $patient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="drugs_show", methods={"GET"})
     * @param Patient $patient
     * @return Response
     */
    public function show(Patient $patient): Response
    {
        $drugs = $patient->getDrugs();
        return $this->render('admin/drugs/show.html.twig', [
            'drugs' => $drugs,
            'patient' => $patient
        ]);
    }

    /**
     * @Route("/{id}/edit", name="drugs_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Drugs $drug): Response
    {
        $form = $this->createForm(DrugsType::class, $drug);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('drugs_index');
        }

        return $this->render('admin/drugs/edit.html.twig', [
            'drug' => $drug,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="drugs_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Drugs $drug): Response
    {
        if ($this->isCsrfTokenValid('delete'.$drug->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($drug);
            $entityManager->flush();
        }

        return $this->redirectToRoute('drugs_show', [
            'id'=> $drug->getPatient()->getId()
        ]);
    }
}
