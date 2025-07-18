<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    
    /**
     * The toArray function in PHP converts a user object into an associative array with specific
     * attributes and nested role information.
     * 
     * @param user The `toArray` function you provided seems to be a method in a class that converts a
     * user object into an array. It includes user details like id, name, last name, mother's name,
     * birth date, email, user code, role id, and role details.
     * 
     * @return An array is being returned with the following keys and values:
     * - 'id' => ->id
     * - 'name' => ->name
     * - 'last_name' => ->last_name
     * - 'mothers_name' => ->mothers_name
     * - 'birth_date' => ->birth_date
     * - 'email' => ->email
     */
    public function toArray($message)
    {
        return [
            'id' => $this->id,
            'conversation_id' => $this->conversation_id,
            'content' => $this->content,
            'sender_id' => $this->sender_id,
            'parent_message_id' => $this->parent_message_id,
            'subject' => $this->subject,
            'read_at' => $this->read_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'conversation' => [
                'id' => $this->conversation->id,
                'conversation_type_id' => $this->conversation->conversation_type_id,
                'name' => $this->conversation->name,
                'user_id' => $this->conversation->user_id,
                'created_at' => $this->conversation->created_at,
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'last_name' => $this->user->last_name,
                'email' => $this->user->email,
                'email_verified_at' => $this->user->email_verified_at,
                'created_at' => $this->user->created_at,
                'updated_at' => $this->user->updated_at,
            ],
            'parent_message' => $this->whenLoaded('parentMessage', function () {
                return new self($this->parentMessage);
            }),
        ];
    }
}