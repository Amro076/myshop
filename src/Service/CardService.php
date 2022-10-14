<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CardService
{
    private $repo;
    private $rs;

    // injection de dépendances hors d'un conttroller : constructeur
    public function __construct(ProductRepository $repo, RequestStack $rs)
    {
        $this->repo = $repo;
        $this->rs = $rs;
    }
    public function add($id)
    {
        //nous allons récupèrer ou crée une session grace à la class RequestStack
        $session = $this->rs->getSession();

        $card = $session->get('card', []);
        //je recupére l'attr de session 'cart' s'il existe ou un tableau vide
        
        if(!empty($card[$id]))
        {
            $card[$id]++;
            // équivaut $cart[$id] = $cart[$id] + 1;
        }
        else
        {
            $card[$id] = 1;
        }
       
        // dans mon tableau $cart, à la case $id, j'insére la valeur 1
        $session->set('card', $card);
        // je sauvegarde l'état de mon panier en session à l'attr de session 'cart'
        //dd($session->get('cart'));
    }
    public function remove($id)
    {
        $session =$this->rs->getSession();
        $card = $session->get('card',[]);
        
        // si l'id existe dans  $cart, je le supprime du tableau
        if (!empty($card[$id]))
        {
            unset($card[$id]);
        }
        
        $session->set('card', $card);

    }
    public function getCardWithData()
    {
        $session = $this->rs->getSession();
        $card =$session->get('card', []);
        //nous allons créeé un nouveau tableua qui contiendra des objet product et le quintité de chaque produit
        $cardWithData =[];

        foreach($card AS $id => $quantity)
        {
            $cardWithData[] = [
                'product' => $this->repo->find($id),
                'Quantity' => $quantity
            ];
        }
        return $cardWithData;
    }
    public function getTotal()
    {
        $cardWithData = $this->getCardWithData();
        $total = 0;
        foreach($cardWithData as $item)
        {
            
            $totalUnitaire = $item['product']->getPrix() * $item['Quantity'];
            $total = $total + $totalUnitaire;
            // équivaut à $total += $totalUnitaire
        } 
        return $total;
    }
   
}