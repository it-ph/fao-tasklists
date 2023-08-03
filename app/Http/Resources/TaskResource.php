<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'clusterId' => $this->cluster_id,
            'clientId' => $this->client_id,
            'agentId' => $this->agent_id,
            'shiftDate' => $this->shift_date,
            'dateReceived' => $this->date_received,
            'dashboardActivityId' => $this->dashboard_activity_id,
            'clientActivityId' => $this->client_activity_id,
            'description' => $this->description,
            'status' => $this->status,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'actualHandlingTime' => $this->actual_handling_time,
            'volume' => $this->volume,
            'remarks' => $this->remarks,
            'createdBy' => $this->created_by,
            'thecluster' => new ClusterResource($this->whenLoaded('thecluster')),
            'theclient' => new ClientResource($this->whenLoaded('theclient')),
            'theagent' => new UserResource($this->whenLoaded('theagent')),
            'thedashboardactivity' => new DashboardActivityResource($this->whenLoaded('thedashboardactivity')),
            'theclientactivity' => new ClientActivityResource($this->whenLoaded('theclientactivity')),
        ];
    }
}
