<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            'status' => $this->status,
            'role_name' => $this->role_name,
            'phone' => $this->phone,
            'email_verified_at' => $this->email_verified_at,
            'custom_name' => $this->custom_name
        ];
    }
}
