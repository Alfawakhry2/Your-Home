<?php

namespace App\Http\Resources;

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
        //this will use as if not create the resource
        //resource allow to edit column name , add , delete , as the hidden , append and more  
        return parent::toArray($request);
    }
}
