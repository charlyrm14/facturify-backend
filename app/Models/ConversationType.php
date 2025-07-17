<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
