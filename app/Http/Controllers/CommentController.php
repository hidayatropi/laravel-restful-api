<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id'   => 'required|exists:posts,id',
            'comments'  => 'required',
        ]);

        $request['user_id'] = Auth::user()->id;
        $comment = Comment::create($request->all());
        
        return new CommentResource($comment->loadMissing(['commentator:id,username']));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'comments'  => 'required'
        ]);

        $comment = Comment::findOrFail($id);
        $comment->update($request->only('comments'));
        
        return new CommentResource($comment->loadMissing(['commentator:id,username']));
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete($id);

        return new CommentResource($comment->loadMissing(['commentator:id,username']));
    }
}
