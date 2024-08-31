<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeletedPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'title' => (string) $this->title,
            'body' => (string) $this->body,
            'cover_image' => asset('images/'. $this->cover_image),
            'pinned' => (bool) $this->pinned,
            'deleted_at' => $this->deleted_at ? Carbon::parse($this->deleted_at)->diffForHumans() : null
        ];
    }
}
