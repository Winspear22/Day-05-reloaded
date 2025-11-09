<?php

namespace App\Controller;

use Exception;
use App\Service\CreateTableService;
use App\Service\DeleteTableService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class Ex09Controller extends AbstractController
{
    public function __construct(
        private readonly CreateTableService $tableCreator,
        private readonly DeleteTableService $tableDeleter
    ) {}

    /**
     * @Route("/ex09", name="ex09_index", methods={"GET"})
     */
    public function index(): Response
    {  
        return $this->render('ex09/index.html.twig');
    }

    /**
     * @Route("/ex09/create_table_without_marital_status", name="ex09_create_table_without_marital_status", methods={"POST"})
     */
    public function createTableWithoutMaritalStatus(): Response
    {
        try
        {
            $result = $this->tableCreator->migrateToVersionWithoutMaritalStatus();
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', "Error, unexpected error: " . $e->getMessage());
        }
        return $this->render('ex09/index.html.twig');
    }

    /**
     * @Route("/ex09/create_table_with_marital_status", name="ex09_create_table_with_marital_status", methods={"POST"})
     */
    public function createTableWithMaritalStatus(): Response
    {
        try
        {
            $result = $this->tableCreator->migrateToVersionWithMaritalStatus();
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex09_index');
    }

    /**
     * @Route("/ex09/drop_tables", name="ex09_drop_tables", methods={"POST"})
     */
    public function dropTables(): Response
    {
        try
        {
            $result = $this->tableDeleter->dropAllTables("ex09_persons");
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex09_index');
    }
}
