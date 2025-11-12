<?php 

namespace App\Service;

use App\Repository\EmployeeRepository;
use Exception;
use App\Service\UtilsTableService;
use Doctrine\ORM\EntityManagerInterface;

class DeleteEmployeeService 
{
	public function __construct(
        private readonly EntityManagerInterface $em,
		private readonly EmployeeRepository $repo,
        private readonly UtilsTableService $utilsTableService
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
}
?>