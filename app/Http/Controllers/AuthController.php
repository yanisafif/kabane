<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\PasswordResets;
use Datetime;

class AuthController extends Controller
{

    public function postLogin(Request $request)
    {
        // Form validate
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // Get credentials from request
        $credentials = $request->only('email', 'password');
        // Get if the user has checked or not the remember_me input
        $remember_me = $request->has('remember_me') ? true : false;

        // Log user with credentials and remember_me
        if (Auth::attempt($credentials, $remember_me)) {
            // Redirect on the main page of application
            return redirect()->route('kanban.board')->with('success', 'You are now logged in.');
        }

        // If the attempt fail, redirect user on login with session message
        return redirect()->route('user.login')->with('danger', 'Login details are not valid.');
    }

    public function postRegistration(Request $request)
    {
        // Array of rules
        $rules = [
            'name' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'policy' => 'required'
        ];

        // Validate the form with is data
        $validator = Validator::make($request->all(), $rules);

        // If data dont respect the validation rules, redirect on same page with error
        if ($validator->fails())
        {
            return redirect()->route('user.sign.up')->with('danger', 'Register details are not valid.');
        }

        // Get all data from the form
        $data = $request->all();
        // private function, that insert new data on database
        $check = $this->__create($data);

        // Credentials for direct connexion after registation
        $credentials = array('email' => $data['email'], 'password' => $data['password']);
        // If logged successfully redirect to the main page of the applications
        if (Auth::attempt($credentials)) {
            return redirect()->route('kanban.board')->with('success', 'You are now logged in.');
        }

        return redirect()->route('user.sign.up')->with('danger', 'Register details are not valid.');
    }


    private function __create(array $data)
    {
        // Entité USER, insertion des données
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }

    public function logOut() {
        Session::flush();
        Auth::logout();

        return redirect()->route('index.international')->with('success', 'You have been logged out successfully');
    }

    public function resetPassword(Request $request) {
        // Array of rules
        $rules = [
            'emailPassword' => 'required|email'
        ];

        // Validate the form with is data
        $validator = Validator::make($request->all(), $rules);

        // If data dont respect the validation rules, redirect on same page with error
        if ($validator->fails())
        {
            return redirect()->route('user.login')->with('danger', 'Email is not valid, Try again.');
        }

        // Insert the request data into in a variable
        $email = $request->all()['emailPassword'];

        // Query for checking if email exists in Database
        $query = DB::table('users')
            ->where(['email' => $email])
            ->first();

        // Check if the query is not empty
        if (!empty($query)){
            // Create the UUID for the path
            $uuid = Str::uuid();
            // Final path with uuid
            $uuidLink = url('user/reset/password', $uuid);

            // Query for checking if we have data email in password_resets table
            $query = DB::table('password_resets')
                ->where(['email' => $email]);

            // In case we have data we can delete the past token
            if(!empty($query)){
                $query->delete();
            }

            // Create a new request for reset password
            $reset = new PasswordResets;
            // Insert data
            $reset->email = $email;
            $reset->token = $uuid;
            date_default_timezone_set("Europe/Paris");
            $reset->created_at = time();
            // Save the query on database with is data
            $reset->save();

            // Send email notification to the user
            Mail::to($email)
                // Link uuid that we will use it in template mail
                ->queue(new ResetPassword($uuidLink));

            return redirect()->route('user.login')->with('success', 'Email has been sent successfully.');
        }
        return redirect()->route('user.login')->with('danger', 'This email does not exist in our database.');
    }


    public function getResetPasswordWithUuid($uuid) {
        // Check if we have uuid send into the page
        if(!empty($uuid)){
            // Check if we have uuid in database field token
            $query = DB::table('password_resets')
                ->where(['token' => $uuid])
                ->first();

            if (!empty($query)){
                // we have uuid in database so we retrun view with uuid for insert it in a hidden form for the next post request
                return view('user.password-reset', [
                    'uuid' => $uuid,
                ]);
            }
        }
        // In case of faillur return a message with error message
        return redirect()->route('user.login')->with('danger', 'You dont have access to this page.');
    }

    public function postResetPasswordWithUuid(Request $request) {
        // Array of rules
        $rules = [
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            'uuid' => 'required'

        ];

        // Validate the form with is data
        $validator = Validator::make($request->all(), $rules);

        // If data dont respect the validation rules, redirect on same page with error
        if ($validator->fails())
        {
            return redirect()->route('user.login')->with('danger', 'Email or password is not valid.');
        }

        // Check if we hava field with is uuid and email
        $resetTable = DB::table('password_resets')
                ->where(['token' => $request['uuid'], 'email' => $request['email']])
                ->first();

        if (!empty($resetTable)){
            // Check if user with email exist in database users
            $userEntity = DB::table('users')
                ->where(['email' => $request['email']])
                ->first();

            if (!empty($userEntity)){

                // -2h for reset this password
                date_default_timezone_set("Europe/Paris");
                // Create two different date 1) the date in databse and 2) the date now
                $intervalDateFirst = new DateTime($resetTable->created_at);
                $intervalDateSecond = new DateTime();

                // Calcul the difference in two date
                $dateDiff  = $intervalDateFirst->diff($intervalDateSecond);
                $dateFormatInMinute = $dateDiff->format('%H');

                // If date is egal or upper than 2 hour
                if($dateFormatInMinute <= 1){
                    // In this case we can update user password and delete the field password_resets
                    $userEntity = DB::table('users')
                        ->where(['id' => $userEntity->id])
                        ->update(['password' => Hash::make($request['password'])]);

                    $resetTable = DB::table('password_resets')
                        ->where(['id' => $resetTable->id,])
                        ->delete();

                    // This function was a success
                    return redirect()->route('user.login')->with('success', 'Password has been updated successfully.');

                }else{
                    // If date is upper than 2 hour then we delete the fields and we dont do update
                    $resetTable = DB::table('password_resets')
                        ->where(['id' => $resetTable->id,])
                        ->delete();

                    return redirect()->route('user.login')->with('danger', 'You have wasted too much time, the link has expired.');
                }
            }
        }
        return redirect()->route('user.login')->with('danger', 'Something went wront in the proccessing, please try again.');
    }
}
