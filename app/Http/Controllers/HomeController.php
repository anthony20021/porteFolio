<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $projects = Projects::selectRaw('*,FALSE AS show_description')->with('documents')->get();
        return view('home', ["projects" => $projects]);
    }
}
