<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'student_id',
        'full_name',
        'email',
        'password',
        'role_id',
        'course',
        'avatar'
    ];
    protected $hidden = [
        'password', 
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationship with Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($roleName)
    {
        return $this->role()->where('name', $roleName)->exists() ||
               $this->roles()->where('name', $roleName)->exists();
    }

    public function hasAnyRole($roles)
    {
        return $this->role()->whereIn('name', $roles)->exists() ||
               $this->roles()->whereIn('name', $roles)->exists();
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'student_id');
    }

    public function isAdmin()
    {
        return $this->role()->where('name', 'admin')->exists();
    }

    // Override the default email verification notification
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \Illuminate\Auth\Notifications\VerifyEmail);
    }
}