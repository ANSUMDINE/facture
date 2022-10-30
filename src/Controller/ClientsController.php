<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Form\ClientFormType;
use App\Service\ClientService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientsController extends AbstractController
{
    private $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    #[Route('/clients', name: 'app_clients')]
    public function index(): Response
    {
        return $this->render('clients/index.html.twig', [
            'clients' => $this->clientService->getClients()
        ]);
    }

    #[Route('/clients/create', name: 'clients_create')]
    public function create(Request $request): Response
    {
        $client = new Clients();
        $form = $this->createForm(ClientFormType::class, $client);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $this->clientService->create($client);
            return $this->redirectToRoute('app_clients');
        }

        return $this->render('clients/create.html.twig', [
            'form' => $form->createView(),
            'message' => null
        ]);
    }

    #[Route('/clients/add', name: 'clients_add')]
    public function add(Request $request): Response
    {
        if($request->request->get('password') == $request->request->get('confirm_password'))
        {
            $this->clientService->add($request);
            return $this->redirectToRoute('app_clients');
        } else 
        {
            return $this->render('clients/create.html.twig', [
                'message' => 'Le mot de passe n\'est pas le même'
            ]);
        }
    }

    #[Route('/clients/edit/{id}', name: 'clients_edit')]
    public function edit(Request $request, Clients $client): Response
    {
        $form = $this->createForm(ClientFormType::class, $client);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $this->clientService->edit($client);
            return $this->redirectToRoute('app_clients');
        }
       
        return $this->render('clients/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/clients/delete/{id}', name: 'clients_delete')]
    public function delete(Clients $client): Response
    {
        $this->clientService->delete($client);
        return $this->redirectToRoute('app_clients');
    }
}
