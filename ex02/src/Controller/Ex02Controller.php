<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use App\Service\ReadUserInTable;
use App\Service\InsertUserInTable;
use App\Service\CreateTableService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class Ex02Controller extends AbstractController
{
    public function __construct(
        private readonly Connection $connection,
        private readonly CreateTableService $tableCreator,
        private readonly InsertUserInTable $userInserter,
        private readonly ReadUserInTable $userReader)
        {}
    /**
     * @Route("/ex02", name="ex02_index"), methods={"GET"})
     */
    public function index(): Response
    {
        try
        {
            //$tableCreator
        }
        catch (\Exception $e)
        {
            // Handle exception if needed
        }
        return $this->render('ex02/index.html.twig', [
            'controller_name' => 'Ex02Controller',
        ]);
    }

    /**
     * @Route("/ex02/insert_user", name="ex02_insert_user"), methods={"POST"})
     */
    public function insertUser(): Response
    {
        return $this->render('ex02/index.html.twig', [
            'controller_name' => 'Ex02Controller',
        ]);
    }

    /**
     * @Route("/ex02/read_user", name="ex02_read_user"), methods={"GET"})
     */
    public function readUser(): Response
    {
        return $this->render('ex02/index.html.twig', [
            'controller_name' => 'Ex02Controller',
        ]);
    }

    private function createUserForm(){}

}