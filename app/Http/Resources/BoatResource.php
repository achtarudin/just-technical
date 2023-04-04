<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BoatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'owner' => $this->owner,
            'address' => $this->address,
            'size' => "{$this->size} Meter",
            'captain_name' => $this->captain_name,
            'total_abk' => "{$this->total_abk} People",
            'document_number' => $this->document_number,
            'document' => $this->document_storage,
            'image' => $this->image_storage,
            'user' => $this->author->name,
            'status' => $this->status,
            'description' => $this->description,
            'admin' => $this->admin->name,
        ];
    }
}
