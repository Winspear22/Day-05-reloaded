<?php

namespace App\Controller;

use App\Service\CreateTableService;
use Exception;
use Doctrine\DBAL\Connection;
//use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class Ex00Controller extends AbstractController
{
    /**
     * @Route("/ex00", name="ex00_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('ex00/index.html.twig', [
            'controller_name' => 'Ex00Controller',
        ]);
    }

    /**
     * @Route("/ex00/create-table", name="ex00_create_table", methods={"POST"})
     */
    public function createTable(CreateTableService $tableCreator, Connection $connection): Response
    {
        try
        {
            $result = $tableCreator->createTable($connection, 'ex00_users');
            if (str_starts_with($result, 'success'))
                $this->addFlash('success', $result);
            else
                $this->addFlash('info', $result);
            return $this->redirectToRoute('ex00_index');
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error creating table: ' . $e->getMessage());
            return $this->redirectToRoute('ex00_index');        }
    }
}
