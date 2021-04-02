<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticleCollectionResource extends ResourceCollection
{
    public $collects = ArticleResource::class;
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'links' => [
                'self' => route('article'),
            ],
            'meta' => [
                'options' => $request->all()
            ]
        ];
    }
}
