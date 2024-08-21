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

## Verification

### Access the Application

1. Open your web browser and navigate to `http://localhost:8000`. You should see the home page of the RIN2 application.

### Check Notifications Functionality

1. **Create a Notification**
   - Go to `/notifications/create` and fill out the form to create a new notification.
   
2. **View Notifications**
   - Access `/home` to see notifications and verify that they appear as expected.

### User Management

1. **View Users**
   - Navigate to `/users` to check the list of users.
   
2. **Impersonate a User**
   - Use the route `/users/impersonate/{userId}` to impersonate a user and verify that the functionality works.

### Phone Number Verification

- If Twilio is configured, test phone number verification by following the steps outlined in the application’s phone number verification section.

### Check Logs and Errors

- Monitor the application logs for any errors or warnings:

   ```bash
   tail -f storage/logs/laravel-2024-08-22.log

