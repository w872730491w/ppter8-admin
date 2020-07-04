<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'cover' => $this->cover,
            'votes' => $this->votes,
            'comments' => $this->comments,
            'views' => $this->views,
            'collects' => $this->collects,
            'status' => $this->status ? true : false,
            'user' => $this->user ? $this->user->nickname : 'ppter8',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
