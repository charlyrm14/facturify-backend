<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

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

    /**
     * The function `messages()` returns a collection of messages associated with the current object.
     * 
     * @return HasMany The `messages()` function is returning a relationship method `HasMany` which
     * defines a one-to-many relationship between the current model and the `Message` model. This means
     * that the current model can have multiple messages associated with it.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * The function `firstMessage` returns the oldest message associated with the current object.
     * 
     * @return The `firstMessage` function is returning a relationship query using Eloquent ORM in
     * Laravel. It is defining a one-to-one relationship with the `Message` model and ordering the
     * results by the `created_at` column in ascending order.
     */
    public function firstMessage()
    {
        return $this->hasOne(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * The scopeById function filters a query by a specific thread ID.
     *
     * @param Builder query The `` parameter is an instance of the
     * `Illuminate\Database\Eloquent\Builder` class, which represents a query builder for a specific
     * model in Laravel's Eloquent ORM. It allows you to construct and execute queries for retrieving
     * data from the corresponding database table.
     * @param int thread_id The `thread_id` parameter is an integer value that represents the unique
     * identifier of a thread. This parameter is used in the `scopeById` function to filter the query
     * results based on the `id` column matching the provided `thread_id`.
     */
    public function scopeById(Builder $query, int $thread_id): void
    {
        $query->where('id', $thread_id);
    }

    /**
     * The scopeByUserId function filters a query by a specific user ID.
     * 
     * @param Builder query The `` parameter is an instance of the Laravel query builder class
     * `Illuminate\Database\Eloquent\Builder`. It is used to build and execute database queries in an
     * object-oriented way within Laravel applications.
     * @param int user_id The `user_id` parameter is an integer value that is used to filter the query
     * results based on the user ID. The `scopeByUserId` function is a query scope in Laravel that can
     * be used to apply this filter when querying the database.
     */
    public function scopeByUserId(Builder $query, int $user_id): void
    {
        $query->where('user_id', $user_id);
    }

    /**
     * The function createConversation creates a Conversation object using data provided, excluding the
     * 'subject' and 'content' fields.
     *
     * @param array data The `createConversation` function takes an array as a parameter, which is
     * named ``. This array likely contains information needed to create a new conversation, such
     * as the participants, timestamps, etc. The function then processes this data by excluding the
     * 'subject' and 'content' keys before passing it
     *
     * @return Conversation An instance of the Conversation class is being returned.
     */
    public static function createConversation(array $data): Conversation
    {
        return self::create(
            collect($data)->except(['subject', 'content'])->all()
        );
    }
}
