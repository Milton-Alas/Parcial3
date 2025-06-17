<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

class WorkersController extends Controller
{
    public function index()
    {
        return view('backend.workers');
    }
}
