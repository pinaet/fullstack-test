<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponses;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    use ApiResponses;

    protected $policyClass;
}
