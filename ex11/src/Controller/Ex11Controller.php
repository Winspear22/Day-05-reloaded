<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;
use App\Service\CreatePersonsTableService;
use App\Service\CreateAddressesTableService;
use App\Service\CreateBankAccountsTableService;
use App\Service\DeleteAllTablesService;

final class Ex11Controller extends AbstractController
{

    public function __construct(
        private readonly Connection $sql_connection,
        private readonly CreatePersonsTableService $createPersonsTable,
        private readonly CreateBankAccountsTableService $createBankAccountsTable,
        private readonly CreateAddressesTableService $createAddressesTable,
        private readonly DeleteAllTablesService $deleteTable
    ) {}

    /**
     * @Route("/ex11", name="ex11_index")
     */
    public function index(): Response
    {  
        return $this->render('ex10/index.html.twig');
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
        return $this->redirectToRoute('ex08_index');
    }

    /**
     * @Route("/ex11/delete_all_tables", name="ex11_delete_all_tables", methods={"POST"})
     */
    public function deleteAllTables(): Response
    {
        try
        {
            $result = $this->deleteTable->deleteTable("ex11_bank_accounts");
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
            $result = $this->deleteTable->deleteTable("ex11_addresses");
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
            $result = $this->deleteTable->deleteTable("ex11_persons");
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex08_index');
    }
}
