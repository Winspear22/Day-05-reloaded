<?php

namespace App\Service;

use Exception;
use Doctrine\DBAL\Connection;

class ReadAndSortService
{
    public function __construct(
        private readonly Connection $sql_connection
    ) {}

    public function getAllPersons(string $tableName): array
    {
        $sql = "SELECT * FROM $tableName";
        try
        {
            return $this->sql_connection->fetchAllAssociative($sql);
        }
        catch (Exception $e)
        {
            return [];
        }
    }
    
    public function getPersonsGrouped(
        string $filterName = '',
        string $sortByColumn = 'name',
        string $sortDirection = 'asc'
    ): array
    {
        $rawResults = $this->search($filterName, $sortByColumn, $sortDirection);
        return $this->groupResults($rawResults);
    }

    private function search(
        string $filterName = '',
        string $sortByColumn = 'name',
        string $sortDirection = 'asc'
    ): array
    {
        $allowedColumns = ['name', 'email', 'birthdate'];
        $allowedDirections = ['asc', 'desc'];
        
        if (!in_array($sortByColumn, $allowedColumns))
            $sortByColumn = 'name';
        
        if (!in_array($sortDirection, $allowedDirections))
            $sortDirection = 'asc';

        $query = "
            SELECT 
                p.id,
                p.username,
                p.name,
                p.email,
                p.birthdate,
                p.enable,
                a.address,
                b.iban,
                b.bank_name
            FROM ex11_persons p
            LEFT JOIN ex11_addresses a ON a.person_id = p.id
            LEFT JOIN ex11_bank_accounts b ON b.person_id = p.id
            WHERE 1=1
        ";
        
        $params = [];

        if (!empty(trim($filterName)))
        {
            $query .= " AND p.name LIKE :searchName";
            $params['searchName'] = "%{$filterName}%";
        }

        $query .= " ORDER BY p.{$sortByColumn} {$sortDirection}, p.id ASC";

        return $this->sql_connection->fetchAllAssociative($query, $params);
    }

    private function groupResults(array $rawResults): array
    {
        $groupedPersons = [];
        
        foreach ($rawResults as $row)
		{
            $personId = $row['id'];
            
            if (!isset($groupedPersons[$personId]))
            {
                $groupedPersons[$personId] = $row;
                $groupedPersons[$personId]['addresses'] = [];
            }
            
            if (!empty($row['address']))
                $groupedPersons[$personId]['addresses'][] = $row['address'];
        }

        foreach ($groupedPersons as &$person)
            $person['addresses'] = implode('<br>', $person['addresses']);
        unset($person);

        return array_values($groupedPersons);
    }
}
