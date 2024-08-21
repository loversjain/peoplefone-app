@extends('layouts.app')

@section('content')
    <div class="container">

        @section('content')
            <div class="container">
                <!-- Display success or error messages -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-12 float-lg-right">
                    <!-- All Notifications Button with Icon -->
                    <a href="{{ route('users.index') }}" class="btn btn-secondary" title="Users List">
                        <i class="fas fa-home"></i>
                    </a>
                    <a href="{{ url('home/' . $user->id . '?filter=all') }}" class="btn btn-secondary" title="All Notifications">
                        <i class="fas fa-list"></i>
                    </a>

                    <!-- Unread Notifications Button with Icon -->
                    <a href="{{ url('home/' . $user->id . '?filter=unread') }}" class="btn btn-warning" title="Unread Notifications">
                        <i class="fas fa-envelope-open-text"></i>
                    </a>

                    <!-- Read Notifications Button with Icon -->
                    <a href="{{ url('home/' . $user->id . '?filter=read') }}" class="btn btn-success" title="Read Notifications">
                        <i class="fas fa-check-circle"></i>
                    </a>


                </div>


            @if($notifications->count() > 0)
                <h4>Your Notifications: <span class="text-muted">(<h5 class="d-inline">{{ $unreadNotificationsCount }} unread notifications</h5>)</span></h4>

                    @php
                        $hasUnreadNotifications = $notifications->contains(function ($notification) {
                            return !$notification->pivot->is_read;
                        });
                    @endphp

                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Notification</th>
                            <th>Status</th>
                            @if ($hasUnreadNotifications)
                                <th>Action</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($notifications as $notification)
                            <tr class="{{ !$notification->pivot->is_read ? 'font-weight-bold' : '' }}">
                                <td>{{ $notification->short_text }}</td>
                                <td>
                                    @if(!$notification->pivot->is_read)
                                        <span class="badge badge-warning">Unread</span>
                                    @else
                                        <span class="badge badge-success">Read</span>
                                    @endif
                                </td>
                                @if ($hasUnreadNotifications)
                                    <td>
                                        @if(!$notification->pivot->is_read)
                                            <form action="{{ route('notifications.markAsRead', ['userId' => $user->id, 'id' => $notification->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Mark as Read</button>
                                            </form>

                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>


                    <div class="mt-4">
                        <!-- Pagination Links -->
                        {{ $notifications->links('vendor.pagination.bootstrap-5') }}
                    </div>

                @else
                    <br><br>
                    <div class="mt-4">
                        <div class="alert alert-primary text-center" role="alert">
                            No notifications.
                        </div>
                    </div>
        @endif
    </div>
@endsection
