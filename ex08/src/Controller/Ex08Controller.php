<?php

namespace App\Controller;

use App\Service\AlterAddressesTableService;
use Exception;
use Doctrine\DBAL\Connection;
use App\Service\CreatePersonsTableService;
use App\Service\AlterBankAccountsTableService;
use App\Service\CreateAddressesTableService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\CreateBankAccountsTableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class Ex08Controller extends AbstractController
{
    public function __construct(
        private readonly Connection $sql_connection,
        private readonly CreatePersonsTableService $createPersonsTable,
        private readonly CreateBankAccountsTableService $createBankAccountsTable,
        private readonly CreateAddressesTableService $createAddressesTable,
        private readonly AlterBankAccountsTableService $alterBankAccountsTables,
        private readonly AlterAddressesTableService $alterAddressesTable
    ) {}

    /**
     * @Route("/ex08", name="ex08_index")
     */
    public function index(): Response
    {  
        return $this->render('ex08/index.html.twig');
    }

    /**
    * @Route("/ex08/create_persons_table", name="ex08_create_persons_table", methods={"POST"})
    */
    public function createPersonsTable(): Response
    {
        try
        {
            $result = $this->createPersonsTable->createPersonsTable('persons');
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
     * @Route("/ex08/create_addresses_table", name="ex08_create_addresses_table", methods={"POST"})
     */
    public function createAddressesTable(): Response
    {
        try
        {
            $result = $this->createAddressesTable->createAddressesTable('addresses');
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
     * @Route("/ex08/create_relation_addresses", name="ex08_create_relation_addresses", methods={"POST"})
     */
    public function createAddressesRelation(): Response
    {
        try
        {
            $result = $this->alterAddressesTable->alterAddressesTable("persons", "addresses");
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
     * @Route("/ex08/create_bank_accounts_table", name="ex08_create_bank_accounts_table", methods={"POST"})
     */
    public function createBankAccountsTable(): Response
    {
        try
        {
            $result = $this->createBankAccountsTable->createBankAccountsTable('bank_accounts');
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
     * @Route("/ex08/create_relation_bank_account", name="ex08_create_relation_bank_account", methods={"POST"})
     */
    public function createBankAccountRelation(): Response
    {
        try
        {
            $result = $this->alterBankAccountsTables->alterBankAccountsTable("persons", "bank_accounts");
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
