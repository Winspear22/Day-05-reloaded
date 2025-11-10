<?php

namespace App\Service;

use Exception;
use App\Repository\UserRepository;

class ReadUserInTable
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {}

    public function getAllUsers(): array
    {
        try
        {
            return $this->userRepository->findAll();
        }
        catch (Exception $e)
        {
            return [];
        }
    }
}
