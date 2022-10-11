<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\BrowserKit\Request;
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
        $product = $repo->findAll();
        return $this->render('main/home.html.twig');
    }
    
    // #[Route('/show/', name:'product_show')]
    // public function show(Product $product)
    // {   
        
    //     return $this->render('main/show.html.twig',[
    //         'product' => $product
    //     ]);
    // }

    #[Route('/main/new', name:'product_new')]
    public function new()
    {
        return $this->render("main/form.html.twig");
    }



}
