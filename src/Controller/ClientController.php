<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Form\UserType;
use App\Form\ClientType;
use App\Enum\StatusDettes;
use App\Dto\ClientSearchDto;
use App\Form\DetteFilterType;
use App\Form\ClientSearchType;
use App\Repository\DetteRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    #[Route('/clients', name: 'clients.index', methods: ['GET', 'POST'])]
    public function index(ClientRepository $clientRepository, Request $request): Response
    {
        $clientSearchDto = new ClientSearchDto();
        $formSearch = $this->createForm(ClientSearchType::class, $clientSearchDto);
        $formSearch->handleRequest($request);
        $page = $request->query->getInt('page', 1);
        $limit = 6;

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $clients = $clientRepository->findClientBy($clientSearchDto, $page, $limit);
            $totalClients = count($clients);
        } else {
            $clients = $clientRepository->paginateClients($page, $limit);
            $totalClients = $clientRepository->getTotalClients();
        }
        $maxPage = ceil($totalClients / $limit);

        return $this->render('client/index.html.twig', [
            'datas' => $clients,
            'page' => $page,
            'maxPage' => $maxPage,
            'totalElements' => $totalClients,
            'currentPage' => $page,
            'formSearch' => $formSearch->createView(),
        ]);
    }


    #[Route('/clients/show/{idClient}', name: 'clients.show', methods: ['GET', 'POST'])]
    public function show(Request $request, int $idClient, ClientRepository $clientRepository, DetteRepository $detteRepository): Response
    {
        $formSearch = $this->createForm(DetteFilterType::class);
        $formSearch->handleRequest($request);

        $client = $clientRepository->find($idClient);

        $detteStatus = null;
        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $detteStatus = $formSearch->get('statusDettes')->getData();
        }
        if ($detteStatus instanceof StatusDettes) {
        $dettes = $detteRepository->findDetteByClientAndStatus($idClient, $detteStatus->value);
    } else {
        $dettes = $detteRepository->findDetteByClientAndStatus($idClient, null); // Ou toute autre logique
    }

        return $this->render('client/dette.html.twig', [
            'client' => $client,
            'formSearch' => $formSearch->createView(),
            'dettes' => $dettes,
        ]);
    }


    #[Route('/clients/search/telephone', name: 'clients.searchClientByTelephone', methods: ['GET'])]
    public function searchlientByTelephone(Request $request): Response
    {
        // query => $_GET
        // request => $_POST
        // $request->query->get('key') => $_GET['key']
        // $request->request->get('name_field') => $_POST['name_field']

        $telephone = $request->query->get('tel');
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

    #[Route('/clients/remove/{id?}', name: 'clients.remove', methods: ['GET'])]
    public function remove(int $id): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

    #[Route('/clients/store', name: 'clients.store', methods: ['GET', 'POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager): Response
    {
        $client = new Client();
        $user = new User();
        $formClient = $this->createForm(ClientType::class, $client);
        $formUser = $this->createForm(UserType::class, $user);
        $formClient->handleRequest($request);
        $formUser->handleRequest($request);

        if ($formClient->isSubmitted() && $formClient->isValid()) {
            $entityManager->persist($client);

            if ($request->get('toggleUser') === 'on' && $formUser->isSubmitted() && $formUser->isValid()) {
                $entityManager->persist($user);
                $client->setUser($user);
            }

            $entityManager->flush();
            return $this->redirectToRoute('clients.index');
        }
        return $this->render('client/form.html.twig', [
            'formClient' => $formClient->createView(),
            'formUser' => $formUser->createView(),
            'client' => $client,
        ]);
    }
}
