<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PeopleFone Notifications</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ route('users.index') }}">PeopleFone-App</a>
    @if(request()->is('home/*'))
        <div class="collapse navbar-collapse">
            <!-- Other nav items -->

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="{{ url('users/settings/'.$user->id)}}" class="nav-link">
                        <i class="fas fa-user"></i> Profile
                    </a>
                </li>

                @if (isset($user) && $user->notification_switch)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Notifications
                            @if (isset($user))
                                <span class="badge badge-danger">{{ $user->unreadNotificationsCount() }}</span>
                            @endif
                        </a>

                        <div class="dropdown-menu" aria-labelledby="notificationsDropdown">
                            @if (isset($user))
                                <!-- Form to mark all notifications as read -->
                                <form action="{{ route('notifications.markAllAsRead', ['userId' => $user->id]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Mark All as Read</button>
                                </form>
                                <!-- Display user notifications -->
                                @forelse($user->notifications as $notification)
                                    <a class="dropdown-item">{{ $notification->data['message'] ?? 'No message' }}</a>
                                    @php break; @endphp
                                @empty
                                    <a class="dropdown-item">No notifications available.</a>
                                @endforelse
                            @else
                                <a class="dropdown-item">No notifications available.</a>
                            @endif
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    @endif
</nav>


<div class="container mt-4">
    @yield('content')
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
