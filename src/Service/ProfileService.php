<?php

namespace App\Service;

use App\Entity\Clients;
use App\Entity\User;
use App\Repository\ClientsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileService 
{
    private $em;
    private $params;
    private $userRepository;
    private $passwordHasher;
    private $clientsRepository;

    public function __construct(
        EntityManagerInterface $em,
        ParameterBagInterface $params,
        UserRepository $userRepository,
        ClientsRepository $clientsRepository,
        UserPasswordHasherInterface $passwordHasher)
    {
        $this->em = $em;
        $this->params = $params;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->clientsRepository = $clientsRepository;
    }

    public function getProfile(User $user) : Clients
    {
        return $this->clientsRepository->findOneBy(['user' => $user->getId()]);
    }

    public function update(Request $request, Clients $client) : void
    {
        $req = $request->request;
        $user = $client->getUser();
        $user
            ->setName($req->get('name'))
            ->setPrenom($req->get('prenom'))
            ->setEmail($req->get('email'))
            ->setTel($req->get('tel'))
        ;

        if($req->get('password'))
        {
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $req->get('password')
                )
            );   
        }

        $this->em->persist($user);
        $this->em->flush();

        $thisUser = $this->userRepository->findOneBy(['email' => $req->get('email')]);
        $client
            ->setAdress($req->get('adress'))
            ->setUser($thisUser)
        ;
        $this->em->persist($client);
        $this->em->flush();
    }
    
    public function updatePicture(Request $request, User $user) : void
    {
        $picture = $request->files->get('picture');
        $client = $this->clientsRepository->findOneBy(['user' => $user->getId()]);
        
        if($picture)
        {
            $newFileName = 'user_' . $client->getId() . '.' . $picture->guessExtension();
            $picture->move(
                $this->params->get('profiles'),
                $newFileName
            );

            $client->setPicture($newFileName);
            $this->em->persist($client);
            $this->em->flush();
        }
    }
}