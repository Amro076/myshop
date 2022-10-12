<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextareaField::new('description'),
            ChoiceField::new('couleur')->setChoices([
                'Red' => 'rouge',
                    'Gray' => 'gris',
                    'Black' => 'noir',
                    'White' => 'blanc'
            ]),
            TextField::new('photo')->setMaxLength(10),
            ChoiceField::new('taille')->setChoices([
                
                'S' => 's',
                'M' => 'm',
                'L' => 'l',
            ]),
            TextField::new('collection'),
            IntegerField::new('prix'),
            IntegerField::new('stock'),
            DateTimeField::new('createdAt')->setFormat("d/M/Y à H:m:s")->hideOnForm(),

        ];
    }public function createEntity(string $entityFqcn)
    {
     
        $product =new $entityFqcn; 
        $product->setCreatedAt(new \DateTime);
        return $product;
    }
    
    
}
