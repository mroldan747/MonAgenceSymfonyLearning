<?php


namespace App\Controller;


use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{


    /**
     * @Route("/", name="home")
     * @return Response
     * @param PropertyRepository $propertyRepository
     */
    public function index(PropertyRepository $propertyRepository):Response
    {
        $properties = $propertyRepository->findLatest();
        dump($properties);
        return $this->render('pages/home.html.twig', [
            'properties' => $properties
        ]);
    }


}