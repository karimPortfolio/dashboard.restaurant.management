<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
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
            'name'=> $this->whenHas('name'),
            'phone'=> $this->whenHas('phone'),
            'email'=> $this->whenHas('email'),
            'address'=> $this->whenHas('address'),
            'notes'=> $this->whenHas('notes'),
            'created_by' => $this->whenLoaded('createdBy'),
            'created_at'=> $this->whenHas('created_at', fn ($d) => Carbon::parse($d)->diffForHumans()),
        ];
    }
}
