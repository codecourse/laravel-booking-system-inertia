<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SlotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'employees' => $this->employees->pluck('slug'),
            'datetime' => $this->time->toDateTimeString(),
            'time' => $this->time->format('H:i')
        ];
    }
}
