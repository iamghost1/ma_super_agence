<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeController extends AbstractController{

    /**
     * @var EmployeeRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EmployeeRepository $repository, EntityManagerInterface $em)
    {
        $this->repository=$repository;
        $this->em=$em;
    }

    /**
     * @return Response
     * @Route("/Employes", name="lesemployes.index")
     */
    public function index(){
        $employees = $this->repository->findAll();
        return $this->render('lesemployes/index.html.twig',['employee'=>$employees]);
    }

    /**
     * @Route("/Employes/create", name="lesemployes.new")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->em->persist($employee);
            $this->em->flush();
            $this->addFlash('success','employé(e) crée avec succès');
            return $this->redirectToRoute('lesemployes.index');
        }
        return $this->render('lesemployes/new.html.twig', [
            'employees' => $employee,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/Employes/edit/{id}", name="lesemployes.edit", methods={"GET|POST"})
     * @param Employee $employee
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function edit(Employee $employee, Request $request)
    {
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->flush();
            $this->addFlash('success','employé(e) modifié avec succès');
            return $this->redirectToRoute('lesemployes.index');
        }
        return $this->render('lesemployes/edit.html.twig',[
            'employees'=>$employee, 'form' =>$form->createView()
        ]);
    }

    /**
     * @Route("/Employes/{slug}-{id}", name="lesemployes.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Employee $employee
     * @param string $slug
     * @return Response
     */
    public function show(Employee $employee,string $slug):Response
    {
        if ($employee->getSlug() !== $slug)
        {
            return $this->redirectToRoute('lesemployes.show',[
                'id'=> $employee->getId(),
                'slug' => $employee->getSlug()
            ],301);
        }
        return $this->render('lesemployes/show.html.twig',[
            'employee' => $employee
        ]);
    }

    /**
     * @Route("/Employes/{id}", name="lesemployes.delete", methods="DELETE")
     * @param Employee $employee
     * @param Request $request
     * @return RedirectResponse
     */

    public function delete(Employee $employee, Request $request)
    {
        if ($this->isCsrfTokenValid($employee->getId(), $request->get('_token')))
        {
            $this->em->remove($employee);
            $this->em->flush();
            $this->addFlash('success','employé(e) supprimé avec succès');
        }
        return $this->redirectToRoute('lesemployes.index');
    }
}