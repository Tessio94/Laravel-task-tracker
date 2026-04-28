<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecurringTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->uuid,
            'title'             => $this->title,
            'description'       => $this->description,
            'category'          => $this->whenLoaded(
                'category',
                fn() => [
                    'id'   => $this->category->uuid,
                    'name' => $this->category->name,
                ]
            ),
            'frequency'         => $this->frequency->value,
            'frequency_config'  => $this->frequency_config,
            'start_date'        => $this->start_date,
            'end_date'          => $this->end_date,
            'created_at'        => $this->created_at?->format('M d, Y g:i A'),
            'updated_at'        => $this->updated_at?->format('M d, Y g:i A'),
        ];
    }
}
