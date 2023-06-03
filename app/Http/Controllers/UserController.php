<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //now write a public function for the logout option that will terminate the user session
    public function logout()
    {
        auth()->logout(); //this will terminate the user session
        return redirect('/')->with('success', 'You are now logged out.'); //this will redirect the user to the homepage
    }

    public function showcorrecthomepage()
    {
        if (auth()->check())
        {
            return view('homepage-feed');
        }
        else
        {
            return view('homepage');
        }
    }

    public function login (Request $request)
    {
        $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);

        if (auth()->attempt(['username' => $incomingFields ['loginusername'], 'password' => $incomingFields ['loginpassword']])) //authenticates user logins
        {
            $request->session()->regenerate(); //saves the session in cookie even after page refreshes, for the user
            return redirect('/')->with('success', 'You have successfully logged in.');
        }
        else
        {
            return redirect('/')->with('failure', 'Invalid Login');
        }
    }

    public function register(Request $request)
    {   
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:15', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:6', 'confirmed']
        ]);

        $incomingFields['password'] = bcrypt($incomingFields['password']);

        $user = User::create($incomingFields);  //a model is how we perform CRUD operations on the data in the database
        auth()->login($user);
        return redirect('/')->with('success', 'Your account was successfully created, and Logged in.');
    }

    public function profile(User $user) //route hinting so laravel can look up in database
    {

        return view('profile-posts',['username' => $user->username, 'posts'=> $user->posts()->latest()->get(), 'postCount' => $user->posts()->count()]);
    }
}
