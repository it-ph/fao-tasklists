<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserClientResource extends JsonResource
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
            'userId' => $this->user_id,
            'clientId' => $this->client_id,
            'theuser' => new UserResource($this->whenLoaded('theuser')),
            'theclient' => new ClientResource($this->whenLoaded('theclient')),
        ];
    }
}
