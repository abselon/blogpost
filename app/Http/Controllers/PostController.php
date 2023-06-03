<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function showCreateForm()
    {
        // if(auth()->check())
        // {
        //     return redirect('/'); //any non-logged in user won't be able to access other URLS
        // }
        return view('create-post');
    }

    public function storenewpost(Request $request)
    {
        $incomingFields = $request->validate([
            'title'=> 'required',
            'body'=> 'required'
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();

        $newPost = Post::create($incomingFields);
        return redirect("/post/{$newPost->id}")-> with('success', 'Congrats! New Post Successfully Created!');
    }

    public function viewSinglePost(post $post) //type hinting to post model
    {   
        $post['body'] = Str::markdown($post->body);   //adds the mrkdown support (you need to use single {} in blade document) use strip_tags to only allow specific
        return view('single-post', ['post'=> $post]);   
    }

    public function delete(Post $post)
    {
        if (auth()->user()->cannot('delete', $post))
        {
            return "You don't have the rights to perform this action";
        }
        $post->delete();
        return redirect ('/profile/'. auth()->user()->username)->with('success', 'Post successfully deleted.');
    }

}
