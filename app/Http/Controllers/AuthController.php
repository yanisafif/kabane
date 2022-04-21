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
            return redirect()->route('user.password.register')->with('danger', 'Email is not valid.');
        }

        $email = $request->all()['emailPassword'];
        $query = DB::table('users')
            ->where(['email' => $email])
            ->first();

        if (!empty($query)){
            $uuid = Str::uuid();
            $uuidLink = url('reset/password', $uuid);

            $query = DB::table('password_resets')
                ->where(['email' => $email]);

            if(!empty($query)){
                $query->delete();
            }

            $reset = new PasswordResets;
            $reset->email = $email;
            $reset->token = $uuid;
            date_default_timezone_set("Europe/Paris");
            $reset->created_at = time();
            $reset->save();

            Mail::to($email)
                ->queue(new ResetPassword($uuidLink));

            return redirect()->route('user.login')->with('success', 'Email has been sent successfully.');
        }
        return redirect()->route('user.password.register')->with('danger', 'This email does not exist in our database.');
    }
}
