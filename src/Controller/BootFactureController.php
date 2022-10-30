<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BootFactureController extends AbstractController
{
    private $userService;

    public function __construct(
        UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/boot/facture/create/super/user', name: 'boot_facture_create_super_user')]
    public function createSuperUser(): Response
    {
        $this->userService->createSuperUser();
        return $this->redirectToRoute('admin');
    }

    #[Route('/boot/facture/delete/super/user', name: 'boot_facture_delete_super_user')]
    public function deleteSuperUser(): Response
    {
        $this->userService->deleteSuperUser();
        return $this->redirectToRoute('admin');
    }
}
