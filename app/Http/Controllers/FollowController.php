<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function createFollow (User $user)
    {
        // RESTRICTION
        //You cant follow yourself

        if($user->id === auth()->user()->id)
        {
            return back()->with('failure', 'Why are you following yourself? Find some Friends');
        }

        //can't follow a followed person

        $existCheck = Follow::where([['users_id', '=', auth()->user()->id],['followeduser', '=', $user->id]])->count();

        if($existCheck)
        {
            return back()->with('failure', 'You are already following this user.');
        }
       
        $newFollow = new Follow;
        $newFollow->users_id = auth()->user()->id;
        $newFollow->followeduser = $user->id;
        $newFollow->save();

        return back()->with('success', 'User successfully Followed woohoo!');
    }

    public function removeFollow (User $user)
    {
        Follow::where([['users_id', '=', auth()->user()->id],['followeduser', '=', $user->id]])->delete();
        return back()->with('success', 'User has been Unfollowed');
    }
}
