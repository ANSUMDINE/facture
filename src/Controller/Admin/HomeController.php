<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $userService;

    public function __construct(
        UserService $userService
    )
    {
        $this->userService = $userService;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/home/index.html.twig', [
            'admins' => $this->userService->getAdmins()
        ]);
    }

    #[Route('admin/creatAdmin', name: 'admin_creatAdmin')]
    public function creatAdmin(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->userService->creatAdmin($user);
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/registration/register.html.twig', [
            'form' => $form->createView(),
            'message' => null
        ]);
    }

    #[Route('admin/edit/{id}', name: 'admin_edit')]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $this->userService->edit($user);
            return $this->redirectToRoute('admin');
        }
       
        return $this->render('admin/registration/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('admin/delete/{id}', name: 'admin_delete')]
    public function delete(User $user): Response
    {
        $this->userService->delete($user);
        return $this->redirectToRoute('admin');
    }
}
