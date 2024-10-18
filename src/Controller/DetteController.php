<?php

namespace App\Controller;

use App\Entity\Dette;
use App\Entity\Client;
use App\Form\DetteType;
use App\Form\ClientType;
use App\Form\DetteSearchType;
use App\Repository\DetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DetteController extends AbstractController
{
    #[Route('/dettes', name: 'dettes.index', methods: ['GET', 'POST'])]
    public function index(DetteRepository $detteRepository, Request $request): Response
    {
        $formSearch = $this->createForm(DetteSearchType::class);
        $formSearch->handleRequest($request);

        $page = $request->query->getInt('page', 1);
        $limit = 2;

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $telephone = $formSearch->get('telephone')->getData();
            $dettes = $detteRepository->findBy(['telephone' => $telephone]);
        } else {
            $dettes = $detteRepository->paginateDetteClients($page, $limit);
        }
        return $this->render('dette/index.html.twig', [
            'datas' => $dettes,
            'page' => $page,
            'maxPage' => ceil($detteRepository->getTotalDetteClients() / $limit),
            'totalElements' => count($dettes),
            'currentPage' => $page,
            'formSearch' => $formSearch->createView(),
        ]);
    }


    #[Route('/dettes/store', name: 'dettes.store', methods: ['GET', 'POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager, DetteRepository $detteRepository): Response
    {
        $dette = new Dette();
        $client = new Client(); 

        $formDette = $this->createForm(DetteType::class, $dette);
        $formClient = $this->createForm(ClientType::class, $client);

        $formDette->handleRequest($request);
        $formClient->handleRequest($request);

        if ($formDette->isSubmitted() && $formDette->isValid()) {
            $toggleUser = $request->request->get('toggleUser', false); // Récupération de la valeur du checkbox

            if ($toggleUser) {
                // Si le client est validé, associez-le à la dette
                $formClient->handleRequest($request);
                if ($formClient->isSubmitted() && $formClient->isValid()) {
                    $entityManager->persist($client); // Enregistrez le nouveau client
                }
            } else {
                // Trouvez le client existant s'il n'y a pas d'utilisateur à créer
                $clientId = $formDette->get('client')->getData(); // Assurez-vous que le champ client est rempli
                $client = $entityManager->getRepository(Client::class)->find($clientId);

                if (!$client) {
                    throw $this->createNotFoundException('Client non trouvé');
                }
            }

            $dette->setClient($client);
            $entityManager->persist($dette); // Préparez la dette pour l'enregistrement
            $entityManager->flush(); // Enregistrez les changements en base de données

            // Redirection avec un message de succès
            $this->addFlash('success', 'La dette a été créée avec succès.');
            return $this->redirectToRoute('dettes.index');
        }

        return $this->render('dette/form.html.twig', [
            'formDette' => $formDette->createView(),
            'formClient' => $formClient->createView(),
        ]);
    }
}
