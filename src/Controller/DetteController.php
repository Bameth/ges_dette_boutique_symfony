<?php

namespace App\Controller;

use App\Entity\Dette;
use App\Entity\Client;
use App\Form\DetteType;
use App\Form\ClientType;
use App\Form\DetteSearchType;
use App\Repository\ClientRepository;
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
        $limit = 3;

        $criteria = [];
        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $statut = $formSearch->get('statut')->getData();

            if ($statut) {
                $criteria['montantVerser'] = true;
            }
            $dettes = $detteRepository->findByCriteria($criteria);
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
            $toggleUser = $request->request->get('toggleUser', false);

            if ($toggleUser) {
                $formClient->handleRequest($request);
                if ($formClient->isSubmitted() && $formClient->isValid()) {
                    $entityManager->persist($client);
                }
            } else {
                $clientId = $formDette->get('client')->getData();
                $client = $entityManager->getRepository(Client::class)->find($clientId);

                if (!$client) {
                    $this->addFlash('error', 'Veuillez sélectionner un client.');
                    return $this->redirectToRoute('dettes.new');
                }
            }

            $dette->setClient($client);
            $entityManager->persist($dette);
            $entityManager->flush();

            $this->addFlash('success', 'La dette a été créée avec succès.');
            return $this->redirectToRoute('dettes.index');
        }

        return $this->render('dette/form.html.twig', [
            'formDette' => $formDette->createView(),
            'formClient' => $formClient->createView(),
        ]);
    }

}
