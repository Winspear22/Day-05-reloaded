<?php

namespace App\Controller;

use DateTime;
use Exception;
use App\Entity\Data;
use App\Service\CreateTableServiceORM;
use App\Service\CreateTableServiceSQL;
use App\Service\DeleteAllDataService;
use App\Service\ImportFileService;
use App\Service\ReadDataServiceORM;
use App\Service\ReadDataServiceSQL;
use App\Service\DeleteDataServiceORM;
use App\Service\DeleteDataServiceSQL;
use App\Service\InsertDataServiceORM;
use App\Service\InsertDataServiceSQL;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Ex10Controller extends AbstractController
{
    public function __construct(
        private readonly ReadDataServiceSQL $readSql,
        private readonly ReadDataServiceORM $readOrm,
        private readonly InsertDataServiceSQL $insertSql,
        private readonly InsertDataServiceORM $insertOrm,
        private readonly DeleteDataServiceSQL $deleteSql,
        private readonly DeleteDataServiceORM $deleteOrm,
        private readonly CreateTableServiceSQL $createSql,
        private readonly CreateTableServiceORM $createOrm,
        private readonly DeleteAllDataService $utils

    ) {}

    /**
     * @Route("/ex10", name="ex10_index", methods={"GET"})
     */
    public function index(): Response
    {
        $formSQL = $this->createDataForm();
        $formORM = $this->createDataForm();
        $tableNameSql = "ex10_data_sql";
        $tableNameOrm = "ex10_data_orm";
        try
        {
            $this->createSql->createTableSQL($tableNameSql);
            $this->createOrm->createTableORM($tableNameOrm);
            $dataSql = $this->readSql->readAllDataSQL($tableNameSql);
            $dataOrm = $this->readOrm->readAllDataORM($tableNameOrm);
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', "Error, unexpected error: " . $e->getMessage());
            $dataSql = [];
            $dataOrm = [];
        }
        return $this->render('ex10/index.html.twig', [
            'formSQL' => $formSQL->createView(),
            'formORM' => $formORM->createView(),
            'datasSQL' => $dataSql,
            'datasORM' => $dataOrm
        ]);
    }

    /**
     * @Route("/ex10/insert_data_sql", name="ex10_insert_data_sql", methods={"POST"})
     */
    public function insertDataSQL(Request $request): Response
    {
        try
        {
            $form = $this->createDataForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
                $data = $form->get('comment')->getData();
                $date = new DateTime();
                $result = $this->insertSql->insertDataSQL("ex10_data_sql", $data, $date);//$dataInsertService->insertData($connection, 'ex10_data_sql', $data, $date);
                [$type, $msg] = explode(':', $result, 2);
                $this->addFlash($type, $msg);
                return $this->redirectToRoute('ex10_index');
            }
            else
            {
                $this->addFlash('danger', 'Error, invalid form!');
                return $this->redirectToRoute('ex10_index');
            }
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error, unexpected error while inserting data: ' . $e->getMessage());
            return $this->redirectToRoute('ex10_index');
        }
    }

    /**
     * @Route("/ex10/insert_data_orm", name="ex10_insert_data_orm", methods={"POST"})
     */
    public function insertDataORM(Request $request): Response
    {
        try
        {
            $form = $this->createDataForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
                $comment = $form->get('comment')->getData();
                $dataEntity = new Data();
                $dataEntity->setData($comment);
                $dataEntity->setDate(new DateTime());
                $result = $this->insertOrm->insertDataORM("ex10_data_orm", $dataEntity);
                [$type, $msg] = explode(':', $result, 2);
                $this->addFlash($type, $msg);
                return $this->redirectToRoute('ex10_index');
            }
            else
            {
                $this->addFlash('danger', 'Error, invalid form!');
                return $this->redirectToRoute('ex10_index');
            }
        }
        catch(Exception $e)
        {
            $this->addFlash('danger', 'Error, unexpected error while inserting data: ' . $e->getMessage());
            return $this->redirectToRoute('ex10_index');
        }
    }

    /**
     * @Route("/ex10/delete_data_sql/{id}", name="ex10_delete_data_sql", methods={"POST"})
     */
    public function deleteDataSQL(int $id): Response
    {
        try
        {
            $result = $this->deleteSql->deleteDataByIdSQL("ex10_data_sql", $id);
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
            return $this->redirectToRoute('ex10_index');

        }
        catch (Exception $e)
        {
            $this->addFlash('danger', "Unexpected error while deleting data: " . $e->getMessage());
            return $this->redirectToRoute('ex10_index');
        }
    }

    /**
     * @Route("/ex10/delete_all_data_sql", name="ex10_delete_all_data_sql", methods={"POST"})
     */
    public function deleteAllDataSQL(): Response
    {
        $tableName = "ex10_data_sql";
        try
        {
            $result = $this->deleteSql->deleteAllDataSQL($tableName);
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
            return $this->redirectToRoute('ex10_index');
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error, unexpected error: ' . $e->getMessage());
            return $this->redirectToRoute('ex10_index');
        }
    }

    /**
     * @Route("/ex10/delete_data_orm/{id}", name="ex10_delete_data_orm", methods={"POST"})
     */
    public function deleteDataORM(int $id): Response
    {
        try
        {
            $result = $this->deleteOrm->deleteDataByIdORM("ex10_data_orm", $id);
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
            return $this->redirectToRoute('ex10_index');
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error, unexpected error: ' . $e->getMessage());
            return $this->redirectToRoute('ex10_index');
        }
    }
        
    /**
     * @Route("/ex10/delete_all_data_orm", name="ex10_delete_all_data_orm", methods={"POST"})
     */
    public function deleteAllDataORM(): Response
    {
        $tableName = "ex10_data_orm";
        try
        {
            $result = $this->deleteOrm->deleteAllDataORM($tableName);
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
            return $this->redirectToRoute('ex10_index');
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error, unexpected error: ' . $e->getMessage());
            return $this->redirectToRoute('ex10_index');
        }
    }

    /**
     * @Route("/ex10/delete_all", name="ex10_delete_all", methods={"POST"})
     */
    public function deleteAll(): Response
    {
        $tableNameSql = "ex10_data_sql";
        $tableNameOrm = "ex10_data_orm";
        $result = $this->utils->deleteAllData($tableNameSql, $tableNameOrm);
        [$type, $msg] = explode(':', $result, 2);
        $this->addFlash($type, $msg);
        return $this->redirectToRoute('ex10_index');
    }

    private function createDataForm()
    {
        return $this->createFormBuilder()
            ->add('comment', TextType::class, [
                'label' => 'Comment',
                'constraints' => [
                    new NotBlank(['message' => 'Comment is required.']),
                    new Length(['max' => 255, 'maxMessage' => 'Maximum 255 characters allowed.']),
                ],
                'attr' => ['maxlength' => 255, 'placeholder' => 'Your comment']
            ])
            ->getForm();
    }

    /**
     * @Route("/ex10/import", name="ex10_import_file", methods={"POST"})
     */
    public function importFile(ImportFileService $importFileService): Response
    {
        $tableNameSql = "ex10_data_sql";
        $tableNameOrm = "ex10_data_orm";
        try
        {
            $filePath = $this->getParameter('kernel.project_dir') . '/text.txt';

            if (!is_file($filePath))
            {
                $this->addFlash('danger', 'Error: The file path is invalid or is a directory.');
                return $this->redirectToRoute('ex10_index');
            }

            if (!is_readable($filePath))
            {
                $this->addFlash('danger', 'Error: The file is not readable. Check file permissions.');
                return $this->redirectToRoute('ex10_index');
            }

            $result = $importFileService->importFile($filePath, $tableNameSql, $tableNameOrm);
            [$type, $msg] = explode(':', $result, 2);
            $this->addFlash($type, $msg);
            return $this->redirectToRoute('ex10_index');
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'An unexpected error occurred during import.');
            if ($this->getParameter('kernel.debug'))
                $this->addFlash('danger', 'Debug: ' . $e->getMessage());
            return $this->redirectToRoute('ex10_index');
        }
    }
}
