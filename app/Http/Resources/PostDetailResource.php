<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'title' => $this->title,
            'image' => Storage::disk('local')->url('imageposts/'.$this->image),
            // 'image' => asset('storage/imageposts/'.$this->image),
            'news_content' => $this->news_content,
            'created_at'   => date('Y-m-d', strtotime($this->created_at)),
            'author_id'    => $this->author_id,
            'writer'       => $this->whenLoaded('getWriter'),
            'comments'     => $this->whenLoaded('comments', function () {
                return collect($this->comments)->each(function ($comment) {
                    $comment->commentator;
                    return $comment;
                });
            }),
            'total_comments' => $this->whenLoaded('comments', function () {
                return $this->comments->count();
            }),
        ];
    }
}
