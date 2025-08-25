<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class LeavesController extends Controller
{
    public function index()
    {
        return view('admin.leaves.index');
    }
}
