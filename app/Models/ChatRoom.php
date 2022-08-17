<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;
    protected $table = 'chat_room';

    protected $fillable = ['uuid', 'title', 'photo', 'is_group', 'created_at'];


    public function inUsers()
    {
        return $this->belongsToMany('App\Models\User', 'chat_room_users', 'chat_roomQid', 'usersQid');
    }

    public function messages()
    {
        return $this->hasMany('App\Models\ChatMessages', 'chat_roomQid', 'id')
            ->orderBy('created_at', 'desc');
    }
    public function lastMessage()
    {
        return $this->hasOne('App\Models\ChatMessages', 'chat_roomQid', 'id')
            ->orderBy('created_at', 'desc');
    }
}
