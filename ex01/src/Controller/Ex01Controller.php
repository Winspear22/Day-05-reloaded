<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class Ex01Controller extends AbstractController
{
    /**
     * @Route("ex01", name="ex01_index")
     */
    public function index(): Response
    {
        return $this->render('ex01/index.html.twig', [
            'controller_name' => 'Ex01Controller',
        ]);
    }
}
