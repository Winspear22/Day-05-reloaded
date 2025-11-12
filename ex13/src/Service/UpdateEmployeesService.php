<?php 
namespace App\Service;

use Exception;
use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class UpdateEmployeesService
{
		public function __construct(
        private readonly EntityManagerInterface $em,
		private readonly EmployeeRepository $repo
	) {}

    public function updateEmployee(Employee $employee): string
    {
        try
		{
			$this->em->flush();
			return "success: User updated successfully!";
		}
		catch (UniqueConstraintViolationException $e)
		{
			return "danger: Email or username already exists!: " . $e->getMessage();
		}
		catch (Exception $e) 
		{
			return "danger: " . $e->getMessage();
		}
	}
}

?>