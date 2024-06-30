<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            TextField::new('prenom'),
            EmailField::new('email'),
            ArrayField::new('roles'),
            TextField::new('adresse'),
            TextField::new('codePostal'),
            TextField::new('ville'),

        ];
    }
//private $hash;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher,)
    {
        $this->hasher = $userPasswordHasher;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $password = $this->hasher->hashPassword($entityInstance, '123456');
        $entityInstance->setPassword($password);
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }
}
