<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
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
            'clusterId' => $this->cluster_id,
            'clientId' => $this->client_id,
            'tlId' => $this->tl_id,
            'omId' => $this->om_id,
            'permission' => $this->permission,
            'theuser' => new UserResource($this->theuser),
            'thecluster' => new ClusterResource($this->thecluster),
            'theclient' => new ClientResource($this->theclient),
            'thetl' => new PermissionResource($this->thetl),
            'theom' => new PermissionResource($this->theom),
            // 'thecluster' => new ClusterResource($this->whenLoaded('thecluster')),
            // 'thetl' => new PermissionResource($this->whenLoaded('thetl')),
            // 'theom' => new PermissionResource($this->whenLoaded('theom')),
        ];
    }
}
