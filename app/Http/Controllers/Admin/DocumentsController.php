<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DocumentsController extends Controller
{
    public function index()
    {
        return view('admin.documents.index');
    }
}
