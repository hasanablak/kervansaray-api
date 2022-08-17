<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessageStatus extends Model
{
    use HasFactory;
    protected $table = 'chat_messages_status';

    protected $fillable = ['chat_messagesQid', 'usersQid', 'status'];

    public function status()
    {
        return $this->hasMany('App\Models\ChatMessageStatus', 'chat_messages_registerQid', 'id');
    }
}
