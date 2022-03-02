<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use illuminate\Support\Str;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }
    //
    public function index(Request $request)
    {
        if ($request->search) {
            $posts = Post::where('title', 'like', '%' . $request->search . '%')
                ->orWhere('body', 'like', '%' . $request->search . '%')->latest()->paginate(4);
        } elseif ($request->category) {
            $posts = Category::where('name', $request->category)->firstOrFail()->posts()->paginate(3)->withQueryString();
        } else {
            $posts = Post::latest()->paginate(4);
        }

        $categories = Category::all();

        return view('Posts.blog', compact('posts', 'categories'));
    }


    /*    public function show($slug)
    {
        $post = Post::where('slug', '=', $slug)->first();

        return view('Posts.single-blog-post', compact('post'));
    } */
    //using Route-Model binding
    public function show(Post $post)
    {
        $category = $post->category;
        $relatedPosts = $category->posts()->where('id', '<>', $post->id)->latest()->take(3)->get();
        //dd($relatedPosts);
        return view('Posts.single-blog-post', compact('post', 'relatedPosts'));
    }

    //edit single blog post
    public function edit(Post $post)
    {
        if (auth()->user()->id !== $post->user->id) {
            abort('403');
        }
        return view('Posts.edit-blog-post', compact('post'));
    }

    //edit single blog post
    public function delete(Post $post)
    {
        if (auth()->user()->id !== $post->user->id) {
            abort('403');
        }
        $post->delete();
        return redirect()->back()->with('status', 'Post Deleted Successfully');
    }


    public function create()
    {
        $categories = Category::all();
        return view('Posts.create-blog-post', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required|image',
            'body' => 'required',
            'category_id' => 'required'
        ]);


        if (Post::latest()->first() !== null) {
            $post_id = Post::latest()->first()->id + 1;
        } else {
            $post_id = 1;
        }

        $category_id = $request->category_id;
        $title = $request->Input('title');
        $slug = Str::slug($title, '-') . '-' . $post_id;
        $user_id = Auth::user()->id;
        $body = $request->Input('body');

        //uploading the image
        $imagepath = 'storage/' . $request->file('image')->store('posts', 'public');

        $posts = new Post();
        $posts->title = $title;
        $posts->user_id = $user_id;
        $posts->slug = $slug;
        $posts->body = $body;
        $posts->image_path = $imagepath;
        $posts->category_id = $category_id;
        $posts->save();

        return redirect()->back()->with('status', 'Post Created Successfully');

        //dd('Validation Successful');
    }

    public function update(Request $request, Post $post)
    {
        if (auth()->user()->id !== $post->user->id) {
            abort('403');
            dd($post->user->id);
        }
        $request->validate([
            'title' => 'required',
            'image' => 'required|image',
            'body' => 'required'
        ]);
        $post_id = $post->id;
        $title = $request->Input('title');
        $slug = Str::slug($title, '-') . '-' . $post_id;
        $user_id = Auth::user()->id;
        $body = $request->Input('body');

        //uploading the image
        $imagepath = 'storage/' . $request->file('image')->store('posts', 'public');

        $post->title = $title;
        $post->user_id = $user_id;
        $post->slug = $slug;
        $post->body = $body;
        $post->image_path = $imagepath;

        $post->save();

        return redirect()->back()->with('status', 'Post Updated Successfully');

        //dd('Validation Successful');
    }
}
