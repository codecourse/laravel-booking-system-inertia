<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'service' => ServiceResource::make($this->service),
            'employee' => EmployeeResource::make($this->employee),
            'date' => $this->starts_at->format('F d Y'),
            'starts_at' => $this->starts_at->format('H:i'),
            'ends_at' => $this->ends_at->format('H:i'),
            'cancelled' => !is_null($this->cancelled_at)
        ];
    }
}
