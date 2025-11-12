<?php

namespace App\Controller;

use DateTime;
use App\Enum\HoursEnum;
use App\Entity\Employee;
use App\Enum\PositionEnum;
use App\Repository\EmployeeRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class Ex13Controller extends AbstractController
{

    /**
     * @Route("/ex13", name="ex13_index", methods={"GET"})
     */
    public function index(): Response
    {
            return $this->render('ex13/index.html.twig', [
                'controller_name' => 'Ex13Controller',
            ]);
    }

    private function createEmployeeForm(Employee $employee): FormInterface
    {
        return $this->createFormBuilder($employee)
            ->add('firstname', TextType::class, [
                'label' => 'First Name',
                'constraints' => [
                    new NotBlank(['message' => 'First name is required.']),
                    new Length(['max' => 60, 'maxMessage' => 'Maximum 60 characters allowed.']),
                ],
                'attr' => ['maxlength' => 60, 'placeholder' => 'First name']
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Last Name',
                'constraints' => [
                    new NotBlank(['message' => 'Last name is required.']),
                    new Length(['max' => 60, 'maxMessage' => 'Maximum 60 characters allowed.']),
                ],
                'attr' => ['maxlength' => 60, 'placeholder' => 'Last name']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank(['message' => 'Email is required.']),
                    new Email(['message' => 'Invalid email address.']),
                    new Length(['max' => 100, 'maxMessage' => 'Maximum 100 characters allowed.']),
                ],
                'attr' => ['maxlength' => 100, 'placeholder' => 'email@example.com']
            ])
            ->add('birthdate', DateTimeType::class, [
                'label' => 'Birthdate',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'Birthdate is required.']),
                    new LessThanOrEqual(['value' => 'today', 'message' => 'Error. Birthdate cannot be in the future.']),
                ],
                'attr' => [
                    'min' => '1945-01-01',
                    'max' => (new DateTime())->format('Y-m-d'),
                ],
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Active',
                'required' => false,
            ])
            ->add('employed_since', DateTimeType::class, [
                'label' => 'Employed Since',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'Employment start date is required.']),
                ],
                'attr' => [
                    'min' => '1945-01-01',
                    'max' => '2045-12-31',
                ],
            ])
            ->add('employed_until', DateTimeType::class, [
                'label' => 'Employed Until',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'min' => '1945-01-01',
                    'max' => '2045-12-31',
                ],
            ])
            ->add('hours', ChoiceType::class, [
                'label' => 'Hours',
                'choices' => [
                    '8 hours' => HoursEnum::EIGHT,
                    '6 hours' => HoursEnum::SIX,
                    '4 hours' => HoursEnum::FOUR,
                ],
                'placeholder' => 'Select hours'
            ])
            ->add('salary', IntegerType::class, [
                'label' => 'Salary',
                'constraints' => [
                    new NotBlank(['message' => 'Salary is required.']),
                ],
            ])
            ->add('manager', EntityType::class, [
                'class' => Employee::class,
                'query_builder' => function (EmployeeRepository $er) use ($employee) {
                    $qb = $er->createQueryBuilder('e');

                    // 🔹 On exclut la personne elle-même
                    $qb->where('e.id != :current')
                    ->setParameter('current', $employee->getId() ?? 0);

                    // 🔹 Cas spéciaux pour filtrer selon le rôle
                    if ($employee->getPosition() === PositionEnum::COO) {
                        // COO => uniquement CEO
                        $qb->andWhere('e.position = :posCEO')
                        ->setParameter('posCEO', PositionEnum::CEO);
                    } elseif (in_array($employee->getPosition(), [
                        PositionEnum::MANAGER,
                        PositionEnum::ACCOUNT_MANAGER,
                        PositionEnum::QA_MANAGER,
                        PositionEnum::DEV_MANAGER
                    ])) {
                        // Managers => uniquement COO
                        $qb->andWhere('e.position = :posCOO')
                        ->setParameter('posCOO', PositionEnum::COO);
                    }
                    return $qb;
                },
                'choice_label' => fn(Employee $e) => $e->getFirstname() . ' ' . $e->getLastname(),
                'placeholder' => 'Select a manager',
                'required' => false,
                'disabled' => in_array($employee->getPosition(), [PositionEnum::CEO, PositionEnum::COO])
            ])
            ->add('position', ChoiceType::class, [
                'label' => 'Position',
                'choices' => [
                    'Manager' => PositionEnum::MANAGER,
                    'Account Manager' => PositionEnum::ACCOUNT_MANAGER,
                    'QA Manager' => PositionEnum::QA_MANAGER,
                    'Dev Manager' => PositionEnum::DEV_MANAGER,
                    'CEO' => PositionEnum::CEO,
                    'COO' => PositionEnum::COO,
                    'Backend Dev' => PositionEnum::BACKEND_DEV,
                    'Frontend Dev' => PositionEnum::FRONTEND_DEV,
                    'QA Tester' => PositionEnum::QA_TESTER,
                ],
                'placeholder' => 'Select position'
            ])
            ->getForm();
    }


    private function updateEmployeeForm(Employee $employee): FormInterface
    {
        return $this->createFormBuilder($employee)
            ->add('firstname', TextType::class, [
                'label' => 'First Name',
                'constraints' => [
                    new NotBlank(['message' => 'First name is required.']),
                    new Length(['max' => 60, 'maxMessage' => 'Maximum 60 characters allowed.']),
                ],
                'attr' => ['maxlength' => 60, 'placeholder' => 'First name']
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Last Name',
                'constraints' => [
                    new NotBlank(['message' => 'Last name is required.']),
                    new Length(['max' => 60, 'maxMessage' => 'Maximum 60 characters allowed.']),
                ],
                'attr' => ['maxlength' => 60, 'placeholder' => 'Last name']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank(['message' => 'Email is required.']),
                    new Email(['message' => 'Invalid email address.']),
                    new Length(['max' => 100, 'maxMessage' => 'Maximum 100 characters allowed.']),
                ],
                'attr' => ['maxlength' => 100, 'placeholder' => 'email@example.com']
            ])
            ->add('birthdate', DateTimeType::class, [
                'label' => 'Birthdate',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'Birthdate is required.']),
                    new LessThanOrEqual(['value' => 'today', 'message' => 'Error. Birthdate cannot be in the future.']),
                ],
                'attr' => [
                    'min' => '1945-01-01',
                    'max' => (new DateTime())->format('Y-m-d'),
                ],
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Active',
                'required' => false,
            ])
            ->add('employed_since', DateTimeType::class, [
                'label' => 'Employed Since',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'Employment start date is required.']),
                ],
                'attr' => [
                    'min' => '1945-01-01',
                    'max' => '2045-12-31',
                ],
            ])
            ->add('employed_until', DateTimeType::class, [
                'label' => 'Employed Until',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'min' => '1945-01-01',
                    'max' => '2045-12-31',
                ],
            ])
            ->add('hours', ChoiceType::class, [
                'label' => 'Hours',
                'choices' => [
                    '8 hours' => HoursEnum::EIGHT,
                    '6 hours' => HoursEnum::SIX,
                    '4 hours' => HoursEnum::FOUR,
                ],
                'placeholder' => 'Select hours'
            ])
            ->add('salary', IntegerType::class, [
                'label' => 'Salary',
                'constraints' => [
                    new NotBlank(['message' => 'Salary is required.']),
                ],
            ])
            ->add('manager', EntityType::class, [
                'class' => Employee::class,
                'query_builder' => function (EmployeeRepository $er) use ($employee) {
                    $qb = $er->createQueryBuilder('e');
                    $qb->where('e.id != :current')
                        ->setParameter('current', $employee->getId() ?? 0);

                    if ($employee->getPosition() === PositionEnum::COO) {
                        $qb->andWhere('e.position = :posCEO')
                            ->setParameter('posCEO', PositionEnum::CEO);
                    }
                    elseif (in_array($employee->getPosition(), [
                        PositionEnum::MANAGER,
                        PositionEnum::ACCOUNT_MANAGER,
                        PositionEnum::QA_MANAGER,
                        PositionEnum::DEV_MANAGER
                    ]))
                    {
                        $qb->andWhere('e.position = :posCOO')
                            ->setParameter('posCOO', PositionEnum::COO);
                    }

                    return $qb;
                },
                'choice_label' => fn(Employee $e) => $e->getFirstname() . ' ' . $e->getLastname(),
                'placeholder' => 'Select a manager',
                'required' => false,
                'disabled' => in_array($employee->getPosition(), [PositionEnum::CEO, PositionEnum::COO])
            ])
            ->add('position', ChoiceType::class, [
                'label' => 'Position',
                'choices' => [
                    'Manager' => PositionEnum::MANAGER,
                    'Account Manager' => PositionEnum::ACCOUNT_MANAGER,
                    'QA Manager' => PositionEnum::QA_MANAGER,
                    'Dev Manager' => PositionEnum::DEV_MANAGER,
                    'CEO' => PositionEnum::CEO,
                    'COO' => PositionEnum::COO,
                    'Backend Dev' => PositionEnum::BACKEND_DEV,
                    'Frontend Dev' => PositionEnum::FRONTEND_DEV,
                    'QA Tester' => PositionEnum::QA_TESTER,
                ],
                'placeholder' => 'Select position',
                'disabled' => ($employee->getId() && in_array($employee->getPosition(), [PositionEnum::CEO, PositionEnum::COO]))
            ])
            ->getForm();
    }
}
