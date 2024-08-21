<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'notification_switch', 'phone_number',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'notification_switch' => 'boolean',
    ];

    public function notifications(): BelongsToMany
    {
        return $this->belongsToMany(Notification::class, 'user_notifications')
            ->withPivot('is_read')
            ->withTimestamps();
    }

    /**
     * Count the number of unread notifications for the user.
     */
    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->wherePivot('is_read', false)->count();
    }
}
