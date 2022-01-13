<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Str;

class ArticleResource extends JsonResource
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
            'short_content'=>Str::words($this->body, 15),
            'date'=>$this->created_at->format('d.m.Y @ H:i:s'),
            'genres'=>$this->genres->pluck('name', 'id'),
            'likes_count'=>$this->likes_count,
            'comments_count'=>$this->comments_count,
            'href'=>route('articles.show', ['slug'=>$this->slug]), 
        ];
    }
}
