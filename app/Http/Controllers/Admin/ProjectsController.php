<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ProjectsController extends Controller
{
    public function index()
    {
        return view('admin.projects.index');
    }
}
