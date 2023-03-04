<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'avatar'=> $this->avatar,
            'name'=> $this->name,
            'phone'=> $this->phone,
            'created_at'=> $this->created_at,
        ];
    }
}
