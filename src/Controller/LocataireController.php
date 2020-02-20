<?php

namespace App\Controller;

use App\Entity\Locataire;
use App\Form\LocataireType;
use App\Repository\LocataireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocataireController extends AbstractController{
    /**
     * @var LocataireRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(LocataireRepository $repository, EntityManagerInterface $em)
    {
        $this->repository=$repository;
        $this->em=$em;
    }

    /**
     * @return Response
     * @Route("/Locataires", name="leslocataires.index")
     */
    public function index(){
        $locataires = $this->repository->findAll();
        return $this->render('leslocataires/index.html.twig',['locataire'=>$locataires]);
    }

    /**
     * @Route("/Locataire/create", name="leslocataires.new")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $locataire = new Locataire();
        $form = $this->createForm(LocataireType::class, $locataire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->em->persist($locataire);
            $this->em->flush();
            $this->addFlash('success','locataire crée avec succès');
            return $this->redirectToRoute('leslocataires.index');
        }
        return $this->render('leslocataires/new.html.twig', [
            'locataires' => $locataire,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/Locataires/edit/{id}", name="leslocataires.edit", methods={"GET|POST"})
     * @param Locataire $locataire
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function edit(Locataire $locataire, Request $request)
    {
        $form = $this->createForm(LocataireType::class, $locataire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->flush();
            $this->addFlash('success','locataire modifié avec succès');
            return $this->redirectToRoute('leslocataires/index.html.twig');
        }
        return $this->render('leslocataires/edit.html.twig',[
            'locataires'=>$locataire, 'form' =>$form->createView()
        ]);
    }

    /**
     * @Route("/Locataire/{id}", name="leslocataires.delete", methods="DELETE")
     * @param Locataire $locataire
     * @param Request $request
     * @return RedirectResponse
     */

    public function delete(Locataire $locataire, Request $request)
    {
        if ($this->isCsrfTokenValid($locataire->getId(), $request->get('_token')))
        {
            $this->em->remove($locataire);
            $this->em->flush();
            $this->addFlash('success','locataire supprimé avec succès');
        }
        return $this->redirectToRoute('leslocataires.index');
    }
}