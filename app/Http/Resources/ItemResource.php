<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->whenHas('name'),
            'price' => $this->whenHas('price'),
            'is_available' => $this->whenHas('is_available'),
            'menus' => $this->whenLoaded('menus'),
            'ingredients' => $this->whenLoaded('ingredients'),
            'created_by' => $this->whenLoaded('createdBy'),
            'updated_by' => $this->whenLoaded('updatedBy'),
            'created_at' => $this->whenHas('created_at', fn ($d) => Carbon::parse($d)->diffForHumans()),
        ];
    }
}
