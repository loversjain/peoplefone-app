@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="my-4">Notifications</h2>

        @include('partials.flash-messages')
        @include('partials.error-messages')

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Type</th>
                <th>Message</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($notifications as $notification)
                <tr>
                    <td>{{ $notification->type }}</td>
                    <td>{{ $notification->short_text }}</td>
                    <td>
                        @if (!$notification->pivot->is_read)
                            <a href="{{ route('notifications.markAsRead', ['userId' => $user->id, 'id' => $notification->id]) }}" class="btn btn-success btn-sm">Mark as Read</a>
                        @else
                            <span class="text-muted">Read</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
