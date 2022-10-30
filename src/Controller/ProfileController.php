<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Service\ProfileService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Security("is_granted('ROLE_CLIENT')")]
#[Route('/profile', name: 'profile')]
class ProfileController extends AbstractController
{
    private $profileService;

    public function __construct(
        ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    #[Route('', name: '')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'client' =>  $this->profileService->getProfile($this->getUser()),
        ]);
    }

    #[Route('/update/{id}', name: '_update')]
    public function update(Request $request, Clients $client): Response
    {
        if($request->isMethod('post'))
        {
            if($request->request->get('password') == $request->request->get('confirm_password'))
            {
                $this->profileService->update($request, $client);
                return $this->redirectToRoute('profile');
            } else 
            {
                return $this->render('profile/update.html.twig', [
                    'client' => $client,
                    'message' => 'Le mot de passe n\'est pas le même'
                ]);
            }
        }
        
        return $this->render('profile/update.html.twig', [
            'client' => $client,
            'message' => null
        ]);
    }

    #[Route('/picture', name: '_update_picture')]
    public function updatePicture(Request $request) 
    { 
        $this->profileService->updatePicture($request, $this->getUser());
        return $this->redirectToRoute('profile', [
            'client' =>  $this->profileService->getProfile($this->getUser()),
        ]);
    }
}
