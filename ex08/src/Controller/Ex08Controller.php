<?php

namespace App\Controller;

use Exception;
use Doctrine\DBAL\Connection;
use App\Service\AlterPersonsTableService;
use App\Service\CreatePersonsTableService;
use App\Service\AlterAddressesTableService;
use App\Service\CreateAddressesTableService;
use App\Service\AlterBankAccountsTableService;
use Symfony\Component\HttpFoundation\Response;
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
        private readonly AlterAddressesTableService $alterAddressesTable,
        private readonly AlterPersonsTableService $alterPersonsTable
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
            $result = $this->createPersonsTable->createPersonsTable('ex08_persons');
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
            $result = $this->createAddressesTable->createAddressesTable('ex08_addresses');
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
            $result = $this->alterAddressesTable->alterAddressesTable("ex08_persons", "ex08_addresses");
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
            $result = $this->createBankAccountsTable->createBankAccountsTable('ex08_bank_accounts');
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
            $result = $this->alterBankAccountsTables->alterBankAccountsTable("ex08_persons", "ex08_bank_accounts");
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
     * @Route("/ex08/add_marital_status", name="ex08_add_marital_status", methods={"POST"})
     */
    public function addMaritalStatus(): Response
    {
        try
        {
            $result = $this->alterPersonsTable->addMaritalStatusToPersons("ex08_persons");
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
     * @Route("/ex08/remove_marital_status", name="ex08_remove_marital_status", methods={"POST"})
     */
    public function removeMaritalStatus(): Response
    {
        try
        {
            $result = $this->alterPersonsTable->removeMaritalStatusFromPersons("ex08_persons");
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