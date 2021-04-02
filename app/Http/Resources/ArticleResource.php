<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'article',
            'id' => $this->uuid,
            'attributes' => [
                'title' => $this->title,
                'slug' => $this->slug,
                'description' => $this->body,
                'status' => $this->status,
                'user' => new UserPublicResource($this->user),
                'created_at' => $this->created_at->format('d-m-Y H:i:s'),
                'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
                'created_h' => $this->created_at->diffForHumans(),
                'updated_h' => $this->updated_at->diffForHumans(),
            ],
            'link' => [
                'self' => route('article.show', ['slug' => $this->slug])
            ]
        ];
    }
}
