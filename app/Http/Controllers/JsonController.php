<?php

namespace App\Http\Controllers;

use App\Models\JSON;
use Illuminate\Http\Request;

class JsonController extends Controller
{
    private $resources = [
        'email-domain-list'
    ];

    public function index($requestedData)
    {

        if (in_array($requestedData, $this->resources)) {
            $json = new JSON;
            $json->get($requestedData);
        }
    }
}
