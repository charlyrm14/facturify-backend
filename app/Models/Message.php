<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'subject',
        'content',
        'conversation_id',
        'sender_id',
        'parent_message_id'
    ];

    /**
     * The user function returns a BelongsTo relationship with a Conversation model, selecting specific
     * columns.
     * 
     * @return BelongsTo The `user()` function is returning a BelongsTo relationship with the
     * Conversation model. The select method is used to specify which columns should be retrieved from
     * the related Conversation model. In this case, the function is returning the columns 'id',
     * 'conversation_type_id', 'name', 'user_id', and 'created_at' from the Conversation model.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    /**
     * The `conversation` function returns a BelongsTo relationship with the Conversation model,
     * selecting specific columns.
     * 
     * @return BelongsTo A BelongsTo relationship is being returned for the "conversation" function.
     * The function is defining a relationship where the current model belongs to a Conversation model.
     * The select method is used to specify which columns should be retrieved from the related
     * Conversation model.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class)->select(
            'id',
            'conversation_type_id',
            'name',
            'user_id',
            'created_at'
        );
    }

    /**
     * The function `parentMessage()` returns the parent message associated with the current message.
     * 
     * @return BelongsTo A BelongsTo relationship is being returned. The function defines a
     * relationship where the current model belongs to another model, using the self-referencing
     * relationship with the same class and 'parent_message_id' as the foreign key.
     */
    public function parentMessage(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_message_id');
    }

    /**
     * The `replies` function defines a relationship where a message can have many replies of the same
     * type.
     *
     * @return HasMany A relationship method named "replies" is being returned. This method defines a
     * one-to-many relationship using the Laravel Eloquent ORM. It specifies that the current model has
     * many instances of itself (self::class) where the foreign key 'parent_message_id' matches the
     * current model's primary key.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_message_id');
    }

    /**
     * The function `createInitialMessage` creates a new message for a conversation with specified
     * data.
     * 
     * @param Conversation conversation The `createInitialMessage` function is a static method that
     * belongs to a class. It takes two parameters:
     * @param array data The `createInitialMessage` function takes a `Conversation` object and an array
     * of data as parameters. The data array typically contains information about the initial message
     * to be created, such as the subject and content of the message.
     * 
     * @return Message A new message is being returned with the specified conversation ID, sender ID,
     * subject, and content based on the provided conversation and data.
     */
    public static function createInitialMessage(Conversation $conversation, array $data): Message
    {
        return self::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $conversation->user_id,
            ...collect($data)->only(['subject', 'content'])->all(),
        ]);
    }

    /* The `createReplyMessage` function in the `Message` model is a static method that creates a new
    reply message for a conversation with specified data. It takes two parameters: */
    public static function createReplyMessage(Conversation $conversation, array $data): Message
    {
        return self::create([
            'conversation_id' => $conversation->id,
            ...collect($data)->except(['thread_id'])->all(),
        ]);
    }
}
