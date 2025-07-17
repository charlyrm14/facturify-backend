<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConversationType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * The function `conversation()` returns a HasMany relationship for the Conversation model.
     * 
     * @return HasMany A relationship method named `conversation` is being returned, which defines a
     * one-to-many relationship between the current model and the `Conversation` model. This method
     * indicates that the current model can have multiple instances of the `Conversation` model
     * associated with it.
     */
    public function conversation(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }
}
