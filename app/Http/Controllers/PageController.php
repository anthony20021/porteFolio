<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use Illuminate\Http\Request;
use App\Models\Projects;

class PageController extends Controller

{
    public function indexProject($id){

        $exist = Projects::where('id', $id)->exists();
        if ($exist) {
            $project = Projects::with('documents')->find($id);
            return view('page.projectPage', ['project' => $project]);   
        }

    }

    public function indexProjectAll(){
        $projects = Projects::with('documents')->get();
        return view('page.allProject', ['projects' => $projects]);
    }

    public function indexCv(){
        $cv = Cv::with('file')->get();
        return view('page.cvPage', ['cv' => $cv[0]]);
    }
}
