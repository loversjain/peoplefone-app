<?php

namespace App\Http\Controllers\Notification;

use App\Repositories\Notification\Interfaces\NotificationRepositoryInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator; // Correct import
use Illuminate\Routing\Controller;
use App\Http\Requests\Notification\StoreNotificationRequest;

class NotificationController extends Controller
{

    /**
     * Class Constructor.
     *
     * Initializes the class with instances of NotificationRepositoryInterface
     * and UserRepositoryInterface, allowing the class to interact with both
     * notification and user repositories.
     *
     * @param \App\Repositories\Notification\Interfaces\NotificationRepositoryInterface $notificationRepo
     * @param \App\Repositories\User\Interfaces\UserRepositoryInterface $userRepo
     */
    public function __construct(
        protected NotificationRepositoryInterface $notificationRepo,
        protected UserRepositoryInterface $userRepo
    ){}

    /**
     * Show the form to create a new notification.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            // Fetch all users from the repository
                $users = $this->userRepo->all();

            // Return the view with users data
            return view('notifications.create', compact('users'));
        } catch (\Exception $e) {
            // Log the exception details
            Log::error('Error occurred while fetching users for notification creation.', [
                'exception' => $e->getMessage()
            ]);

            // Redirect back with an error message
            return redirect()->back()
                ->with('error', 'Unable to retrieve users at this time.');
        }
    }


// Inside your controller method
    /**
     * Store a newly created notification in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreNotificationRequest $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validated();

            // Create a new notification
            $notification = $this->notificationRepo->create($validatedData);

            // Attach the notification based on the destination
            if ($validatedData['destination'] === 'user') {
                $this->notificationRepo->attachToUser($notification, $validatedData['user_id']);
            } else {
                $this->notificationRepo->attachToAllUsers($notification);
            }

            return redirect()->route('users.index')->with('success', 'Notification created successfully.');
        } catch (\Exception $e) {
            // Log the exception details
            Log::error('Error occurred while creating notification.', [
                'exception' => $e
            ]);

            // Return an error message
            return redirect()->back()->with('error', 'Unable to create notification at this time.');
        }
    }


    /**
     * Mark a notification as read for a specific user.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $userId
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead(Request $request, int $userId, int $id)
    {
        try {
            // Find the notification that is associated with the user
            $notification = $this->notificationRepo->findForUser($id, $userId);

            if (!$notification) {
                // Log the error if notification is not found
                Log::warning('Notification not found.', ['user_id' => $userId, 'notification_id' => $id]);

                return redirect()->back()
                    ->with('error', 'Notification not found.');
            }

            // Access the pivot record and update the `is_read` status
            $pivot = $notification->users()->wherePivot('user_id', $userId)->first()->pivot;
            $pivot->update(['is_read' => true]);

            // Log the successful update
            Log::info('Notification marked as read.', ['user_id' => $userId, 'notification_id' => $id]);

            return redirect()->route('home.user', ['userId' => $userId])
                ->with('success', 'Notification marked as read.');
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error marking notification as read.', [
                'user_id' => $userId,
                'notification_id' => $id,
                'exception' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while marking the notification as read.');
        }
    }

    /**
     * Mark all notifications for a specific user as read.
     *
     * @param Request $request
     * @param int $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead(Request $request, int $userId)
    {
        try {
            // Fetch the user based on userId
            $user = $this->userRepo->find($userId);

            // Check if user exists
            if (!$user) {
                Log::warning('User not found when marking all notifications as read.', ['user_id' => $userId]);

                return redirect()->back()
                    ->with('error', 'User not found.');
            }

            // Mark all notifications for the user as read
            $notificationIds = $user->notifications->pluck('id')->toArray();

            if (!empty($notificationIds)) {
                $user->notifications()->updateExistingPivot(
                    $notificationIds,
                    ['is_read' => true]
                );

                Log::info('All notifications marked as read for user.', [
                    'user_id' => $userId,
                    'notification_count' => count($notificationIds)
                ]);

                return redirect()->back()
                    ->with('success', 'All notifications marked as read.');
            } else {
                Log::info('No notifications to mark as read for user.', ['user_id' => $userId]);

                return redirect()->back()
                    ->with('info', 'No notifications to mark as read.');
            }
        } catch (\Exception $e) {
            // Log the exception details
            Log::error('Error occurred while marking notifications as read.', [
                'user_id' => $userId,
                'exception' => $e
            ]);

            // Return an error message
            return redirect()->back()
                ->with('error', 'Unable to mark notifications as read at this time.');
        }
    }
}
