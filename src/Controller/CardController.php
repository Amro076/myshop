<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Service\CardService;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CardController extends AbstractController
{
    #[Route('/card', name: 'app_card')]
    public function index(CardService $cs): Response
    {
        $cardWithData = $cs->getCardWithData();
        $total = $cs->getTotal();


        return $this->render('card/index.html.twig', [
            'items' => $cardWithData,
            'total' => $total
        ]);
    }
    #[Route('/card/add/{id}', name:"card_add")]
    public function add($id, CardService $cs)
    {
        
        $cs->add($id);

        return $this->redirectToRoute('app_card');
    }

    #[Route('/cart/remove/{id}', name:'card_remove')]
    public function remove($id, CardService $cs)
    {
        $cs->remove($id);
        return $this->redirectToRoute('app_card');
    }

   




    


    
}
       
   


   

