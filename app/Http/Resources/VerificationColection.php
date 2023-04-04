<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class VerificationColection extends ResourceCollection
{
    public $collects = VerificationResource::class;

    public function toArray($request)
    {

        return parent::toArray($request);
    }
}
