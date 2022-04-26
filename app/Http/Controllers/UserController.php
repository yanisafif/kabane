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
    /** access to the page for see information about one user
     *  Everyone connected can enter in this function
     *
     * @method GET
     * @param string name
     * @return Route app.user.profile
     */
    public function getUser($name)
    {
        $user = DB::table('users')->where('name', $name)->first();

        if ($user !== null) {

            // Count how much the user has created a kanban
            $countKanban = DB::table('kanbans')->where('ownerUserId', $user->id)->count();
            // Count how much user have been invited to join a Kanban
            $countCollaborative = DB::table('invitations')->where('userId', $user->id)->count();
            // Count how much the user has been selected in a item to do task
            $countItemsOwner = DB::table('items')->where('assignedUserId', $user->id)->count();

            $kanbans = $this->getLayoutData();
            return view('app.user.profile', [
                'user' => $user,
                'kanbans' => $kanbans,
                'countKanban' => $countKanban,
                'countCollaborative' => $countCollaborative,
                'countItemsOwner' => $countItemsOwner
            ]);
        }
        return redirect()->route('kanban.board')->with('danger', 'This page does not exist in this context.');
    }

    /** access to the main page form of all updating user information
     *  Only the user connected can enter in this function
     *
     * @method GET
     * @param int id
     * @return Route app.user.update | fail = kanban.board
     */
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

    /** Insert or update a new image used in site
     *
     * @method POST
     * @param Request $request
     * @return Route back
     */
    public function postUpdateAvatar(Request $request)
    {
        // Update Avatar Image

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:png,jpg,jpeg,gif|max:5000'
        ]);

        if ($validator->fails()) {
            return back()->with('danger', 'Your image is maybe corrupted, try again in few seconds.');

        }else{
            // Object file in a variable
            $image = $request->file('file');
            // Name with current time + user id + .jpg or other
            $imageName = time().'.'.auth()->user()->id.$image->extension();
            // We move image in a public dir named avatars for store here img
            $image->move(public_path('avatars'), $imageName);

            // We select the entity of the user
            $user = User::find(auth()->user()->id);
            // If he has already a image in is db that mean image is stored in server so we had to delete it before delete in db
            if (!empty($user->path_image)){
                // We get the path of image stored in server
                $existingPathImage = public_path('avatars/'.$user->path_image);
                // If file existe in this context
                if (File::exists($existingPathImage)){
                    // then delete the file
                    unlink($existingPathImage);
                }
            }

            // Now we insert OR update the field path_image with is new path image
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

    /** Delete avatar image wihout update it
     *
     * @method GET
     * @param int $id
     * @return Route back | fail = back
     */
    public function getDeleteAvatar($id){
        // Delete Avatar by clicking on trash bin img
        if (auth()->user()->id == $id){
            // We verif if auth user is id in get
            $user = User::find(auth()->user()->id);
            // Verification that we have in the field a insertion
            if (!empty($user->path_image)){
                // If we have the field, then we take the path in a varible
                $existingPathImage = public_path('avatars/'.$user->path_image);
                // Verification if the file exist in this server
                if (File::exists($existingPathImage)){
                    // delete file from the server
                    unlink($existingPathImage);
                }
                // Delete path from db field
                $query = DB::table('users')
                    ->where('id', $user->id)
                    ->update(['path_image' => null]);

                return back()->with('success', 'Image is successfully deleted.');
            }
        }
        return back()->with('danger', 'You can\'t go in this area.');
    }

    /** Edit nickname in databse
     *
     * @method POST
     * @param Request $request
     * @return Route back | fail = back
     */
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

    /** Edit email for a new one
     *
     * @method POST
     * @param Request $request
     * @return Route back | fail = back
     */
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

    /** Edit the secondary information :
     *  Title or Description
     *
     * @method POST
     * @param Request $request
     * @return Route back | fail = back
     */
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

    /** Edit link social media
     *
     * @method POST
     * @param Request $request
     * @return Route back
     */
    public function postEditUserLinkInformation(Request $request){

        // Attempt from the request
        $request->validate([
            "link_twitter" => "nullable",
            "link_facebook" => "nullable",
            "link_instagram" => "nullable",
            "link_linkedin" => "nullable",
        ]);

        // array of link that enter the user
        $requestLink = [
            'twitter' => $request->link_twitter,
            'facebook' => $request->link_facebook,
            'instagram' => $request->link_instagram,
            'linkedin' => $request->link_linkedin,
        ];

        // Starting string in array for the social media
        $defaultLink = [
            'twitter' => 'https://www.twitter.com/',
            'facebook' => 'https://www.facebook.com/',
            'instagram' => 'https://www.instagram.com/',
            'linkedin' => 'https://www.linkedin.com/in/',
        ];

        // we concatenating the starting default link + the string that enter the user in request
        foreach ($requestLink as $key => $link) {
            if(!empty($link)){
                $data[$key] = $defaultLink[$key].$link;
            }else{
                $data[$key] = '';
            }
        }

        // We insert it in the db
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

    /** Edit password
     *  attempt, old pass, new pass, repet new pass
     *
     * @method POST
     * @param Request $request
     * @return Route back | fail = back
     */
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

    /** Delete all information in the server for a user
     *
     * @method POST
     * @param Request $request
     * @return Route
     */
    public function postDeleteUser(Request $request){

        // Create a middleware for check if user is not the owner of colaborative Kanban

        // Delete user

        // Delete is personal data

        // Delete all is personal kanban

        $rules = [
            'delete_account' => 'required',
        ];
        $data = $request->all();
        // Validate the form with is data
        $validator = Validator::make($data, $rules);

        if ($validator->fails())
        {
            return back()->with('danger', 'The string is required is this context.');

        }else{
            if($data['delete_account'] == "I will come back soon"){
                $user = User::find(auth()->user()->id);
                $user->delete();
                
            }
            return back()->with('success', 'Your email has been updated with success.');
        }
    }

    /**
     * @param void
     * @return array
     */
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
