<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class Ex12Controller extends AbstractController
{
    #[Route('/ex12', name: 'app_ex12')]
    public function index(): Response
    {
        return $this->render('ex12/index.html.twig', [
            'controller_name' => 'Ex12Controller',
        ]);
    }
}
