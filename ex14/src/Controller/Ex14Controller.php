<?php

namespace App\Controller;

use Exception;
use App\Service\CreateTableService;
use App\Service\ReadCommentsService;
use App\Service\UtilsTableService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class Ex14Controller extends AbstractController
{
    public function __construct(
        private readonly CreateTableService $tableCreator,
        private readonly UtilsTableService $utilsTableService,
        private readonly ReadCommentsService $readCommentsService
    ) {}
    /**
     * @Route("/ex14", name="ex14_index", methods={"GET"})
     */
    public function index(): Response
    {
        $tableName = "ex14_comments";
        try
        {
            $doesTableExist = $this->utilsTableService->checkTableExistence($tableName);
            if ($doesTableExist === true)
                $comments = $this->readCommentsService->getAllComments($tableName);
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', "Error, unexpected error: " . $e->getMessage());
        }
        return $this->render('ex14/index.html.twig', [
            'comments'    => $comments,
            'doesTableExist' => $doesTableExist,
            'tableName'   => 'ex14_comments'
        ]);
    }

    /**
     * @Route("/ex14/create_table", name="ex14_create_table", methods={"POST"})
     */
    public function createTable(): Response
    {
        try
        {
            $result = $this->tableCreator->createTable('ex14_comments');
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
            return $this->redirectToRoute('ex14_index');
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error creating table: ' . $e->getMessage());
            return $this->redirectToRoute('ex14_index');
        }
    }
}
