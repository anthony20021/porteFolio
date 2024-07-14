<?php

namespace App\Http\Controllers;

use App\Models\Codes;
use App\Models\Cv;
use App\Models\Documents;
use Illuminate\Support\Facades\Validator;
use App\Models\Experiences;
use App\Models\Projects;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $codes = Codes::all();
        $experiences = Experiences::all();
        $projects = Projects::selectRaw('*,FALSE AS show_description')->with('documents')->get();
        $cv = Cv::all();

        return view('admin.index', ["projects" => $projects, "codes"=> $codes, "experiences"=>$experiences, "cv" => $cv]);
    }

    // Project

    public function saveProject(Request $request){
        try {
            $data = $request->input('data');
    
            $validatedData = Validator::make($data, [
                'name' => 'required',
                'desc' => 'required',
            ])->validate();
    
            $new_project = Projects::create($validatedData);
    
            $result = [
                'statut' => 'ok',
                'message' => 'Le projet a bien été sauvegardé',
                'data' => $new_project,
            ];
            return response()->json($result);
        } catch (\Throwable $th) {
            $result = [
                'statut' => 'error',
                'message' => $th->getMessage()
            ];
            return response()->json($result, 400);
        }
    }

    public function putProject(Request $request){
        try {
            $data = $request->input('data');
    
            $validatedData = Validator::make($data, [
                'id' => 'required',
                'name' => 'required',
                'desc' => 'required',
            ])->validate();
    
            $project = Projects::find($validatedData['id']);
            $project->update($validatedData);
    
            $result = [
                'statut' => 'ok',
                'message' => 'Le projet a bien été mis à jour',
                'data' => $project,
            ];
            return response()->json($result);
        } catch (\Throwable $th) {            
            $result = [
                'statut' => 'error',
                'message' => $th->getMessage()
            ];
            return response()->json($result, 400);
        }
    }
}
