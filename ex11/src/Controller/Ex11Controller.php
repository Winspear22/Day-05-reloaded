<?php

namespace App\Controller;

use Exception;
use Doctrine\DBAL\Connection;
use App\Service\ReadAndSortService;
use App\Service\LoadDemoDataService;
use App\Service\DeleteAllTablesService;
use App\Service\ValidationQueryService;
use App\Service\CreatePersonsTableService;
use App\Service\CreateAddressesTableService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\CreateBankAccountsTableService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class Ex11Controller extends AbstractController
{

    public function __construct(
        private readonly Connection $sql_connection,
        private readonly CreatePersonsTableService $createPersonsTable,
        private readonly CreateBankAccountsTableService $createBankAccountsTable,
        private readonly CreateAddressesTableService $createAddressesTable,
        private readonly DeleteAllTablesService $deleteTable,
        private readonly LoadDemoDataService $loadDemoData,
        private readonly ReadAndSortService $readAndSortService,
        private readonly ValidationQueryService $validationQueryService
    ) {}

    /**
     * @Route("/ex11", name="ex11_index")
     */
    public function index(): Response
    {  
        return $this->render('ex11/index.html.twig');
    }

    /**
     * @Route("/ex11/create_all_tables", name="ex11_create_all_tables", methods={"POST"})
     */
    public function createAllTables(): Response
    {
        try
        {
            $result = $this->createPersonsTable->createPersonsTable('ex11_persons');
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
            $result = $this->createBankAccountsTable->createBankAccountsTable('ex11_bank_accounts');
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
            $result = $this->createAddressesTable->createAddressesTable('ex11_addresses');
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex11_index');
    }

    /**
     * @Route("/ex11/delete_all_tables", name="ex11_delete_all_tables", methods={"POST"})
     */
    public function deleteAllTables(): Response
    {
        try
        {
            $result = $this->deleteTable->deleteTableContent("ex11_bank_accounts");
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
            $result = $this->deleteTable->deleteTableContent("ex11_addresses");
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
            $result = $this->deleteTable->deleteTableContent("ex11_persons");
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex11_index');
    }

    /**
     * @Route("/ex11/load_demo_data", name="ex11_load_demo_data", methods={"POST"})
     */
    public function loadDemoData(): Response
    {
        try
        {
            $result = $this->loadDemoData->loadData();
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
            return $this->redirectToRoute('ex11_index');
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex11_index');
    }

    /**
     * @Route("/ex11/search", name="ex11_search", methods={"GET"})
     */
    public function search(Request $request): Response
    {
        try
        {
            $searchRequest = $this->validationQueryService->validateQueryParams($request);

            foreach ($searchRequest->messages as [$type, $msg])
                $this->addFlash($type, $msg);

            $results = $this->readAndSortService->getPersonsGrouped(
                $searchRequest->filterName,
                $searchRequest->sortBy,
                $searchRequest->sortDirection
            );
            return $this->render('ex11/index.html.twig', [
                'results' => $results,
                'filterName' => $searchRequest->filterName,
                'sortBy' => $searchRequest->sortBy,
                'sortDirection' => $searchRequest->sortDirection,
                'totalResults' => count($results)
            ]);
        }
        catch (Exception $e)
        {
            $this->addFlash('danger','Error : '. $e->getMessage());
            return $this->redirectToRoute('ex11_index');
        }
    }
}
