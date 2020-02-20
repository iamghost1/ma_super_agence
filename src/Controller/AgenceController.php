<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Form\AgenceType;
use App\Repository\AgenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgenceController extends AbstractController{

    /**
     * @var AgenceRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(AgenceRepository $repository, EntityManagerInterface $em)
    {
        $this->repository=$repository;
        $this->em=$em;
    }

    /**
     * @return Response
     * @Route("/Agences", name="lesagences.index")
     */
    public function index(){
        $agences = $this->repository->findAll();
        return $this->render('lesagences/index.html.twig',['agence'=>$agences]);
    }

    /**
     * @Route("/Agence/create", name="lesagences.new")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $agence = new Agence();
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->em->persist($agence);
            $this->em->flush();
            $this->addFlash('success','agence crée avec succès');
            return $this->redirectToRoute('lesagences.index');
        }
        return $this->render('lesagences/new.html.twig', [
            'agences' => $agence,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/Agence/edit/{id}", name="lesagences.edit", methods={"GET|POST"})
     * @param Agence $agence
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function edit(Agence $agence, Request $request)
    {
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->flush();
            $this->addFlash('success','agence modifié avec succès');
            return $this->redirectToRoute('lesagences.index');
        }
        return $this->render('lesagences/edit.html.twig',[
            'agences'=>$agence, 'form' =>$form->createView()
        ]);
    }

    /**
     * @Route("/Agence/{id}", name="lesagences.delete", methods="DELETE")
     * @param Agence $agence
     * @param Request $request
     * @return RedirectResponse
     */

    public function delete(Agence $agence, Request $request)
    {
        if ($this->isCsrfTokenValid($agence->getId(), $request->get('_token')))
        {
            $this->em->remove($agence);
            $this->em->flush();
            $this->addFlash('success','agence supprimé avec succès');
        }
        return $this->redirectToRoute('lesagences.index');
    }
}