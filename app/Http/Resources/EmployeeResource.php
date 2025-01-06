<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'full_name'=> $this->whenHas('full_name'),
            'phone'=> $this->whenHas('phone'),
            'email'=> $this->whenHas('email'),
            'role'=> $this->whenHas('role'),
            'salary'=> $this->whenHas('salary'),
            'status'=> $this->whenHas('status'),
            'created_by' => $this->whenLoaded('createdBy'),
            'updated_by'=> $this->whenLoaded('updatedBy'),
            'created_at'=> $this->whenHas('created_at', fn ($d) => Carbon::parse($d)->diffForHumans()),
        ];
    }
}
