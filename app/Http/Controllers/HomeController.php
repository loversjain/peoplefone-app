<?php

namespace App\Http\Controllers;

use App\Enums\NotificationEnum;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Class HomeController
 * @package App\Http\Controllers
 *
 * Controller to handle the home page functionality including notifications.
 */
class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @param UserRepositoryInterface $userRepo
     * @return void
     */
    public function __construct( protected UserRepositoryInterface $userRepo){}

    /**
     * Display the home page with user notifications.
     *
     * @param Request $request
     * @param int $userId
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, int $userId)
    {
        try {
            // Fetch the user based on userId
            $user = $this->userRepo->find($userId);

            // Redirect to home page with the authenticated user if the user is not found
            if (!$user) {
                Log::warning('User not found during home page request.',
                    ['user_id' => $userId, 'request_user' => Auth::id()]
                );

                return redirect()->route('home', ['userId' => $userId])->with('error', 'User not found.');
            }

            // Get filter type from the request, default to 'all' if no filter is specified
            $filter = $request->get('filter', 'all');

            // Build the query for notifications based on the filter
            $query = $user->notifications()->active()->newQuery();

            if ($filter === NotificationEnum::UNREAD->value) {
                $query->wherePivot('is_read', false);
            } elseif ($filter === NotificationEnum::READ->value) {
                $query->wherePivot('is_read', true);
            }

            // Paginate notifications
            $notifications = $query->orderBy('id', 'desc')->paginate(10);

            // Count unread notifications
            $unreadNotificationsCount = $user->notifications()->wherePivot('is_read', false)->count();

            // Log the successful retrieval of notifications
            Log::info('Notifications retrieved successfully.', [
                'user_id' => $userId,
                'filter' => $filter,
                'unread_count' => $unreadNotificationsCount
            ]);

            // Pass data to the view
            return view('home', compact('notifications', 'unreadNotificationsCount', 'user', 'filter'));

        } catch (\Exception $e) {
            // Log the exception details
            Log::error('Error occurred while fetching notifications.', [
                'user_id' => $userId,
                'exception' => $e
            ]);

            // Return an error view or redirect with an error message
            return view('errors.general', ['message' => 'Unable to fetch notifications at this time.']);
        }
    }
}
