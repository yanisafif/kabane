<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\Kanban;
use App\Models\Invitation;

class UserController extends Controller
{
    public function getUser($name)
    {
        $user = DB::table('users')->where('name', $name)->first();

        if ($user !== null) {
            $kanbans = $this->getLayoutData();
            return view('app.user.profile', ['user' => $user, 'kanbans' => $kanbans]);
        }
        return redirect()->route('kanban.board')->with('danger', 'This page does not exist in this context.');
    }

    public function getUpdateUser($id)
    {
        if (auth()->user()->id == $id){
            $user = DB::table('users')->where('id', auth()->user()->id)->first();
            if ($user !== null) {
                $kanbans = $this->getLayoutData();
                return view('app.user.update', ['user' => $user, 'kanbans' => $kanbans]);
            }
        }
        return redirect()->route('kanban.board')->with('danger', 'You dont have access to this page.');
    }

    public function postUpdateAvatar(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:png,jpg,jpeg,gif|max:5000'
        ]);

        if ($validator->fails()) {
            return back()->with('danger', 'Your image is maybe corrupted, try again in few seconds.');

        }else{
            $image = $request->file('file');
            $imageName = time().'.'.auth()->user()->id.$image->extension();
            $image->move(public_path('avatars'), $imageName);

            $user = User::find(auth()->user()->id);
            if (!empty($user->path_image)){
                $existingPathImage = public_path('avatars/'.$user->path_image);
                if (File::exists($existingPathImage)){
                    unlink($existingPathImage);
                }
            }

            $query = DB::table('users')
                ->updateOrInsert(
                    [
                        'id' => auth()->user()->id,
                        'name' => auth()->user()->name,
                    ],
                    [
                        'path_image'=> $imageName,
                    ]
                );

            return back()->with('success', 'Woaaaaw, you will looking so good with this new avatar.');
        }
    }

    public function getDeleteAvatar($id){
        if (auth()->user()->id == $id){
            $user = User::find(auth()->user()->id);
            if (!empty($user->path_image)){
                $existingPathImage = public_path('avatars/'.$user->path_image);
                if (File::exists($existingPathImage)){
                    unlink($existingPathImage);
                }
                $query = DB::table('users')
                    ->where('id', $user->id)
                    ->update(['path_image' => null]);

                return back()->with('success', 'Image is successfully deleted.');
            }
        }
    }

    public function postEditUserName(Request $request){
        $rules = [
            "name" => "required|unique:users|max:50"
        ];

        $data = $request->all();

        // Validate the form with is data
        $validator = Validator::make($data, $rules);
        $user = User::find(auth()->user()->id);

        if ($validator->fails())
        {
            return back()->with('danger', 'Pseudo is not valid or already used.');

        }else{
            // Make the changes
            $user->update(['name' => $data['name']]);
            return back()->with('success', 'Your pseudo has been updated with success.');

        }

    }
    public function postEditEmail(Request $request){
        $rules = [
            "email" => "required|email|unique:users",
        ];

        $data = $request->all();

        // Validate the form with is data
        $validator = Validator::make($data, $rules);
        $user = User::find(auth()->user()->id);

        if ($validator->fails())
        {
            return back()->with('danger', 'Email is not valid or already used.');

        }else{
            // Make the changes
            $user->update(['email'=>$data['email']]);
            return back()->with('success', 'Your email has been updated with success.');

        }

    }

    public function postEditUserSecondaryInformation(Request $request){
        $request->validate([
            "title" => "nullable",
            "description" => "nullable",
        ]);

        $query = DB::table('users')
            ->updateOrInsert(
                [
                    'id' => auth()->user()->id,
                    'name' => auth()->user()->name,
                ],
                [
                    'title'=> $request->title,
                    'description'=> $request->description,
                ]);

        if($query){
            return back()->with('success', 'Your information has been updated with success.');
        }else{
            return back()->with('danger', 'Oups ! something went wrong retry in few secondes.');
        }


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
            return back()->with('success', 'Password has been updated with success.');

        }
    }

    public function postDeleteUser(Request $request){

        // Create a middleware for check if user is not the owner of colaborative Kanban

        // Delete user

        // Delete is personal data

        // Delete all is personal kanban
    }

    protected function getLayoutData()
    {
        $userId = \Auth::user()->id;
        $data = [];

        $data['invitedKanban'] = Kanban::query()
            ->whereIn(
                'id',
                Invitation::query()
                    ->where('userId', '=', $userId)
                    ->select('userId')
                    ->get()
            )
            ->select('id', 'name', 'isActive', 'ownerUserId')
            ->get();

        $data['ownedKanban'] = Kanban::query()
            ->where('ownerUserId', '=', $userId)
            ->select('id', 'name', 'isActive', 'ownerUserId')
            ->get();

        return $data;
    }
}
