<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'conversation_type_id',
        'name',
        'user_id'
    ];

    /**
     * The function conversationType() returns a BelongsTo relationship with the ConversationType model
     * in PHP.
     * 
     * @return BelongsTo A BelongsTo relationship is being returned.
     */
    public function conversationType(): BelongsTo
    {
        return $this->belongsTo(ConversationType::class)->select('id', 'name', 'description');
    }

    /**
     * The user function returns the relationship between the current object and a User model.
     * 
     * @return BelongsTo A BelongsTo relationship is being returned.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->select('id', 'name', 'last_name');
    }

    public function scopeById(Builder $query, int $thread_id): void
    {
        $query->where('id', $thread_id);
    }
}
