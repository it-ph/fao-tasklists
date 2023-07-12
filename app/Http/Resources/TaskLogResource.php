<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskLogResource extends JsonResource
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
            'taskId' => $this->task_id,
            'activity' => $this->activity,
            'createdBy' => $this->created_by,
            'thetask' => $this->thetask()->select('id','task_number')->get(),
            'thecreatedby' => new UserResource($this->whenLoaded('thecreatedby'))
        ];
    }
}
