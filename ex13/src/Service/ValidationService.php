<?php

namespace App\Service;

use App\Entity\Employee;
use DateTime;

class ValidationService
{
    public static function validateDates(Employee $employee): array
    {
        $errors = [];
        $birthdate = $employee->getBirthdate();
        $employedSince = $employee->getEmployedSince();
        $employedUntil = $employee->getEmployedUntil();
        $todayDate = new DateTime();

        if (!$birthdate || !$employedSince)
            return $errors;

        // Birthdate dans le futur
        if ($birthdate > $todayDate)
            $errors[] = 'Error. Birthdate cannot be in the future!';

        // Embauche avant la naissance
        if ($employedSince < $birthdate)
            $errors[] = 'Error. Hire date cannot be before birth date.';

        // Moins de 18 ans à l'embauche
        $minHireDate = new DateTime($birthdate->format('Y-m-d'));
        $minHireDate->modify('+18 years');
        if ($employedSince < $minHireDate)
            $errors[] = 'Error. Employee must be at least 18 years old to be hired.';

        // Contrat d'au moins 24h
        if ($employedUntil)
		{
            $minEndDate = new DateTime($employedSince->format('Y-m-d'));
            $minEndDate->modify('+1 day');
            if ($employedUntil < $minEndDate)
                $errors[] = 'Error. Contract end date must be at least 24 hours after hire date.';
        }

        return $errors;
    }
}
