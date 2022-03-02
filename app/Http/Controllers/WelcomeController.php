<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    //
    public function index()
    {
        //fetch posts
        $posts = Post::latest()->take(4)->get();
        //fetch categories
        return view('welcome', compact('posts'));
    }
}
