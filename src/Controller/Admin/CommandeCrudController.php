<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            IntegerField::new('Quantity'),
            IntegerField::new('montant'),
            ChoiceField::new('etat')->setChoices([
                'En cours de traitement' => '1',
                    'Envoyé' => '2',
                    'Livré' => '3',
                    
            ]),
            DateTimeField::new('createdAt')->setFormat("d/M/Y à H:m:s"),
            AssociationField::new('membr')->renderAsNativeWidget(),
            AssociationField::new('product')->renderAsNativeWidget(),
        ];
    }
    
}
