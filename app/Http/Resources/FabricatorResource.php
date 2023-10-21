<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AddressResource;

class FabricatorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);

        return [
            "id" => $this->viar ? $this->id : '',
            "sid" => $this->sid,
            "name" => $this->user->name,
            "email" => $this->user->email,
            "description" => $this->description,
            "addresses" => AddressResource::collection($this->addresses),
        ];
    }
}
