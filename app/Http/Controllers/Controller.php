<?php

namespace App\Http\Controllers;

use App\traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\ResponseTrait;

abstract class Controller
{
    use AuthorizesRequests , ApiResponse;
    protected string $PolicyClass;
    public function isAble(string $ability, $model ) {
        return $this->authorize($ability, [$model, $this->PolicyClass]);
    }
}
