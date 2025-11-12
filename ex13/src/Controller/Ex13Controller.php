<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class Ex13Controller extends AbstractController
{

    /**
     * @Route("/ex13", name="ex13_index", methods={"GET"})
     */
    public function index(): Response
    {
            return $this->render('ex13/index.html.twig', [
                'controller_name' => 'Ex13Controller',
            ]);
    }
}
