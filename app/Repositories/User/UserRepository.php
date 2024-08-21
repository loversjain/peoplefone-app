<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;

/**
 * UserRepository handles data operations related to users.
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * @var User
     */
    protected User $model;

    /**
     * Create a new repository instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Get all users.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Get paginate users.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function paginate(int $perPage = 10)
    {
        return $this->model->paginate($perPage);
    }

    /**
     * Find a user by their ID.
     *
     * @param int $id
     * @return \App\Models\User|null
     */
    public function find(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * Update a user by their ID.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\User
     */
    public function update(int $id, array $data)
    {
        $user = $this->find($id);

        if ($user) {
            $user->update($data);
        }

        return $user;
    }

    /**
     * Process the users in chunks.
     *
     * This method retrieves users in chunks of a specified size and
     * processes each chunk with the provided callback. It is useful
     * for handling large datasets efficiently without consuming too
     * much memory.
     *
     * @param int $size The number of users to retrieve per chunk.
     * @param callable $callback The callback function to process each chunk.
     *
     * @return bool Returns true if the chunking operation was successful, false otherwise.
     * @throws \Exception If an error occurs during chunking, it will be logged and rethrown.
     */
    public function chunk(int $size, callable $callback)
    {
        try {
            // Use chunk method to process users in batches
            return $this->model->chunk($size, $callback);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error occurred while chunking users.', [
                'exception' => $e,
                'chunk_size' => $size,
            ]);

            // Rethrow the exception to be handled by the calling code
            throw $e;
        }
    }

    /**
     * Update user settings.
     *
     * @param int $userId
     * @param array $data
     * @return User
     */
    public function updateSettings(int $userId, array $data): User
    {
        $user = $this->model->find($userId);

        if (!$user) {
            throw new \Exception('User not found.');
        }

        // Update user settings
        $user->update($data);

        return $user;
    }
}
