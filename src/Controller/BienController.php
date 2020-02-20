<?php

namespace App\Controller;


use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BienController extends AbstractController{

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var PropertyRepository
     */
    private $repository;

    public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
    {
        $this->repository=$repository;
        $this->em=$em;
    }

    /**
     * @return Response
     * @Route("/Bien", name="lesbiens.index")
     */
    public function index(){
        $property = $this->repository->findAll();
        return $this->render('lesbiens/index.html.twig',['properties'=>$property]);
    }

    /**
     * @Route("/Property/create", name="lesbiens.new")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->em->persist($property);
            $this->em->flush();
            $this->addFlash('success','bien crée avec succès');
            return $this->redirectToRoute('lesbiens.index');
        }
        return $this->render('lesbiens/new.html.twig', [
            'properties' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/Property/edit/{id}", name="lesbiens.edit", methods={"GET|POST"})
     * @param Property $property
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function edit(Property $property, Request $request)
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->flush();
            $this->addFlash('success','bien modifié avec succès');
            return $this->redirectToRoute('lesbiens.index');
        }
        return $this->render('lesbiens/edit.html.twig',[
            'properties'=>$property, 'form' =>$form->createView()
        ]);
    }

    /**
     * @Route("/Property/{id}", name="lesbiens.delete", methods="DELETE")
     * @param Property $property
     * @param Request $request
     * @return RedirectResponse
     */

    public function delete(Property $property, Request $request)
    {
        if ($this->isCsrfTokenValid($property->getId(), $request->get('_token')))
        {
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('success','bien supprimé avec succès');
        }
        return $this->redirectToRoute('lesbiens.index');
    }
}