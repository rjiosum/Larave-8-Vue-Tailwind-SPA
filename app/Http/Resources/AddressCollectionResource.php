<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AddressCollectionResource extends ResourceCollection
{
    public $collects = AddressResource::class;
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
                'self' => route('address'),
            ],
            'meta' => [
                'options' => $request->all()
            ]
        ];
    }
}
