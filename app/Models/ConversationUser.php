<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversationUser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'conversation_id',
        'user_id',
        'is_admin'
    ];

    /**
     * The function creates a conversation detail record for a given conversation with the user as an
     * admin.
     * 
     * @param Conversation conversation The `createConversationDetail` function takes a `Conversation`
     * object as a parameter. It then creates a new `ConversationUser` object with the following
     * attributes:
     * 
     * @return ConversationUser A new `ConversationUser` object is being returned, created with the
     * specified values for `conversation_id`, `user_id`, and `is_admin`.
     */
    public static function createConversationDetail(Conversation $conversation): ConversationUser
    {
        return self::create([
            'conversation_id' => $conversation->id,
            'user_id' => $conversation->user_id,
            'is_admin' => 1
        ]);
    }
}
