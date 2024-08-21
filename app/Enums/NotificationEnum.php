<?php


namespace App\Enums;

/**
 * Enum representing phone settings.
 */
enum NotificationEnum: string
{
    case READ = 'read';
    case UNREAD = 'unread';
}
