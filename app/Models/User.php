<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The function setPasswordAttribute in PHP hashes the input password before setting it as an
     * attribute.
     * 
     * @param password The `setPasswordAttribute` function is a mutator in Laravel Eloquent that
     * automatically hashes the password before saving it to the database. This helps to ensure that
     * the password is securely stored.
     */
    public function setPasswordAttribute($password){
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * The getJWTIdentifier function returns the key of the current object for JWT identification.
     * 
     * @return The `getJWTIdentifier` function is returning the key of the current object.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * The function getJWTCustomClaims() in PHP returns an empty array.
     * 
     * @return An empty array is being returned.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The function `conversation()` returns a HasMany relationship for the Conversation model.
     * 
     * @return HasMany A relationship method named `conversation` is being returned, which defines a
     * one-to-many relationship between the current model and the `Conversation` model. This method
     * returns a `HasMany` relationship instance.
     */
    public function conversation(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }
}
