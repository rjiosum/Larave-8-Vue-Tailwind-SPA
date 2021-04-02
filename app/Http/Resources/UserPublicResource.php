<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPublicResource extends JsonResource
{
    //public $preserveKeys = true;
    //public static $wrap = 'user';
    /**
     * Transform the resource into an array.
     * Format - https://jsonapi.org/format/
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'type' => 'user',
                'id' => $this->uuid,
                'attributes' => [
                    'first_name' => $this->first_name,
                    'last_name' => $this->last_name,
                    'name' => $this->full_name,
                    'email' => $this->email,
                    'avatar' => $this->avatar_url,
                ],
                'link' => [
                    'self' => route('user.profile'),
                ]
            ]
        ];
    }
}
