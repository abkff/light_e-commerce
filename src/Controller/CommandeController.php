<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\PdfService;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index', methods: ['GET', 'POST'])]
    public function index(CommandeRepository $commandeRepository, EntityManagerInterface $entityManager,
                          MailerInterface $mailer, PdfService $pdf,
                          SessionInterface $session, ProductRepository $productRepository): Response
    {
        if (!empty($this->getUser())) {
            $commande = new Commande();
            $panier = $session->get('panier', []);


            $commande->setUser($this->getUser());

            // recupération du panier
            foreach ($panier as $id => $value) {
                $product = $productRepository->find($id);
                $commande->addProduct($product);

            }


            $entityManager->persist($commande);
            $entityManager->flush();

            //dd($commandeRepository->getLastInserted());

            //Envoie du mail
            $pdfPath = $pdf->generatePdfAndSave($this->render('commande/pdf.html.twig', [
                'commande' => $commandeRepository->getLastInserted(),
            ]),
                $commande->getUser());

            $email = (new TemplatedEmail())
                ->from('hello@example.com')
                ->to($this->getUser()->getEmail())
                ->subject('light e-commerce: commande  '.$commande->getCreateAt()->format('d-m-Y H:i:s'))
                ->htmlTemplate('commande/mail.html.twig')
                ->context([
                    'commande' => $commande,
                ])
                ->attachFromPath($pdfPath)
            ;

            $mailer->send($email);




            //VIDER LE PANIER ET LE NBRE QTE
            $session->remove('panier');
            $session->remove('nbre');

            return $this->render('commande/index.html.twig', [
               // 'commandes' => $commandeRepository->findAll(),
                'commandes' => $commandeRepository->findby(['user' => $this->getUser()->getId()]),
            ]);

        } else {
            $this->addFlash('danger', 'Vous devez etre connecté avant de passer commande');

            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/mescommandes', name: 'app_mes_commande_index', methods: ['GET'])]
    public function myIndex(CommandeRepository $CommandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $CommandeRepository->findby(['user' => $this->getUser()->getId()]),
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commandes' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}
