<?php

namespace App\Controller;

use Exception;
use App\Service\UtilsTableService;
use App\Service\CreateTableService;
use App\Service\InsertCommentsService;
use App\Service\ReadCommentsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class Ex14Controller extends AbstractController
{
    public function __construct(
        private readonly CreateTableService $tableCreator,
        private readonly UtilsTableService $utilsTableService,
        private readonly ReadCommentsService $readCommentsService,
        private readonly InsertCommentsService $insertCommentsService
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

    /**
     * @Route("/ex14/insert_vulnerable_comment", name="ex14_insert_vulnerable_comment", methods={"POST"})
     */
    public function insertVulnerableComment(Request $request): Response
    {
        try
        {
            $comment = $request->request->get('comment', '');
            $result = $this->insertCommentsService->insertCommentVulnerable('ex14_comments', $comment);
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
            return $this->redirectToRoute('ex14_index');
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error, we could not insert your vulnerable comment : ' . $e->getMessage());
            return $this->redirectToRoute('ex14_index');
        }
    }

    /**
     * @Route("/ex14/insert_secure_comment", name="ex14_insert_secure_comment", methods={"POST"})
     */
    public function insertSecureComment(Request $request): Response
    {
        try
        {
            $comment = $request->request->get('comment', '');
            $result = $this->insertCommentsService->insertCommentSecure('ex14_comments', $comment);
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
            return $this->redirectToRoute('ex14_index');
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error, we could not insert your secure comment : ' . $e->getMessage());
            return $this->redirectToRoute('ex14_index');
        }
    }
}
