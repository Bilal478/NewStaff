<?php

namespace App\Http\Resources;

use Carbon\CarbonInterval;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $today1 = $request->date . " 00:00:00";
        $today2 = $request->date . " 23:59:59";

        return [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'punchin_pin_code_active' => $this->punchin_pin_code_active,
            'worked_hours_today' =>  CarbonInterval::seconds($this->activities()->thisPeriodOfTime($today1,  $today2)->sum('seconds'))->cascade()->format('%H:%I:%S'),
        ];
    }
}
