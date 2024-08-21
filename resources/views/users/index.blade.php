@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row mb-3">
            <div class="col-md-6">
                <h2 class="my-4">Users List</h2>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('notifications.create') }}" class="btn btn-primary">Create New Notification</a>
            </div>
        </div>

        @include('partials.flash-messages')
        @include('partials.error-messages')

        @if($users->count() > 0)
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Unread Notifications</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->unreadNotificationsCount() }}</td>
                    <td>
                        <a href="{{ route('users.impersonate', $user->id) }}" class="btn btn-primary btn-sm">Impersonate</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
            <div class="mt-4">
                <!-- Pagination Links -->
                {{ $users->links('vendor.pagination.bootstrap-5') }}
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
