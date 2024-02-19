<?php

namespace App\Http\Resources;

use App\Bookings\Slot;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvailabilityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'date' => $this->date->toDateString(),
            'slots' => $this->slots->map(fn (Slot $slot) => SlotResource::make($slot))
        ];
    }
}
