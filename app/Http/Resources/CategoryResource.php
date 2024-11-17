<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->whenHas('id'),
            'name' => $this->whenHas('name'),
            'parent_category' => CategoryResource::make($this->whenLoaded('parentCategory')),
            'created_by' => $this->whenLoaded('createdBy'),
            'created_at' => $this->whenHas('created_at', fn ($d) => Carbon::parse($d)->diffForHumans()),
        ];
    }
}
