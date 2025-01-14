<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->whenHas('id'),
            'full_name'=> $this->whenAppended('full_name'),
            'first_name'=> $this->whenHas('first_name'),
            'last_name'=> $this->whenHas('last_name'),
            'phone'=> $this->whenHas('phone', fn ($v) => "+212{$v}"),
            'email'=> $this->whenHas('email'),
            'role'=> $this->whenHas('role'),
            'salary'=> $this->whenHas('salary'),
            'converted_salary'=> $this->whenHas('salary', fn ($v) => Number::currency($v, 'MAD')),
            'photo' => $this->whenHas('photo'),
            'status'=> $this->whenHas('status', fn ($v) => $v->toArray()),
            'position'=> $this->whenHas('position', fn ($v) => $v->toArray()),
            'cnss_number' => $this->whenHas('cnss_number'),
            'cin_number' => $this->whenHas('cin_number'),
            'user' => UserResource::make($this->whenLoaded('user')),
            'created_by' => UserResource::make($this->whenLoaded('createdBy')),
            'updated_by'=> UserResource::make($this->whenLoaded('updatedBy')),
            'joining_date'=> $this->whenHas('joining_date'),
            'formatted_joining_date'=> $this->whenHas('joining_date', fn ($d) => Carbon::parse($d)->format('d-m-Y')),
            'joining_date_diff'=> $this->whenHas('joining_date', fn ($d) => Carbon::parse($d)->diffForHumans()),
            'created_at'=> $this->whenHas('created_at', fn ($d) => Carbon::parse($d)->diffForHumans()),
        ];
    }
}
