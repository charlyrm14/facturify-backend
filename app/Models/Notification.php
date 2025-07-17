<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'type',
        'content',
        'user_id',
        'data',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array'
    ];

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
    public function scopeByUserId(Builder $query, int $user_id)
    {
        $query->where('user_id', $user_id)->whereNull('read_at');
    }

    /**
     * The function `createNotificationReplyMessage` creates a notification for a user when someone
     * replies to a message.
     * 
     * @param int user_id The `user_id` parameter is an integer value that represents the unique
     * identifier of the user for whom the notification reply message is being created.
     * @param Message dataMessage The `dataMessage` parameter in the `createNotificationReplyMessage`
     * function is of type `Message`. It is used to pass the message data that is being replied to in
     * order to create a notification for the user.
     * 
     * @return Notification An instance of the `Notification` class is being returned.
     */
    public static function createNotificationReplyMessage(int $user_id, Message $dataMessage): Notification
    {
        return self::create([
            'type' => 'reply_message',
            'content' => 'Alguien respondio a un mensaje',
            'user_id' => $user_id,
            'data' => $dataMessage
        ]);
    }
}
