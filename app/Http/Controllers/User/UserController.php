<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserSettingsRequest;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use MessageBird\Client;

class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @param UserRepositoryInterface $userRepo
     * @param Client $twilioService
     */
    public function __construct(
        protected UserRepositoryInterface $userRepo,
        protected TwilioService $twilioService
    ){}

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Define the number of items per page
            $perPage = 10;

            // Fetch paginated users from the repository
            $users = $this->userRepo->paginate($perPage);

            // Log the retrieval of users
            Log::info('Fetched all users from the repository.');

            // Return the view with users data
            return view('users.index', compact('users'));
        } catch (\Exception $e) {
            // Log the exception details
            Log::error('Error occurred while fetching users.', ['exception' => $e]);

            // Return an error view or redirect with an error message
            return view('errors.general', ['message' => 'Unable to fetch users at this time.']);
        }
    }

    /**
     * Impersonate a user by storing their ID in the session.
     *
     * @param int $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function impersonate(int $userId)
    {
        try {
            // Attempt to find the user
            $user = $this->userRepo->find($userId);

            if ($user) {
                // Store impersonated user ID in the session
                session(['impersonate' => $userId]);

                // Log the impersonation action
                Log::info('User impersonation started.', ['impersonated_user' => $userId]);

                // Redirect to the user's home page
                return redirect()->route('home.user', ['userId' => $userId]);
            } else {
                // Log the error of user not found
                Log::warning('Attempted impersonation of a non-existent user.', ['user_id' => $userId]);

                // Redirect back to the user list with an error message
                return redirect()->route('users.index')->with('error', 'User not found.');
            }
        } catch (\Exception $e) {
            // Log the exception details
            Log::error('Error occurred during user impersonation.', ['exception' => $e]);

            // Redirect back to the user list with a general error message
            return redirect()->route('users.index')->with('error', 'An error occurred while trying to impersonate the user.');
        }
    }

    public function show($id)
    {
        $user = $this->userRepo->find($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }
        return view('users.show', compact('user'));
    }

    /**
     * Update user settings including notification preferences and phone number verification.
     *
     * @param \App\Http\Requests\UpdateUserSettingsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSettings(UpdateUserSettingsRequest  $request)
    {
        // Fetch the user based on userId from the request
        $user = $this->userRepo->find($request->userId);

        // Check if user exists
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $user = $this->userRepo->find($request->userId);

        $phoneNumber = $request->input('phone_number');

        // Verify phone number using Twilio service
        try {
            // Perform the lookup
            $phoneDetails = $this->twilioService->lookup($phoneNumber);
            // Get the raw content from the response
            $content = $phoneDetails->getContent();
            // Decode the JSON content
            $decodedContent = json_decode($content, true);


            // Check if 'is_real' key exists and is true
            if (!isset($decodedContent['is_real']) || !$decodedContent['is_real']) {
                return redirect()->back()->with('error', 'The phone number is not valid.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to verify phone number: ' . $e->getMessage());
        }

        // Update user settings
        try {

            $user->notification_switch = $request->input('notification_switch');
            $user->email = $request->input('email');
            $user->phone_number = $request->input('phone_number');
            $user->save();

            return redirect()->route('users.settings', ['userId' => $request->userId])->with('success', 'Settings updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }


    /**
     * Display the settings page for a specific user.
     *
     * @param int $userId
     * @return \Illuminate\Http\Response
    */
    public function showSettings(int $userId)
    {
        try {
            // Fetch the user by ID
            $user = $this->userRepo->find($userId);

            if (!$user) {
                // Log the error and redirect back with an error message if user is not found
                Log::warning('Attempt to show settings for non-existent user.', ['user_id' => $userId]);
                return redirect()->back()->with('error', 'User not found.');
            }

            // Return the settings view with user data
            return view('users.settings', compact('user'));
        } catch (\Exception $e) {
            // Log any exceptions that occur during the process
            Log::error('Error occurred while displaying user settings.', [
                'exception' => $e->getMessage(),
                'user_id' => $userId
            ]);

            // Redirect back with an error message
            return redirect()->back()->with('error', 'Failed to display settings. Please try again.');
        }
    }

}
