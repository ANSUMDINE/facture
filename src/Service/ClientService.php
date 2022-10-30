<?php

namespace App\Service;

use App\Entity\Clients;
use App\Entity\User;
use App\Repository\ClientsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientService
{
    private $em;
    private $userRepository;
    private $passwordHasher;
    private $clientsRepository;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        ClientsRepository $clientsRepository,
        UserPasswordHasherInterface $passwordHasher)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->clientsRepository = $clientsRepository;
    }

    public function getClients(): array
    {
        return $this->clientsRepository->findAll();
    }

    public function create(Clients $client) : void
    {
        $this->em->persist($client);
        $this->em->flush();
    }

    public function add(Request $request): void
    {
        $req = $request->request;
        $user = new User();
        $user
            ->setName($req->get('name'))
            ->setPrenom($req->get('prenom'))
            ->setEmail($req->get('email'))
            ->setTel($req->get('tel'))
            ->setRoles(['ROLE_CLIENT'])
            ->setPassword(
                $this->passwordHasher->hashPassword(
                    $user, 
                    $req->get('password')
                )
            )
        ;

        $this->em->persist($user);
        $this->em->flush();
        
        $thisUser = $this->userRepository->findOneBy(['email' => $req->get('email')]);
        $client = new Clients();
        $client
            ->setAdress($req->get('adress'))
            ->setUser($thisUser)
        ;
        
        $this->em->persist($client);
        $this->em->flush();
    }

    public function edit(Clients $client) : void
    {
        $this->em->persist($client);
        $this->em->flush();
    }

    public function delete(Clients $client) : void
    {
        $this->em->remove($client);
        $this->em->flush();        
    }
}