<?php 

namespace App\Service;

use Exception;
use App\Service\UtilsTableService;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\EmployeesValidationUtilsService;

class DeleteEmployeesService 
{
	public function __construct(
        private readonly EntityManagerInterface $em,
		private readonly EmployeeRepository $repo,
        private readonly UtilsTableService $utilsTableService,
        private readonly EmployeesValidationUtilsService $validationUtils

	) {}
    
    public function deleteAllTableContent(): string
    {
        try
        {
            if (!$this->utilsTableService->checkTableExistence('ex13_employees'))
                return 'danger:Table ex13_employees does not exist.';

            $employees = $this->repo->findAll();

            if (empty($employees))
                return 'info:No data to delete.';

            foreach ($employees as $employee)
                $this->em->remove($employee);
            $this->em->flush();
            return 'success:All data deleted successfully!';
        }
        catch (Exception $e)
        {
            return 'danger:' . $e->getMessage();
        }
    }

    public function deleteEmployeeById(int $id): string
    {
        try
        {
            if (!$this->utilsTableService->checkTableExistence('ex13_employees'))
                return 'danger:Table ex13_employees does not exist.';

            $employee = $this->repo->find($id);

            if (!$employee)
                return 'danger:Employee with ID ' . $id . ' not found.';
            if (!$this->validationUtils->canDeleteCEO($employee))
                return 'danger:Cannot delete the CEO while other employees exist.';

            if (!$this->validationUtils->canDeleteCOO($employee))
                return 'danger:Cannot delete the COO while they manage employees.';

            if (!$this->validationUtils->canDeleteManager($employee))
                return 'danger:Cannot delete a manager who still has employees.';
            $this->em->remove($employee);
            $this->em->flush();
            return 'success:Employee deleted successfully!';
        }
        catch (Exception $e)
        {
            return 'danger:' . $e->getMessage();
        }
    }
}
?>