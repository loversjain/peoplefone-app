@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center">
            <h2 class="mr-3">Update Notification Settings</h2>
            <p class="mb-0 float-lg-right">
                <a href="{{ url('home/' . $user->id ) }}" class="btn btn-warning" title="Back">
                    <i class="fas fa-user"></i>
                </a>
            </p>
        </div>

        @include('partials.flash-messages')
        @include('partials.error-messages')


        <form action="{{ route('users.updateSettings', ['userId' => $user->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="notification_switch">On-Screen Notifications</label>
                <select name="notification_switch" id="notification_switch">
                    <option value="1" {{ old('notification_switch', $user->notification_switch) == 1 ? 'selected' : '' }}>Enable Notifications</option>
                    <option value="0" {{ old('notification_switch', $user->notification_switch) == 0 ? 'selected' : '' }}>Disable Notifications</option>
                </select>
                <span class="form-text text-muted">Toggle to enable/disable on-screen notifications.</span>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}" required>
                <small class="form-text text-muted">Must be a valid mobile number.</small>
            </div>

            <button type="submit" class="btn btn-primary">Update Settings</button>
        </form>
    </div>
@endsection
