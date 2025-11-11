<?php

namespace App\Controller;

use Exception;
use App\Service\CreateTableService;
use App\Service\DeleteTableService;
use App\Service\LoadDemoDataService;
use App\Service\ValidationQueryService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class Ex12Controller extends AbstractController
{
        public function __construct(
        private readonly LoadDemoDataService $loadDemoData,
        private readonly CreateTableService $createTable,
        private readonly DeleteTableService $deleteTable,
        private readonly ValidationQueryService $validationQueryService
    ) {}

    /**
     * @Route("/ex12", name="ex12_index", methods={"GET"})
     */
    public function index(): Response
    {  
        return $this->render('ex12/index.html.twig');
    }

    /**
     * @Route("/ex12/create_all_tables", name="ex12_create_all_tables", methods={"POST"})
     */
    public function createAllTables(): Response
    {
        try
        {
            $result = $this->createTable->createTable("ex12_persons");
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex12_index');
    }

    /**
     * @Route("/ex12/delete_all_tables_content", name="ex12_delete_all_tables_content", methods={"POST"})
     */
    public function deleteAllTablesContent(): Response
    {
        try
        {
            $result = $this->deleteTable->deleteAllTableContent();
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex12_index');
    }

    /**
     * @Route("/ex12/load_demo_data", name="ex12_load_demo_data", methods={"POST"})
     */
    public function loadDemoData(): Response
    {
        try
        {
            $result = $this->loadDemoData->loadData();
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
            return $this->redirectToRoute('ex12_index');
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex12_index');
    }
}
