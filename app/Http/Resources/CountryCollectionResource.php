<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CountryCollectionResource extends ResourceCollection
{
    public $collects = CountryResource::class;
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'links' => [
                'self' => route('country'),
            ],
            'meta' => [
                'options' => $request->all()
            ]
        ];
    }
}
