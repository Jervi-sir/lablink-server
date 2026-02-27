<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'email',
        'phone_number',
        'password',
        'avatar',
        'is_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password'                 => 'hashed',
            'is_verified'              => 'boolean',
            'two_factor_confirmed_at'  => 'datetime',
        ];
    }

    // ─── Relationships ──────────────────────────────────────

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function businessProfile(): HasOne
    {
        return $this->hasOne(BusinessProfile::class);
    }

    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function notificationSetting(): HasOne
    {
        return $this->hasOne(NotificationSetting::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function conversationsAsUser1(): HasMany
    {
        return $this->hasMany(Conversation::class, 'user1_id');
    }

    public function conversationsAsUser2(): HasMany
    {
        return $this->hasMany(Conversation::class, 'user2_id');
    }

    public function savedProducts()
    {
        return $this->belongsToMany(Product::class, 'saved_products');
    }

    public function savedBusinesses()
    {
        return $this->belongsToMany(BusinessProfile::class, 'saved_businesses', 'user_id', 'business_id');
    }

    public function followedBusinesses()
    {
        return $this->belongsToMany(BusinessProfile::class, 'followers', 'user_id', 'business_id');
    }

    // ─── Helpers ────────────────────────────────────────────

    /**
     * Check if the user has a specific role.
     */
    public function hasRole(string $roleCode): bool
    {
        return $this->role && $this->role->code === $roleCode;
    }

    /**
     * Determine if the user is a student.
     */
    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    /**
     * Determine if the user is a business.
     */
    public function isBusiness(): bool
    {
        return $this->hasRole('business');
    }

    /**
     * Determine if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    // ─── Formatter ──────────────────────────────────────────

    /**
     * Format the user data for API responses.
     */
    public function format(): array
    {
        $data = [
            'id' => $this->id,
            'email' => $this->email,
            'phoneNumber' => $this->phone_number,
            'avatar' => $this->avatar,
            'role' => $this->role ? [
                'id' => $this->role->id,
                'code' => $this->role->code,
            ] : null,
            'isVerified' => (bool) $this->is_verified,
            'createdAt' => $this->created_at?->toIso8601String(),
            'updatedAt' => $this->updated_at?->toIso8601String(),
        ];

        if ($this->studentProfile) {
            $data['studentProfile'] = $this->studentProfile->format();
        }

        if ($this->businessProfile) {
            $data['businessProfile'] = $this->businessProfile->format();
        }

        return $data;
    }
}
