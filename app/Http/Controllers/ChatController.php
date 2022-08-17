<?php

namespace App\Http\Controllers;

use App\Models\ChatMessages;
use App\Models\ChatMessageStatus;
use Illuminate\Http\Request;
use App\Models\ChatRoom;
use App\Models\ChatRoomUsers;
use Carbon\Carbon;
use Auth;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms = ChatRoom::query()
            ->withWhereHas('inUsers', fn ($q) => $q->where('usersQid', auth()->id()))
            ->with(['inUsers' => fn ($q) => $q->select('*')])
            ->with('lastMessage', function ($q) {
                /**
                 * Mesajın sahibi auth değil ise ve status 3 değil ise mesaj okunmamıştır.
                 */
                $q->with('user')->with(['situations' => function ($q) {
                    $q->select('*', \DB::Raw('sum(status) as status'))->groupBy('usersQid', 'chat_messagesQid');
                }]);
            })
            ->orderBy(ChatMessages::select('created_at')->whereColumn('chat_roomQid', 'chat_room.id')->orderBy('created_at', 'desc')->limit(1), 'DESC')
            ->paginate(10);


        return response([
            "status"    =>  "success",
            "rooms"      =>  $rooms
        ]);
    }

    public function index1()
    {
        $rooms = ChatRoom::query()
            ->withWhereHas('inUsers', fn ($q) => $q->where('usersQid', auth()->id()))
            ->with('inUsers')
            ->with('message', function ($q) {
                $q->with('user');
            })
            ->with('message')
            ->orderBy(ChatMessages::select('created_at')->whereColumn('chat_roomQid', 'chat_room.id')->limit(1), 'DESC')
            ->get();

        //->paginate(10);

        return response([
            "status"    =>  "success",
            "rooms"      =>  $rooms
        ]);
    }

    public function index2()
    {
        $rooms = ChatRoom::query()
            ->withWhereHas('inUsers', fn ($q) => $q->where('usersQid', auth()->id()))
            ->with('inUsers')
            ->with('message', function ($q) {
                $q->with('user');
            })
            ->get()
            ->sortByDesc('message.created_at');

        //->paginate(10);

        return response([
            "status"    =>  "success",
            "rooms"      =>  $rooms
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!ChatRoom::where('uuid', $request->uuid)->exists()) {

            $chatRoom = ChatRoom::create([
                "uuid"            =>    $request->uuid,
                "title"            =>    $request->title,
                "photo"            =>    $request->photo,
                "is_group"        =>    $request->is_group,
                "created_at"    =>    $request->created_at
            ]);
            foreach ($request->in_users as $user) {
                ChatRoomUsers::create([
                    "chat_roomQid"  =>  $chatRoom->id,
                    "usersQid"      =>  $user["ID"]
                ]);
            }
        } else {
            $chatRoom = ChatRoom::where('uuid', $request->uuid)->first();
        }

        $message = ChatMessages::create([
            "message"           =>  $request->last_message["message"],
            "created_at"        =>  $request->last_message["created_at"],
            "usersQid"          =>  $request->last_message["user"]["ID"],
            "chat_roomQid"      =>  $chatRoom->id,
            "uuid"              =>  $request->last_message["uuid"]
        ]);



        $situations = [];
        foreach ($request->in_users as $user) {
            if ($user["ID"] == $request->last_message["user"]["ID"]) {
                continue;
            }
            array_push($situations, ChatMessageStatus::create([
                "chat_messagesQid"  =>  $message->id,
                "usersQid"          =>  $user["ID"],
                "status"            =>  0
            ]));
        }




        return response([
            "status"    =>  "success",
            "data"      => [
                //"mesajSahibiOnlineDurum" => User::select('is_online')->where('ID', $request->last_message["user"]["ID"])->first(),
                "situations"    =>  $situations
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $chatRoom = ChatRoom::where('uuid', $uuid)->with(['inUsers' => fn ($q) => $q->select('ID', 'USER_NAME')])->first();
        \DB::connection()->enableQueryLog();
        $messages = ChatMessages::query()
            ->where('chat_roomQid', $chatRoom->id)
            ->orderBy('created_at', 'desc')
            ->with('user')

            ->with(['situations' => function ($q) {
                $q->select('*', \DB::Raw('sum(status) as status'))->groupBy('usersQid', 'chat_messagesQid');
            }])

            ->paginate(3);
        $queries = \DB::getQueryLog();
        //return response($queries);


        $chatRoom["messages"] = $messages;


        return response([
            "status"    =>  "success",
            "chatRoom"      => $chatRoom
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
