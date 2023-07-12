<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'empId' => $this->emp_id,
            'empCode' => $this->emp_code,
            'lastName' => $this->last_name,
            'fullname' => $this->fullname,
            'employmentStatus' => $this->employment_status,
        ];
    }
}
