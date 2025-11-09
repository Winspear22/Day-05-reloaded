<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Service\ReadUserInTable;
use App\Repository\UserRepository;
use App\Service\DeleteUserInTable;
use App\Service\InsertUserInTable;
use App\Service\UpdateUserInTable;
use App\Service\CreateTableService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
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
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class Ex07Controller extends AbstractController
{
    public function __construct(
        private readonly CreateTableService $tableCreator,
        private readonly InsertUserInTable $userInserter,
        private readonly ReadUserInTable $userReader,
        private readonly DeleteUserInTable $userDeleter,
        private readonly UpdateUserInTable $userUpdater,
        private readonly UserRepository $repo
    ) {}

    /**
     * @Route("ex07", name="ex07_index", methods={"GET"})
     */
    public function index(): Response
    {
        $user = new User();
        $form = $this->createUserForm($user);
        $users = [];
        try
        {
            $this->tableCreator->createTable('ex07_users');
            $users = $this->userReader->getAllUsers();
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', "Error, unexpected error: " . $e->getMessage());
        }
        return $this->render('ex07/index.html.twig', [
            'form' => $form->createView(),
            'users' => $users
        ]);
    }

    /**
     * @Route("/ex07/insert_user", name="ex07_insert_user", methods={"POST"})
     */
    public function insertUser(Request $request): Response
    {
        $user = new User();
        $form = $this->createUserForm($user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            //$data = $form->getData();
            try
            {
                $this->userInserter->insertUser($user);
                $this->addFlash('success', 'User added successfully.');
                return $this->redirectToRoute('ex07_index');
            }
            catch (UniqueConstraintViolationException $e)
            {
                $this->addFlash('danger', 'Error, email or username already in use !');
            }
            catch (Exception $e)
            {
                $this->addFlash('danger', 'Error adding user: ' . $e->getMessage());
            }
            return $this->redirectToRoute('ex07_index');
        }
        else
        {
            $this->addFlash('danger', 'Error - Invalid form submission.');
            return $this->redirectToRoute('ex07_index');
        } 
    }

    /**
     * @Route("/ex07/delete_user/{id}", name="ex07_delete_user", methods={"POST"})
     */
    public function deleteUser(int $id): Response
    {
        try
        {
            $result = $this->userDeleter->deleteUserById($id);
            [$type, $message] = explode(':', $result, 2);
            $this->addFlash($type, $message);
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex07_index');
    }

    /**
     * @Route("/ex07/update_user/{id}", name="ex07_update_user", methods={"GET", "POST"})
     */
    public function updateUser(Request $request, int $id): Response
    {
        try
        {
            $user = $this->repo->find($id);
            if (!$user)
            {
                $this->addFlash('danger', 'Error: User not found.');
                return $this->redirectToRoute('ex07_index');
            }
            $form = $this->createUserForm($user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
                $result = $this->userUpdater->updateUser();
                [$type, $message] = explode(':', $result, 2);
                $this->addFlash($type, $message);
                return $this->redirectToRoute('ex07_index');
            }
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error: ' . $e->getMessage());
        }
        return $this->render('ex07/update.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/ex07/delete_all_users", name="ex07_delete_all_users", methods={"POST"})
     */
    public function deleteAllUsers(): Response
    {
        try
        {
            $result = $this->userDeleter->deleteAllUsers();
            [$type, $message] = explode(':', $result, 2);
            $this->addFlash($type, $message);
        }
        catch (Exception $e)
        {
            $this->addFlash('danger', 'Error: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex07_index');
    }

    private function createUserForm(User $user): FormInterface
    {
        $form = $this->createFormBuilder($user)
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
