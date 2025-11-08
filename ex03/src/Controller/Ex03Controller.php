<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class Ex03Controller extends AbstractController
{
    /**
     * @Route("/ex03", name="ex03_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('ex03/index.html.twig', [
            'controller_name' => 'Ex03Controller',
        ]);
    }
}
