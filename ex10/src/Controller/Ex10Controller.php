<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class Ex10Controller extends AbstractController
{
    #[Route('/ex10', name: 'app_ex10')]
    public function index(): Response
    {
        return $this->render('ex10/index.html.twig', [
            'controller_name' => 'Ex10Controller',
        ]);
    }
}
