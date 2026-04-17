<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'title'        => $this->title,
            'description'  => $this->description,
            'category'     => $this->whenLoaded(
                'category',
                fn() => [
                    'id'   => $this->category->uuid,
                    'name' => $this->category->name,
                ]
            ),
            'task_date'    => $this->task_date?->format('M d, Y'),
            'is_recurring' => $this->is_recurring,
            'completed_at'   => $this->completed_at?->format('M d, Y g:i A'),
            'created_at'   => $this->created_at?->format('M d, Y g:i A'),
            'updated_at'   => $this->updated_at?->format('M d, Y g:i A'),
        ];
    }
}
