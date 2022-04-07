<?php


namespace App\Repositories\Users\Repository;


use App\Repositories\Users\User;
use Illuminate\Database\Eloquent\Collection;

interface IUser
{
    public function createUser(array $params): User;

    public function deleteUser(User $user): bool;

    public function listUsers(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']);

    public function listAllUsers(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']);

    public function listAdminAndSupervisor();

    public function listAssignedUsers(User $user, string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']);

    public function listCustomers(User $user, $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']);
}
