<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserTrade extends Model
{
    use HasFactory;

    public $table = 'user_trades';

    public function user()
    {
        return $this->hasOne(User::class, 'ID', 'USER_ID');
    }
}
