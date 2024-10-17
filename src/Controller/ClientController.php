<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Form\ClientSearchType;
use App\Form\UserType;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    #[Route('/clients', name: 'clients.index', methods: ['GET','POST'])]
    public function index(ClientRepository $clientRepository, Request $request): Response
    {
        $formSearch = $this->createForm(ClientSearchType::class);
        $formSearch->handleRequest($request);
        $page = $request->query->getInt('page', 1);
        $limit = 2;
        if ($formSearch->isSubmitted($request) && $formSearch->isValid()) {
            $clients = $clientRepository->findBy(['telephone' => $formSearch->get('telephone')->getData()]);
        } else {
            $clients = $clientRepository->paginateClients($page, $limit);
        }

        return $this->render('client/index.html.twig', [
            'datas' => $clients,
            'page' => $page,
            'maxPage' => ceil($clientRepository->getTotalClients() / $limit),
            'totalElements' => count($clients),
            'currentPage' => $page,
            'formSearch' => $formSearch->createView(),
        ]);
    }

    #[Route('/clients/show/{id?}', name: 'clients.show', methods: ['GET'])]
    public function show(): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
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
            // Vérification si le formulaire utilisateur doit être soumis
            if ($request->request->get('toggleUser') === 'on' && $formUser->isSubmitted() && $formUser->isValid()) {
                $entityManager->persist($user);
                $client->setUser($user); // Lier le client à l'utilisateur
            }
            $entityManager->flush();
            return $this->redirectToRoute('clients.index');
        }
        return $this->render('client/form.html.twig', [
            'formClient' => $formClient->createView(),
            'formUser' => $formUser->createView(),
            'client' => $client, // Assurez-vous que le client est passé à la vue
        ]);
    }
}
