<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;


class UserController extends Controller
{
    public function user_socket_update(Request $request)
    {
        User::query()
            ->where('ID', $request->ID)
            ->orWhere('socket_id', $request->socket_id)->update([
                "socket_id" =>  $request->socket_id,
                "is_online" =>  $request->is_online
            ]);

        return response([
            "status"        =>  "success",
            "userInfo"      =>  User::where('ID', $request->ID)->orWhere('socket_id', $request->socket_id)->first()
        ]);
    }
}
