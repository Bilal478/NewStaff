<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'label' => $this->label,
            'time_estimation' => $this->time_estimation,
            'hidden_clients' => $this->hidden_clients,
            'high_priority' => $this->high_priority,
            'default' => $this->default,
            'completed' => $this->completed,
            'project_id' => $this->project_id,
        ];
    }
}
