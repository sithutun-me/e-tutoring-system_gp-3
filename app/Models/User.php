<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $primaryKey = 'id';
    protected $fillable = [
        'email',
        'password',
    ];

    public function hasRole($role):bool
    {
        return $this->role_id === $role;
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'post_create_by', 'id'); // Link posts created by user
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id'); // Link posts created by user
    }



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function studentAllocations()
    {
        return $this->hasMany(Allocation::class, 'student_id', 'id');
    }

    public function tutorAllocations()
    {
        return $this->hasMany(Allocation::class, 'tutor_id', 'id');
    }

    public function staffAllocations()
    {
        return $this->hasMany(Allocation::class, 'staff_id', 'id');
    }
}
