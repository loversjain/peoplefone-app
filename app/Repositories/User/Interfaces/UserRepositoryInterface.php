<?php

namespace App\Repositories\User\Interfaces;

use App\Models\User;

/**
 * Interface UserRepositoryInterface
 *
 * This interface defines the methods required for user repository implementations.
 */
interface UserRepositoryInterface
{

    /**
     * Find a user by their ID.
     *
     * @param int $id
     * @return \App\Models\User|null
     */
    public function find(int $id);

    /**
     * all users.
     *
     * @return \App\Models\User|null
     */
    public function all();
    /**
     * Update a user by their ID.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data);

    /**
     * Update a user by their ID.
     *
     * @param int $size
     * @param callable $callback
     * @return bool
     */
    public function chunk(int $size, callable $callback);

    /**
     * Update user settings.
     *
     * @param int $userId
     * @param array $data
     * @return User
     */
    public function updateSettings(int $userId, array $data): User;
    /**
     * paginate user.
     *
     * @param int $perPage
     * @return Users
     */
    public function paginate(int $perPage = 10);
}
