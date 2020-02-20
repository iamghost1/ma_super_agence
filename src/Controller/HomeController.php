<?php

namespace App\Controller;


use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController{

    /**
     * @var PropertyRepository
     */
    private $repository;

    public function __construct(PropertyRepository $repository)
    {
        $this->repository=$repository;
    }
    /**
     * @return Response
     * @Route("/", name="home")
     */
    public function index(){
        $property = $this->repository->findAll();
        return $this->render('pages/home.html.twig',['properties'=>$property]);
    }

    /**
     * @return Response
     * @Route("/pages/index", name="pages.index")
     */
    public function home(){
        return $this->render('pages/index.html.twig');
    }
}
