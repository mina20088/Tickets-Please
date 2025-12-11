<?php

namespace App\Models;

use App\services\v1\RequestFilter;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static filter(\App\services\v1\AuthorService $service)
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable , HasApiTokens;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_manger'
    ];

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
            'is_manger' => 'bool',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function tickets(): User|HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function scopeFilter(Builder $builder, RequestFilter $filter): Builder
    {
        return $filter->apply($builder);
    }

    public function scopeIsManger(Builder $builder , User $user):bool {

        return $user->is_manger;
    }
}
