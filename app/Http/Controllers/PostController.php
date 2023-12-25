<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|max:255',
            'news_content'  => 'required',
        ]);

        $request['author_id'] = Auth::user()->id;
        $post = Post::create($request->all());
        return new PostDetailResource($post->loadMissing('getWriter:id,username'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title'         => 'required|max:255',
            'news_content'  => 'required',
        ]);

        $post = Post::findOrFail($id);
        $post->update($request->all());
        return new PostDetailResource($post->loadMissing('getWriter:id,username'));
    }

    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->delete($id);

        return new PostDetailResource($post->loadMissing('getWriter:id,username'));
    }
}
