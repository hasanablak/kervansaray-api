<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessages extends Model
{
    use HasFactory;
    protected $table = 'chat_messages';
    protected $fillable = ["message", "chat_roomQid", "created_at", "usersQid", "uuid"];
    public function user()
    {
        return $this->hasOne('App\Models\User', 'ID', 'usersQid')->select('ID', 'USER_NAME', 'socket_id');
    }

    public function situations()
    {
        return $this->hasMany('App\Models\ChatMessageStatus', 'chat_messagesQid', 'id');
    }
}
