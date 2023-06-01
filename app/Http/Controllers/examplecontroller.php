<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class examplecontroller extends Controller
{
    public function homepage() 
    {
        //return view('homepage'); //link the controller to the viewsfile
        return view('homepage');
    }

    public function aboutpage()
    {
        return view('single-post');
    }
}
