<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ParamCollection extends ResourceCollection
{

    public $collects = ParamResource::class;

}
