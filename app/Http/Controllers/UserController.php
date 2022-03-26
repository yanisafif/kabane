<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

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

    public function postEditUserName(Request $request){
        $rules = [
            "name" => "required|unique:users|max:50"
        ];

    }
    public function postEditEmail(Request $request){
        $rules = [
            "email" => "required|email|unique:users",
        ];

    }

    public function postEditUserPrimaryInformation(Request $request){
        $rules = [
            "title" => "nullable",
            "description" => "nullable",
        ];
    }

    public function postEditUserLinkInformation(Request $request){
        $request->validate([
            "link_twitter" => "nullable",
            "link_facebook" => "nullable",
            "link_instagram" => "nullable",
            "link_linkedin" => "nullable",
        ]);

        $requestLink = [
            'twitter' => $request->link_twitter,
            'facebook' => $request->link_facebook,
            'instagram' => $request->link_instagram,
            'linkedin' => $request->link_linkedin,
        ];
        $defaultLink = [
            'twitter' => 'https://www.twitter.com/',
            'facebook' => 'https://www.facebook.com/',
            'instagram' => 'https://www.instagram.com/',
            'linkedin' => 'https://www.linkedin.com/in/',
        ];


        foreach ($requestLink as $key => $link) {
            if(!empty($link)){
                $data[$key] = $defaultLink[$key].$link;
            }else{
                $data[$key] = '';
            }
        }

        $query = DB::table('users')
            ->updateOrInsert(
                [
                    'id' => auth()->user()->id,
                    'name' => auth()->user()->name,
                ],
                [
                    'link_twitter'=> $data['twitter'],
                    'link_facebook'=> $data['facebook'],
                    'link_instagram'=> $data['instagram'],
                    'link_linkedin'=> $data['linkedin']
                ]);

        return back()->with('success', 'Link has been updated with success');






    }

    public function postEditUserPassword(Request $request){
        $rules = [
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ];

        $data = $request->all();

        // Validate the form with is data
        $validator = Validator::make($data, $rules);

        $user = User::find(auth()->user()->id);

        if ($validator->fails() || (!\Hash::check($data['old_password'], $user->password)))
        {
            return back()->with('danger', 'Password change are not valid');

        }else{
            // Make the changes
            $user->update(['password'=> Hash::make($data['new_password'])]);
            return back()->with('succes', 'Password has been updated with succes');

        }
    }

    public function postDeleteUser(Request $request){

    }
}