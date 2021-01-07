<?php

namespace App\Controller;

use App\Entity\Requests;
use App\Entity\User;
use App\Form\RequestsType;
use App\Repository\RequestsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/request")
 */
class RequestsController extends AbstractController
{
    /**
     * @Route("/", name="request_index", methods={"GET"})
     */
    public function index(RequestsRepository $requestRepository): Response
    {
        return $this->render('request/index.html.twig', [
            'requests' => $requestRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="request_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserInterface $user): Response
    {
        $requests = new Requests();
        dd($user);
        $form = $this->createForm(RequestsType::class, $requests);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($requests);
            $entityManager->flush();
            return $this->redirectToRoute('request_index');
        }

        return $this->render('request/new.html.twig', [
            'requests' => $requests,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="request_show", methods={"GET"})
     */
    public function show(Requests $requests): Response
    {
        return $this->render('request/show.html.twig', [
            'requests' => $requests,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="request_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Requests $requests): Response
    {
        $form = $this->createForm(RequestsType::class, $requests);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('request_index');
        }

        return $this->render('request/edit.html.twig', [
            'requests' => $requests,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="request_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Requests $requests): Response
    {
        if ($this->isCsrfTokenValid('delete'.$requests->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($requests);
            $entityManager->flush();
        }

        return $this->redirectToRoute('request_index');
    }
}
