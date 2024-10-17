<?php

namespace App\Controller;

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
    public function store(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('dette/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }
}
