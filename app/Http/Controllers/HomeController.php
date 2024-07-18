<?php

namespace App\Http\Controllers;

use App\Models\Codes;
use App\Models\Cv;
use App\Models\Experiences;
use App\Models\Projects;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $projects = Projects::selectRaw('*,FALSE AS show_description')->with('documents')->where('front_page', true)->get();
        $codes = Codes::selectRaw('*,FALSE AS show_description')->with('documents')->with('file')->where('front_page', true)->get();
        $cv = Cv::selectRaw('*,FALSE AS show_description')->with('file')->where('front_page', true)->get();
        $experiences = Experiences::selectRaw('*,FALSE AS show_description')->where('front_page', true)->get();
        return view('home', ["projects" => $projects, "codes" => $codes, "cv" => $cv, "experiences" => $experiences]);
    }
}
