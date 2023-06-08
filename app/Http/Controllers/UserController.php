<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;


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
            return view('homepage-feed', ['posts' => auth()->user()->feedPosts()->latest()->get()]);
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

    private function getSharedData($user) {
        $currentlyFollowing = 0;

        if (auth()->check()) {
            $currentlyFollowing = Follow::where([['users_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        }

        View::share('sharedData', ['currentlyFollowing' => $currentlyFollowing, 'avatar' => $user->avatar, 'username' => $user->username, 'postCount' => $user->posts()->count(), 'followerCount' => $user->followers()->count(), 'followingCount' => $user->followingTheseUsers()->count()]);
    }

    public function profile(User $user) {
        $this->getSharedData($user);
        return view('profile-posts', ['posts' => $user->posts()->latest()->get()]);
    }

    public function profileFollowers(User $user) {
        $this->getSharedData($user);
        return view('profile-followers', ['followers' => $user->followers()->latest()->get()]);
    }

    public function profileFollowing(User $user) {
        $this->getSharedData($user);
        return view('profile-following', ['following' => $user->followingTheseUsers()->latest()->get()]);
    }

    public function showAvatarForm()
    {
        return view('avatar-form');
    }

    public function storeAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:3000'
        ]);

        $user = auth()->user();

        $filename = $user->id . '-' . uniqid() . '.jpg';

        $imgData = Image::make($request->file('avatar'))->fit(120)->encode('jpg');
        Storage::put('public/avatars/' . $filename, $imgData);

        //         $image = $request->file('avatar');
        // $filename = time() . '.' . $image->getClientOriginalExtension();
        // $path = public_path('avatars' . $filename);

        // Image::make($image)->fit(300, 300)->save($path);

        $oldAvatar = $user->avatar;

        $user->avatar = $filename;
        $user->save(); //saves the avatar file name to the database

        if ($oldAvatar != "/fallback-avatar.jpg")
        {
            Storage::delete(str_replace("/storage/storage","public/", $oldAvatar));
        }

        return back()->with('success', 'Congrats, Your Avatar has been set successfully.');
    }
}