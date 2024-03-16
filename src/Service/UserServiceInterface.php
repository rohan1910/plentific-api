<?php

namespace App\Service;

use App\Model\User;

interface UserServiceInterface 
{
    /**
     * @param string $name
     * @param string $job
     * @return int|null
     */
    public function createUser(string $name, string $job, string $email): ?int;

    /**
     * @param int $userId
     * @return User|null
     */
    public function getUser(int $userId): ?User;


    /**
     *  @param int $page
     *  @param int $per_page
     *  @return array
     */
    public function listUsers(int $page, int $per_page): array;
}
