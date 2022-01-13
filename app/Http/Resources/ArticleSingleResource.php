<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleSingleResource extends JsonResource
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
            'id'=>$this->id,
            'author'=>new AuthorResource($this->user),
            'title'=>$this->title,
            'content'=>$this->body,
            'date'=>$this->created_at->format('d.m.Y @ H:i:s'),
            'genres'=>$this->genres->pluck('name', 'id'),
            'likes_count'=>$this->likes_count,
            'likes'=>new LikeCollection($this->likes),
            'comments_count'=>$this->comments_count,
            'comments'=>new CommentCollection($this->comments),
            'href'=>route('articles.show', ['slug'=>$this->slug]), 
        ];
    }


}
