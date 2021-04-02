<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'type' => 'address',
            'id' => $this->uuid,
            'attributes' => [
                'address_1' => $this->address_1,
                'address_2' => $this->address_2,
                'town' => $this->town,
                'county' => $this->county,
                'postcode' => $this->postcode,
                'country_id' => $this->country_id,
                'country' => new CountryResource($this->country),
                'user' => new UserPublicResource($this->user),
                'created_at' => $this->created_at->format('d-m-Y H:i:s'),
                'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
                'created_h' => $this->created_at->diffForHumans(),
                'updated_h' => $this->updated_at->diffForHumans(),
            ],
            'link' => [
                'self' => route('address.show', ['uuid' => $this->uuid])
            ]
        ];
    }
}
