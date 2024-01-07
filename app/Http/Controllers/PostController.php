<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        // return response()->json(['data' => $posts]);
        return PostDetailResource::collection($posts->loadMissing(['getWriter:id,username', 'comments:id,post_id,user_id,comments']));
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

        $image = null;
        if ($request->file) {
            $fileName   = $this->generateRandomString();
            $extension  = $request->file->extension();
            $image      = $fileName.'.'.$extension;

            Storage::putFileAs('imageposts', $request->file, $image);
        }

        $request['image']     = $image;
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

        $image = null;
        if ($request->file) {
            $fileName   = $this->generateRandomString();
            $extension  = $request->file->extension();
            $image      = $fileName.'.'.$extension;

            Storage::putFileAs('imageposts', $request->file, $image);
        }

        $request['image']     = $image;
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

    function generateRandomString($length = 40) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
}
