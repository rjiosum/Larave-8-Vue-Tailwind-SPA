<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'type' => 'country',
                'id' => $this->uuid,
                'attributes' => [
                    'id' => $this->id,
                    'name' => $this->name,
                    'ISOAlpha2' => $this->ISOAlpha2,
                    'ISOAlpha3' => $this->ISOAlpha3,
                    'status' => $this->status,
                ]
            ]
        ];
    }
}
