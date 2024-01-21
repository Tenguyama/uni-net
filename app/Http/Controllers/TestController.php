<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestController extends Controller
{
    public function index(){
        return new JsonResource('Connect to docker project!!!!');
    }
}
