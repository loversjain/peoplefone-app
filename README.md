# Laravel Notification and User Management Application

## Overview

This application is built using Laravel and includes features for managing users and notifications. It provides functionality for creating and managing notifications, user settings, and user impersonation.

## Features

- **User Management:**
    - List users with pagination
    - View user details
    - Impersonate users
    - Update user settings

- **Notification Management:**
    - Create notifications
    - Mark notifications as read or unread
    - Mark all notifications as read

- **User Impersonation:**
    - Impersonate a user for admin purposes

## Installation

1. **Clone the Repository:**
    ```bash
    git clone https://github.com/loversjain/peoplefone-app
    cd peoplefone-app
    ```

2. **Install Dependencies:**
    ```bash
    composer install
    ```

3. **Set Up Environment:**
   Copy the `.env.example` file to `.env` and update the environment variables:
    ```bash
    cp .env.example .env
    ```

4. **Generate Application Key:**
    ```bash
    php artisan key:generate
    ```

5. **Run Migrations:**
    ```bash
    php artisan migrate
    ```

6. **Seed the Database (Optional):**
    ```bash
    php artisan db:seed
    ```

7. **Serve the Application:**
    ```bash
    php artisan serve
    ```

## Configuration

- **Database:** Ensure your `.env` file has the correct database configuration.
- **Twilio Service:** Configure Twilio service for phone number verification.

## Routes

- **Users:**
    - `GET /users` - List all users
    - `GET /users/{id}` - View user details
    - `GET /users/impersonate/{id}` - Impersonate a user
    - `PUT /users/settings` - Update user settings
    - `GET /users/settings/{userId}` - Show user settings

- **Notifications:**
    - `GET /notifications/create` - Show form to create a new notification
    - `POST /notifications` - Store a new notification
    - `POST /notifications/{userId}/mark-as-read/{id}` - Mark a specific notification as read
    - `POST /notifications/home/{userId}/mark-all-as-read` - Mark all notifications as read for a user

- **Home:**
    - `GET /home` - Home page
    - `GET /home/{userId}` - Home page for a specific user

## Testing

To run the tests, use the following command:

```bash
php artisan test
