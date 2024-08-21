<?php

namespace App\Repositories\Notification;

use App\Models\Notification;
use App\Repositories\Notification\Interfaces\NotificationRepositoryInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;


class NotificationRepository implements NotificationRepositoryInterface
{


    /**
     * Create a new NotificationRepository instance.
     *
     * @param Notification $model The notification model instance.
     * @param UserRepositoryInterface $userRepo The user repository instance.
     * @return void
     */
    public function __construct(
        protected Notification $model,
        protected UserRepositoryInterface $userRepo
    ){}

    /**
     * Get all notifications.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Find a notification by its ID.
     *
     * @param int $id The ID of the notification to be found.
     * @return \App\Models\Notification|null The found notification, or null if not found.
     */
    public function find($id)
    {
        return $this->model->find($id);
    }
    /**
     * Attach a notification to a user.
     *
     * @param Notification $notification
     * @param int $userId
     * @return void
     */
    public function attachToUser(Notification $notification, int $userId): void
    {
        try {
            $user = $this->userRepo->find($userId);

            if ($user) {
                $user->notifications()->attach($notification->id);
                Log::info('Notification attached to user.', [
                    'notification_id' => $notification->id,
                    'user_id' => $userId
                ]);
            } else {
                Log::warning('User not found when attaching notification.', [
                    'user_id' => $userId
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error occurred while attaching notification to user.', [
                'exception' => $e,
                'notification_id' => $notification->id,
                'user_id' => $userId
            ]);

            throw $e;
        }
    }

    /**
     * Attach a notification to all users.
     *
     * @param Notification $notification
     * @return void
     */
    public function attachToAllUsers(Notification $notification): void
    {
        try {
            // Process users in chunks to avoid memory issues
            $this->userRepo->chunk(100, function ($users) use ($notification) {
                foreach ($users as $user) {
                    $user->notifications()->attach($notification->id);
                }

                Log::info('Notification attached to a chunk of users.', [
                    'notification_id' => $notification->id,
                    'user_count' => $users->count()
                ]);
            });

            Log::info('Notification successfully attached to all users.', [
                'notification_id' => $notification->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Error occurred while attaching notification to all users.', [
                'exception' => $e,
                'notification_id' => $notification->id
            ]);

            throw $e;
        }
    }

    /**
     * Create a new notification.
     *
     * @param array $data
     * @return Notification
     */
    public function create(array $data): Notification
    {
        try {
            $this->model->type = $data['type'];
            $this->model->short_text = $data['short_text'];
            $this->model->expiration = $data['expiration'];
            $this->model->destination = $data['destination'];
            $this->model->user_id = $data['destination'] === 'user' ? $data['user_id'] : null;
            $this->model->save();
            // Log successful creation
            Log::info('Notification created successfully.', [
                'notification_id' => $this->model->id,
                'data' => $data
            ]);

            return $this->model;
        } catch (\Exception $e) {
            Log::error('Error occurred while creating notification.', [
                'exception' => $e->getMessage(),
                'data' => $data
            ]);

            throw $e;
        }
    }

    /**
     * Mark a notification as read.
     *
     * @param int $id The ID of the notification to mark as read.
     * @return void
     */
    public function markAsRead($id)
    {
        try {
            // Find the notification by its ID
            $notification = $this->model->find($id);

            if ($notification) {
                // Update the 'is_read' status of the notification
                $notification->update(['is_read' => true]);

                // Log the successful update
                Log::info('Notification marked as read.', ['notification_id' => $id]);
            } else {
                // Log if the notification was not found
                Log::warning('Notification not found.', ['notification_id' => $id]);
            }
        } catch (\Exception $e) {
            // Log any exception that occurs
            Log::error('Error marking notification as read.', [
                'notification_id' => $id,
                'exception' => $e->getMessage(),
            ]);

            // Re-throw the exception to be handled by the caller
            throw $e;
        }
    }


    /**
     * Find notifications for a user with optional filtering.
     *
     * @param int $userId
     * @param string|null $filter 'unread' or 'read' to filter notifications by read status
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function findByUserId($userId, $filter = null)
    {
        try {
            // Create the base query to find notifications for the user
            $query = Notification::whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            });

            // Apply filter if provided
            if ($filter === 'unread') {
                $query->whereHas('users', function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                        ->where('is_read', false);
                });
            } elseif ($filter === 'read') {
                $query->whereHas('users', function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                        ->where('is_read', true);
                });
            }

            // Log the query for debugging
            Log::info('Querying notifications for user.', [
                'user_id' => $userId,
                'filter' => $filter,
            ]);

            // Execute the query and paginate the results
            $notifications = $query->with(['users' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])->paginate(10);

            return $notifications;
        } catch (\Exception $e) {
            // Log any exception that occurs
            Log::error('Error finding notifications for user.', [
                'user_id' => $userId,
                'filter' => $filter,
                'exception' => $e->getMessage(),
            ]);

            // Re-throw the exception to be handled by the caller
            throw $e;
        }
    }



    public function findByUserIdAndId($userId, $id): ?Notification
    {
        try {
            // Attempt to find the notification by user ID and notification ID
            $notification = Notification::where('user_id', $userId)
                ->where('id', $id)
                ->first();

            // Log if the notification is found
            if ($notification) {
                Log::info('Notification found.', [
                    'user_id' => $userId,
                    'notification_id' => $id,
                ]);
            } else {
                // Log if the notification is not found
                Log::warning('Notification not found.', [
                    'user_id' => $userId,
                    'notification_id' => $id,
                ]);
            }

            return $notification;
        } catch (\Exception $e) {
            // Log any exception that occurs
            Log::error('Error finding notification by user ID and notification ID.', [
                'user_id' => $userId,
                'notification_id' => $id,
                'exception' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Find a notification by ID for a specific user.
     *
     * @param int $id
     * @param int $userId
     * @return Notification|null
     */
    public function findForUser(int $id, int $userId): ?Notification
    {
        try {
            return Notification::where('id', $id)
                ->whereHas('users', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->first();
        } catch (\Exception $e) {
            Log::error('Error occurred while fetching notification for user.', [
                'exception' => $e->getMessage(),
                'notification_id' => $id,
                'user_id' => $userId
            ]);

            throw $e;
        }
    }
}
