<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        // return response()->json(['data' => $posts]);
        return PostResource::collection($posts);
    }

    public function showNewsAndWriter($id)
    {
        $post = Post::with('getWriter:id,email,username')->findOrFail($id);
        return new PostDetailResource($post);
    }

    public function showNews($id)
    {
        $post = Post::findOrFail($id);
        return new PostDetailResource($post);
    }
}
