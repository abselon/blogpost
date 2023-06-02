<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function showCreateForm()
    {
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

    public function viewSinglePost(post $post) //type hiting to post model
    {   
        return view('single-post', ['post'=> $post]);   
    }
}
