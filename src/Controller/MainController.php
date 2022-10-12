<?php

namespace App\Controller;

use DateTime;
use App\Entity\Membre;
use App\Entity\Product;
use App\Entity\Commande;
use App\Form\ProductType;
use App\Form\CommandeType;
use App\Repository\MembreRepository;
use App\Repository\ProductRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    #[Route('/home', name:'product_home')]
    public function home(ProductRepository $repo)
    {   
        $products = $repo->findAll();
        return $this->render('main/home.html.twig',[
            'products' => $products
        ]);
    }
    
    #[Route('/show/{id}', name:'product_show')]
    public function show($id, ProductRepository $repo, Product $product)
    {   
        $product = $repo->find($id);
        return $this->render('main/show.html.twig',[
            'product' => $product
        ]);
    }

    #[Route('/main/new', name:'product_new')]
    public function new(Request $request, EntityManagerInterface $manager  )
    {
            $product = new Product;
        
        $form = $this->createForm(ProductType::class, $product);
        
        $form->handleRequest($request);
        //dd($request);
        if($form->isSubmitted() && $form->isValid() )
        {
            $product->setCreatedAt(new DateTime());
            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute('product_home',[
                'id' => $product->getId()
            ]);

        }

        return $this->render("main/form.html.twig",[
            'formProduct' => $form->createView()
        ]);
    }
    // #[Route('/main/commande/{id}' , name:"commande_product")]
    // public function commande(EntityManagerInterface $manager, Request $request, ProductRepository $repo, $id)
    // {
    //         $commande =new Commande;
    //         $product = $repo->find($id);
            
    //         $form = $this->createForm(CommandeType::class,$commande);
    //         //dd($request);
    //         $form->handleRequest($request);
            
    //         if($form->isSubmitted() && $form->isValid())
    //     {
    //         $commande->setMembr($this->getUser());
    //         $commande->setcreatedAt(new \DateTime());
    //         $commande->setProduct($product);


    //         $manager->persist($commande); 
    //         $manager->flush();
    //         $this->addFlash('success', "Votre commande est en cours de traitement");
    //         return $this-> redirectToRoute('app_main');

    //     }
    //     return $this->renderForm('locvoiture/commande.html.twig', [
    //         'formCommande' => $form
    //     ]);

    // }
    
    #[Route('/main/profil', name:"profil")]
    public function profil(CommandeRepository $repo)
    {
        $commandes = $repo->findBy(['membr'=>$this->getUser()]);

        return $this->render('main/profil.html.twig',[
            'commandes'=>$commandes
        ]);
    }

   





  





}
