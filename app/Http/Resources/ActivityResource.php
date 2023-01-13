<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
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
            'from' => $this->from,
            'to' => $this->to,
            'seconds' => $this->seconds,
            'date' => $this->date,
            'keyboard_count' => $this->keyboard_count,
            'mouse_count' => $this->mouse_count,
            'total_activity' => $this->total_activity,
            'total_activity_percentage' => $this->total_activity_percentage,
            'user_id' => $this->user_id,
            'project_id' => $this->project_id,
            'screenshots' => $this->screenshotsFullPath(),
        ];
    }
}
