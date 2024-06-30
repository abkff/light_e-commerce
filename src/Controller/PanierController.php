<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use App\Repository\ProductRepository;

class PanierController extends AbstractController
{
    private $nbreItem ;

    #[Route('/panier', name: 'app_panier_index')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        $prixTotal = 0;
        $total = [];

        $panier = $session->get('panier', []);

        foreach ($panier as $id => $value) {
            $product = $productRepository->find($id);
            $total[] = [
                'qte' => $value,
                'product' => $product
            ];
            
            $prixTotal += $value * $product->getPrice();
            
            $this->nbreItem += $value;        
        }

        //NBRE ITEM DANS PANIER
        $session->set('nbre', $this->nbreItem);

        return $this->render('panier/index.html.twig', [
            'commandes' => $total, 
            'Total' => $prixTotal
        ]);
    }

    #[Route('/panier/new/{id}', name: 'app_panier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, 
    SessionInterface $session, Product $product): Response
    {

        $panier = $session->get('panier', []);

        $idProduct = $product->getId();
        // dump($idProduct);
        empty($panier[$idProduct]) ? $panier[$idProduct]=1 : $panier[$idProduct]++ ;
        
        $session->set('panier', $panier);

        $session->set('nbre', ($session->get('nbre') +1 ) );

        return $this->redirectToRoute('app_product_index');
     }

    #[Route('/panier/add/{id}', name: 'app_panier_add_item')]
    public function addItem(Request $request, EntityManagerInterface $entityManager, 
    SessionInterface $session,Product $product): Response
    {
        $panier = $session->get('panier', []);
        $idProduct = $product->getId();
        // dump($idProduct);
        empty($panier[$idProduct]) ? $panier[$idProduct]=1 : $panier[$idProduct]++ ;
        
        $session->set('panier', $panier);
        
    return $this->redirectToRoute('app_panier_index');
     }

    #[Route('/remove/{id}', name: 'app_panier_remove_item')]
    public function remove(Product $product, SessionInterface $session)
    {
        $panier = $session->get('panier',[]);

        $idProduct = $product->getId();
        if(!empty($panier[$idProduct])){

            if($panier[$idProduct] > 1){
                $panier[$idProduct]--;
            }else{
                unset($panier[$idProduct]);
            }
        }

        $session->set('panier', $panier);
        return $this->redirectToRoute('app_panier_index');
    }

    // #[Route('/countItem', name: 'app_panier_count_item')]
    public function count(SessionInterface $session)
    {
        $panier = $session->get('panier',[]);
        $nbreItem = 0;
        foreach ($panier as $id => $value) {
            dump($value);
            $nbreItem += $value;
        }

        return $nbreItem;
    }


}
