<?php

namespace App\Controller;

use DateTime;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    #[Route('/home', name:'produit_home')]
    public function home(ProductRepository $repo)
    {   
        $products = $repo->findAll();
        return $this->render('main/home.html.twig');
    }
    
    #[Route('/show/', name:'product_show')]
    public function show(Product $product)
    {   
        
        return $this->render('main/show.html.twig',[
            'product' => $product
        ]);
    }

    #[Route('/main/new', name:'product_new')]
    public function new(Request $request, EntityManagerInterface $manager,  )
    {
      
        
            $product = new Product;
          
        
        
        $form = $this->createForm(ProductType::class, $product);
        //dd($request);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() )
        {
            $product->setCreatedAt(new DateTime());
            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute('product_show',[
                'id'=> $product->getId()
            ]);

        }

        return $this->render("main/form.html.twig",[
            'formProduct' => $form->createView()
        ]);
    }



}
