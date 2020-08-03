<?php


namespace App\Controller;


use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $propertyRepository;

    //
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(PropertyRepository $propertyRepository,  EntityManagerInterface $em)
    {

        $this->propertyRepository = $propertyRepository;
        $this->em = $em;
    }

    /**
     * @Route("/biens", name="property.index")
     * @param PaginationInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request) : Response
    {
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);
        $properties = $paginator->paginate(
            $this->propertyRepository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            12
        );
        /*$property = new Property();
        $property->setTitle('Mon premier bien')
            ->setPrice(200000)
            ->setRooms(4)
            ->setBedrooms(3)
            ->setDescription("une petite description")
            ->setSurface(60)
            ->setFloor(4)
            ->setHeat(1)
            ->setCity("Montpellier")
            ->setAddress("15 quai gambetta")
            ->setPostalCode("34000");
                //to save or update on DB
            $em = $this->getDoctrine()->getManager();
            $em->persist($property);
            $em->flush();*/
        // Repository
        //$propertyRepository = $this->getDoctrine()->getRepository(Property::class);
        //$property = $this->propertyRepository->findBy(['floor' => 4]);
       //$property = $this->propertyRepository->findALlVisible();
       /* $property[0]->setSold(true);*/
       /* $this->em->flush();*/

        return $this->render("property/index.html.twig", [
            'current_menu' => 'properties',
            'properties' => $properties,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug":"[a-z0-9\-]*"})
     * @param Property $property
     * @return Response
     */
    // au lieu de faire show($lug, $id) on peut faire Property $property et il fera tout seul l'appele au repository
    public function show(Property $property, string $slug): Response
    {
        if ($property->getSlug() !== $slug)
        {
            return $this->redirectToRoute('property.show',
                [
                    'id' => $property->getId(),
                    'slug' => $property->getSlug()
                ], 301);
        }
        //$property = $this->propertyRepository->find($id);
        return $this->render("property/show.html.twig", [
            "property" => $property,
            "current_menu" => "properties"
        ]);
    }

}