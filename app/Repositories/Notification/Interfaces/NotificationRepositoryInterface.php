<?php

namespace App\Repositories\Notification\Interfaces;

use App\Models\Notification;

/**
 * Interface NotificationRepositoryInterface
 *
 * This interface defines the methods required for notification repository implementations.
 */
interface NotificationRepositoryInterface
{
    /**
     * Get all notifications.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Find a notification by its ID.
     *
     * @param int $id
     * @return \App\Models\Notification|null
     */
    public function find(int $id);

    /**
     * Mark a notification as read by its ID.
     *
     * @param int $id The ID of the notification to mark as read.
     * @return void
     */
    public function markAsRead(int $id);

    /**
     * Find all notifications for a specific user by their user ID.
     *
     * @param int $userId The ID of the user to find notifications for.
     * @return \Illuminate\Database\Eloquent\Collection|Notification[] A collection of notifications for the specified user.
     */
    public function findByUserId(int $userId);

    /**
     * Find a specific notification for a user by their user ID and notification ID.
     *
     * @param int $userId The ID of the user whose notification is to be found.
     * @param int $id The ID of the notification to be found.
     * @return \App\Models\Notification|null The notification for the specified user and ID, or null if not found.
     */
    public function findByUserIdAndId(int $userId, int $id): ?Notification;

    /**
     * Create a new notification.
     *
     * @param array $data
     * @return Notification
     */
    public function create(array $data): Notification;

    /**
     * Attach a notification to a user.
     *
     * @param Notification $notification
     * @param int $userId
     * @return void
     */
    public function attachToUser(Notification $notification, int $userId): void;

    /**
     * Attach a notification to all users.
     *
     * @param Notification $notification
     * @return void
     */
    public function attachToAllUsers(Notification $notification): void;

    /**
     * Find a notification by ID for a specific user.
     *
     * @param int $id
     * @param int $userId
     * @return Notification|null
     */
    public function findForUser(int $id, int $userId): ?Notification;

}
