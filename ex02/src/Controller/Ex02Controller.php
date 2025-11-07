<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use App\Service\ReadUserInTable;
use App\Service\InsertUserInTable;
use App\Service\CreateTableService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
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
        $form = $this->createUserForm();
        try
        {
            $this->tableCreator->createTable('ex02_users');

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

    private function createUserForm()
    {
        $form = $this->createFormBuilder()
            ->add('username', TextType::class, [
                'label' => 'Username',
                'constraints' => [
                    new NotBlank(['message' => 'Username is required.']),
                    new Length(['max' => 25, 'maxMessage' => 'Maximum 25 characters allowed.']),
                ],
                'attr' => ['maxlength' => 25, 'placeholder' => 'Your username']
            ])
            ->add('name', TextType::class, [
                'label' => 'Full name',
                'constraints' => [
                    new NotBlank(['message' => 'Name is required.']),
                    new Length(['max' => 25, 'maxMessage' => 'Maximum 25 characters allowed.']),
                ],
                'attr' => ['maxlength' => 25, 'placeholder' => 'Your full name']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank(['message' => 'Email is required.']),
                    new Email(['message' => 'Invalid email address.']),
                    new Length(['max' => 255, 'maxMessage' => 'Maximum 255 characters allowed.']),
                ],
                'attr' => ['maxlength' => 255, 'placeholder' => 'email@example.com']
            ])
            ->add('enable', CheckboxType::class, [
                'label' => 'Enabled?',
                'required' => false,
            ])
            ->add('birthdate', DateTimeType::class, [
                'label' => 'Birthdate',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'Birthdate is required.']),
                    new LessThanOrEqual([
                        'value' => 'today',
                        'message' => 'Birthdate cannot be in the future.'
                    ]),
                ],
            ])
            ->add('address', TextareaType::class, [
                'label' => 'Address',
                'constraints' => [
                    new NotBlank(['message' => 'Address is required.']),
                    new Length([
                        'max' => 1000,
                        'maxMessage' => 'Address cannot be longer than 1000 characters.',
                    ]),
                ],
                'attr' => ['rows' => 3, 'placeholder' => 'Your full address', 'maxlength' => 1000]
            ])
            ->getForm();
        return $form;
    }

}