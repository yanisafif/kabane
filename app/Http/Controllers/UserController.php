<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getUser($name)
    {
        $user = DB::table('users')->where('name', $name)->first();

        return view('app.user.profile', ['user' => $user]);
    }

    public function getUpdateUser($id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        return view('app.user.update', ['user' => $user]);
    }

    public function postEditUserPrimaryInformation(Request $request){

    }

    public function postEditUserLinkInformation(Request $request){

    }

    public function postEditUserPassword(Request $request){

    }

    public function postDeleteUser(Request $request){

    }
}
