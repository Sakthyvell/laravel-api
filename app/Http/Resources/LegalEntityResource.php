<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LegalEntityResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->le_id,
            'name' => $this->le_name,
        ];
    }
}