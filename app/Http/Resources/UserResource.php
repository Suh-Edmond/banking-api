<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    private $token;

    public function __construct($resource, $token)
    {
        parent::__construct($resource);
        $this->token = $token;

    }


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return  [
            'id'             => $this->id,
            'first_name'     => $this->first_name,
            'last_name'      => $this->last_name,
            'email'          => $this->email,
            'telephone'      => $this->telephone,
            'location'       => $this->location,
            'gender'         => $this->gender,
            'country'        => $this->country,
            'box_number'     => $this->box_number,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
            'roles'          => RoleResource::collection($this->roles),
            'token'          => $this->token,
        ];
    }
}
