<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'short_text',
        'expiration',
        'destination',
        'user_id',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_notifications')
            ->withPivot('is_read')
            ->withTimestamps();
    }

    /**
     * Get the user notifications for the notification.
     */
    public function userNotifications(): HasMany
    {
        return $this->hasMany(UserNotification::class);
    }

    // Scope to filter out expired notifications
    public function scopeActive($query)
    {
        return $query->where(function($query) {
            $query->where('expiration', '>', now())
                ->orWhereNull('expiration');
        });
    }
}
