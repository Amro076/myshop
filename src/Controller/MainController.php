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
use App\Service\CardService;
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
    #[Route('/main/commande' , name:"commande_product")]
    public function commande(EntityManagerInterface $manager, Request $request, ProductRepository $repo, CardService $cs)
    {       
            $cardWithData = $cs->getCardWithData();

           

            foreach ($cardWithData as $item) 
            
            $stock = $item['product']->getStock();
            
            {
                $commande =new Commande;

            $commande->setMembr($this->getUser());
            $commande->setcreatedAt(new \DateTime());
            $commande->setProduct($item ['product']);
            $commande->setQuantity($item['Quantity']);
            $commande->setEtat('en cours de traitement');
            
            $quantity = $item['Quantity'];
            
            $prixunitaire=$item['product']->getPrix();
            
            $montant =$quantity * $prixunitaire;

            $commande->setMontant($montant);
            
           
            $stock= $item['product']->getStock();
            if($stock == 0)
            {
               
                return $this->redirectToRoute("product_home");
                
            }else
            {
            $stock -= $item['Quantity'];
            $item['product']->setStock($stock);

            $manager->persist($commande); 
        
            $manager->flush();

            }
                               
           
            $this->get('session')->clear();
          
        }
            
        

            return $this-> redirectToRoute('product_home');

        

        
        

    }
    
    #[Route('/main/profil', name:"profil")]
    public function profil(CommandeRepository $repo)
    {
        $commandes = $repo->findBy(['membr'=>$this->getUser()]);

        return $this->render('main/profil.html.twig',[
            'commandes'=>$commandes
        ]);
    }
    #[Route('/portfoilo', name:'portfolio')]
    public function portfolio()
    {
      return $this->render("portfolio/index.html.twig");  
    }
    #[Route('/portfoilo/crea', name:'creation')]
    public function creation()
    {
      return $this->render("portfolio/création.html.twig");
    }

   


}
