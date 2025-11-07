<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class Ex02Controller extends AbstractController
{
    /**
     * @Route("/ex02", name="ex02_index"), methods={"GET"}
     */
    public function index(): Response
    {
        return $this->render('ex02/index.html.twig', [
            'controller_name' => 'Ex02Controller',
        ]);
    }
}
