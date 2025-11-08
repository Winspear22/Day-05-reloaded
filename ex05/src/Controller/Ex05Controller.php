<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class Ex05Controller extends AbstractController
{
    /**
     * @Route("/ex05", name="ex05_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('ex05/index.html.twig', [
            'controller_name' => 'Ex05Controller',
        ]);
    }
}
