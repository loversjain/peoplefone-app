@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Create Notification</h2>
            <a href="{{ route('users.index') }}" class="btn btn-secondary"> <i class="fas fa-home"></i></a>
        </div>

        @include('partials.flash-messages')
        @include('partials.error-messages')

        <form action="{{ route('notifications.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="type">Notification Type</label>
                <select name="type" id="type" class="form-control">
                    <option value="">Select a type</option>
                    <option value="marketing" {{ old('type') === 'marketing' ? 'selected' : '' }}>Marketing</option>
                    <option value="invoices" {{ old('type') === 'invoices' ? 'selected' : '' }}>Invoices</option>
                    <option value="system" {{ old('type') === 'system' ? 'selected' : '' }}>System</option>
                </select>
            </div>

            <div class="form-group">
                <label for="short_text">Short Text</label>
                <textarea name="short_text" id="short_text" class="form-control">{{ old('short_text') }}</textarea>
            </div>

            <div class="form-group">
                <label for="expiration">Expiration Date</label>
                <input type="date" name="expiration" id="expiration" class="form-control" value="{{ old('expiration') }}">
            </div>

            <div class="form-group">
                <label for="destination">Destination</label>
                <select name="destination" id="destination" class="form-control">
                    <option value="all" {{ old('destination') === 'all' ? 'selected' : '' }}>All Users</option>
                    <option value="user" {{ old('destination') === 'user' ? 'selected' : '' }}>Specific User</option>
                </select>
            </div>

            <div class="form-group" id="userSelect" style="display: {{ old('destination') === 'user' ? 'block' : 'none' }};">
                <label for="user_id">Select User</label>
                <select name="user_id" id="user_id" class="form-control">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Create Notification</button>
        </form>
    </div>

    <script>
        document.getElementById('destination').addEventListener('change', function () {
            var userSelect = document.getElementById('userSelect');
            if (this.value === 'user') {
                userSelect.style.display = 'block';
            } else {
                userSelect.style.display = 'none';
            }
        });

        // Trigger change event to handle userSelect visibility on page load
        document.getElementById('destination').dispatchEvent(new Event('change'));
    </script>
@endsection
