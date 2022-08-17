<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Middleware\BasicTokenMiddleware;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(BasicTokenMiddleware::class, ['only' => ['show', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        $request->validate([
            "name"      =>  ['required', 'max:100'],
            "surname"   =>  ['required', 'max:100'],
            "tcno"      =>  ['required', 'max:11'],
            "password"  =>  ['required'],
            "email"     =>  ['required', 'unique:users', "max:100"],
            "gsm"       =>  ['required'],
            "address"   =>  ['required']
        ]);


        $user = User::create($request->all());

        return response([
            "status"    =>  "success",
            "data"      =>  $user
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
        try {
            User::where('id', $id)->update($request->all());

            return response([
                "status"    =>  "success"
            ]);
        } catch (\Exception $e) {
            return response([
                "status"    =>  "success",
                "message"   =>  $e->getMessage()
            ]);
        }
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