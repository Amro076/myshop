<?php

namespace App\Controller\Admin;

use App\Entity\Membre;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MembreCrudController extends AbstractCrudController
{   
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        // je crée un constructeur pour appeler le service UserPasswordHasherInterface
        $this->hasher = $hasher;
    }
    
    public static function getEntityFqcn(): string
    {
        return Membre::class;
    }
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        //$entityInstance correspond à $user
        if(!$entityInstance->getId())
        {
            $entityInstance->setPassword(
                $this->hasher->hashPassword($entityInstance, $entityInstance->getPassword()));
        }
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('pseudo'),
            TextField::new('nom'),
            TextField::new('prenom'),
            TextField::new('email'),
            ChoiceField::new('Civilite')->setChoices([
                
                'M' => 'Homme',
                'Mme' => 'Femme',
               
            ]),
            DateTimeField::new('createdAt')->setFormat("d/M/Y à H:m:s")->hideOnForm(),
            TextField::new('password','Mot de passe')->setFormType(PasswordType::class)->onlyWhenCreating(),
            
            CollectionField::new('roles')->setTemplatePath('admin/field/roles.html.twig'),
            
            
        ];
    }
    public function createEntity(string $entityFqcn)
    {
        // creatEntity( est exécutée lorsque je clique sur 'add article)
        // elle permet d'exécuter du code avant l'affichage de la page
        //ics je vais définir une date de création
        $membre =new $entityFqcn; // ici , équivaut à $article
        $membre->setCreatedAt(new \DateTime);
        return $membre;
    }
    
    
}
