<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private $em;
    private $userRepository;
    private $passwordHasher;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function getAdmins() : array
    {
        return $this->userRepository->getAdminsByRole('ROLE_ADMIN');
    }

    public function creatAdmin(User $user) : void
    {
        $user
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                )
            )
        ;

        $this->em->persist($user);
        $this->em->flush();
    }

    public function edit(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function delete(User $user) : void
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    public function createSuperUser() : void
    {
        $user = new User();
        $user 
            ->setName('Super')
            ->setPrenom('User')
            ->setEmail('superUser@gmail.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    'superUser'
                )
            )
        ;
        $this->em->persist($user);
        $this->em->flush();
    }

    public function deleteSuperUser() : void
    {
        $user = $this->userRepository->findOneBy(['email' => 'superUser@gmail.com']);
        $this->em->remove($user);
        $this->em->flush();
    }
}