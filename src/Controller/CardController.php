<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CardController extends AbstractController
{
    #[Route('/card', name: 'app_card')]
    public function index(RequestStack $rs, ProductRepository $repo): Response
    {
        $session = $rs->getSession();
        $card = $session->get('card',[]);
        $cardWithData = [];

       

        foreach ($card as $id => $quantity) 
        {
            $cardWithData[] = [
                'product' => $repo->find($id),
                'quantity' => $quantity
            ];
        }

        $total = 0;
        foreach ($cardWithData as $item) {

            $totalItem = $item['product']->getPrix() * $item['quantity'];
            $total += $totalItem;
        }
        return $this->render('card/index.html.twig', [
            'items' => $cardWithData,
            'total' => $total
        
        ]);

        

    }

    
    #[Route('/card/add/{id}', name:'card_add')]
    public function add($id, RequestStack $rs)
    {
        $session = $rs->getSession();

        $card =$session->get('card', []);

    if(!empty($card[$id]))
        $card[$id]++;
    else
        $card[$id] =1;

        $session->set('card', $card);

        return $this->redirectToRoute('app_card');

    }

    #[Route('/card/remove/{id}', name:'card_remove')]
    public function remove($id, RequestStack $rs)
    {
        $session =$rs->getSession();
        $card = $session->get('card',[]);
        
        // si l'id existe dans  $cart, je le supprime du tableau
        if (!empty($card[$id]))
        {
            unset($card[$id]);
        }
        
        $session->set('card', $card);
        return $this->redirectToRoute('app_card');
    }

    

   
}
