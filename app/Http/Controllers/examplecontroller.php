<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class examplecontroller extends Controller
{
    public function homepage() 
    {
        return '<h1> Home page </h1> <a href="/about"> go to about page <a>';
    }

    public function aboutpage()
    {
        return '<h1> about page </h1> <a href="/"> Back to home <a>';
    }
}
