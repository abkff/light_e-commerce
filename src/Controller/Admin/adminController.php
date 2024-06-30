<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class adminController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

         return $this->render('admin/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('E Commerce');
    }

    public function configureMenuItems(): iterable
    {
        return[
        MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('light commerce'),

            MenuItem::section('Utilisateur'),
            MenuItem::linkToCrud('Users', 'fa fa-user', User::class),
            MenuItem::section('Produit'),
            MenuItem::linkToCrud('Produits', 'fa fa-user', Product::class),
            ];
    }
}
