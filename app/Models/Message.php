<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        return $this->belongsTo(Conversation::class)->select(
            'id',
            'conversation_type_id',
            'name',
            'user_id',
            'created_at'
        );
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
}
