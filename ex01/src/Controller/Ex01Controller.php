<?php

namespace App\Controller;

use Exception;
use App\Service\CreateTableService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class Ex01Controller extends AbstractController
{
    public function __construct(
        private readonly CreateTableService $tableCreator
    ) {}

    /**
     * @Route("ex01", name="ex01_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('ex01/index.html.twig');
    }

    /**
     * @Route("/ex01/create_table", name="ex01_create_table", methods={"POST"})
     */
    public function createTable(): Response
    {
        try
        {
            $result = $this->tableCreator->createTable('ex01_users');
            if (str_starts_with($result, 'success'))
                $this->addFlash('success', $result);
            else
                $this->addFlash('info', $result);
            return $this->redirectToRoute('ex01_index');
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error creating table: ' . $e->getMessage());
            return $this->redirectToRoute('ex01_index');        
        }
    }
}