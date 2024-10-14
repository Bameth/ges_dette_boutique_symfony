<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Form\UserType;
use App\Form\ClientType;
use App\Model\ClientModel;
use App\Model\ClientSearch;
use App\Form\ClientSearchType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    private ClientModel $clientModel;
    public function __construct()
    {
        $this->clientModel = new ClientModel();
    }
    #[Route('/clients', name: 'clients.index', methods: ['GET'])]
    // public function index(ClientRepository  $clientRepository,int $page = 0): Response
    // {
    //     /*
    //         Methodes de répository permet de récupérer les données d'une entité :
    //             findAll() : Retourne tous les objets de la classe
    //             find($id) : Retourne un objet unique grâce à son id
    //             findBy(['field' => 'value']) : Retourne une liste d'objets en fonction d'un ou plusieurs champs
    //             findBy(['field1' => 'value1', 'field2' => 'value2']) : Retourne une liste d'objets en fonction de plusieurs champs
    //             findOneBy(['field' => 'value']) : Retourne un objet unique en fonction d'un ou plusieurs champs
    //             findOneBy(['field1' => 'value1', 'field2' => 'value2']) : Retourne un objet unique en fonction de plusieurs champs
    //             findOneBy(['field' => 'value'], ['order_field' => 'ASC']) : Retourne un objet unique en fonction d'un ou plusieurs champs et tri
    //     */
    //     $clients = $clientRepository->findAll();
    //     return $this->render('client/index.html.twig', [
    //         'datas' => $clients
    //     ]);
    // }
    #[Route('/clients/{page}', name: 'clients.index', requirements: ['page' => '\d+'], methods: ['GET', 'POST'])]
    public function index(Request $request,int $page = 0): Response
    {    

        $clients = $this->clientModel->findAllWithPaginate($page, 2);
        return $this->render('client/index.html.twig', [
            "response" => $clients,
            "currentPage" => $page,
        ]);
    }

    #[Route('/clients/show/{id?}', name: 'clients.show', methods: ['GET'])]
    public function show(): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

    #[Route('/clients/search/telephone', name: 'clients.searchByTelephone', methods: ['GET'])]
public function searchClients(Request $request, ClientRepository $clientRepository,int $page = 0): Response
{
    $telephone = $request->query->get('telephone');

    // Vérifier si le champ 'telephone' est renseigné
    if ($telephone) {
        // Rechercher le client par téléphone
        $clients = $clientRepository->createQueryBuilder('c')
            ->where('c.telephone LIKE :telephone')
            ->setParameter('telephone', '%' . $telephone . '%')
            ->getQuery()
            ->getResult();
    } else {
        // Si aucun téléphone n'est fourni, retourner tous les clients ou afficher un message
        $clients = $this->clientModel->findAllWithPaginate($page, 2);
    }

    return $this->render('client/index.html.twig', [
        "response" => ['data' => $clients],
        "currentPage" => 0,
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
    $user= new User();
    $form = $this->createForm(ClientType::class, $client);
    $formUser = $this->createForm(UserType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Assurez-vous que les dates sont définies
        $client->setCreateAt(new \DateTimeImmutable());
        $client->setUpdateAt(new \DateTimeImmutable());

        // Enregistrement
        $entityManager->persist($client);
        $entityManager->flush();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('clients.index');
    }

    return $this->render('client/form.html.twig', [
        'formClient' => $form->createView(),
        'formUser' => $formUser->createView(), // Passez le formulaire utilisateur
    ]);
}
}