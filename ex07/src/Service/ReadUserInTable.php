<?php

namespace App\Service;

use App\Repository\UserRepository;

class ReadUserInTable
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {}

    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }
}
