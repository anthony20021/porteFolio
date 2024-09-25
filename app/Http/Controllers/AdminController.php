<?php

namespace App\Http\Controllers;

use App\Models\Codes;
use App\Models\Cv;
use App\Models\Documents;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Models\Experiences;
use App\Models\Messages;
use App\Models\Projects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $codes = Codes::selectRaw('*,FALSE AS show_description')->with('documents')->with('file')->get();
        $experiences = Experiences::selectRaw('*,FALSE AS show_description')->get();
        $projects = Projects::selectRaw('*,FALSE AS show_description')->with('documents')->get();
        $cv = Cv::selectRaw('*,FALSE AS show_description')->with('file')->get();
        $messages = Messages::orderBy('date')->get();

        return view('admin.index', ["projects" => $projects, "codes"=> $codes, "experiences"=>$experiences, "cv" => $cv, "messages" => $messages]);
    }

    // Project

    public function saveProject(Request $request){
        try {
            $data = $request->input('data');
    
            $validatedData = Validator::make($data, [
                'name' => 'required',
                'desc' => 'required',
                'path' => 'string',
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
                'front_page' => 'required',
                'path' => 'string'
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

    public function deleteProject(Request $request){
        try {
            $data = $request->input('data');
    
            $validatedData = Validator::make($data, [
                'id' => 'required',
            ])->validate();
    
            $project = Projects::find($validatedData['id']);
            $project->delete();
    
            $result = [
                'statut' => 'ok',
                'message' => 'Le projet a bien été supprimé',
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

    public function saveCode(Request $request){
        try {
            $data = $request->input('data');
    
            $validatedData = Validator::make($data, [
                'name' => 'required',
                'desc' => 'required',
            ])->validate();
    
            $new_code = Codes::create($validatedData);
    
            $result = [
                'statut' => 'ok',
                'message' => 'Le code a bien été sauvegardé',
                'data' => $new_code,
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

    public function putCode(Request $request){
        try {
            $data = $request->input('data');
    
            $validatedData = Validator::make($data, [
                'id' => 'required',
                'name' => 'required',
                'desc' => 'required',
                'front_page' => 'required',
            ])->validate();
    
            $code = Codes::find($validatedData['id']);
            $code->update($validatedData);
    
            $result = [
                'statut' => 'ok',
                'message' => 'Le code a bien été mis à jour',
                'data' => $code,
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

    public function deleteCode(Request $request){
        try {
            $data = $request->input('data');
    
            $validatedData = Validator::make($data, [
                'id' => 'required',
            ])->validate();
    
            $code = Codes::find($validatedData['id']);

            $doc = Documents::find($code->document_id);
            
            //delete doc
            File::delete(public_path($doc->path));
            $doc->delete();
            $code->delete();
    
            $result = [
                'statut' => 'ok',
                'message' => 'Le code a bien été supprimé',
                'data' => $code,
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

    public function saveCodeFile(Request $request)
    {
        try {
            DB::beginTransaction();
    
            // Validate the request inputs
            $this->validate($request, [
                'file' => 'required|max:20480|file', 
                'id' => 'required|integer',  
            ]);
    
            $id = $request->input('id');
            $code = Codes::findOrFail($id); 
    
            $file = $request->file('file');
            $name = strtolower(str_replace(' ', '_', $code->name . '_file'));
            $extension = $file->getClientOriginalExtension();
            $filename = $name . '.' . $extension;
    
            $storagePath = public_path('file');
            if (!File::isDirectory($storagePath)) {
                File::makeDirectory($storagePath, 0777, true, true);
            }

            if($code['document_id'] != null){
                $document = Documents::find($code['document_id']);
                $document->delete();
                //delete in public path
                $path = public_path($document->path);
                if (File::exists($path)) {
                    File::delete($path);
                }
            }


            $filePath = 'file/' . $filename;
            $file->move($storagePath, $filename);  
    
            $fileBd = new Documents();
            $fileBd->name = $name;
            $fileBd->path = $filePath;
            $fileBd->type = $extension;
            $fileBd->save();
    
            $code->document_id = $fileBd->id;
            $code->save();
    
            $result = [
                'statut' => 'ok',
                'message' => 'Le code a bien été sauvegardé',
                'data' => $code,
            ];
    
            DB::commit();
            return response()->json($result);
        } catch (\Throwable $th) {
            DB::rollBack();
    
            $result = [
                'statut' => 'error',
                'message' => $th->getMessage(),
            ];
    
            return response()->json($result, 400);
        }
    }

    public function saveExperience(Request $request){   
        try {
            $data = $request->input('data');
            $validatedData = Validator::make($data, [
                'name' => 'required',
                'desc' => 'required',
            ])->validate();

            $experience = Experiences::create($validatedData);
            $result = [
                'statut' => 'ok',
                'message' => 'L\'expérience a bien été sauvegardée',
                'data' => $experience,
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

    public function deleteExperience(Request $request){
        try {
            $data = $request->input('data');
            $validatedData = Validator::make($data, [
                'id' => 'required',
            ])->validate();
            $experience = Experiences::find($data['id']);
            $experience->delete();
            $result = [
                'statut' => 'ok',
                'message' => 'L\'expérience a bien été supprimée',
                'data' => $experience,
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

    public function putExperience(Request $request){
        try {
            $data = $request->input('data');
            $validatedData = Validator::make($data, [
                'id' => 'required',
                'name' => 'required',
                'desc' => 'required',
                'front_page' => 'required',
            ])->validate();
            $experience = Experiences::find($data['id']);
            $experience->update($data);
            $result = [
                'statut' => 'ok',
                'message' => 'L\'expérience a bien été modifiée',
                'data' => $experience,
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

    public function saveCv(Request $request){
        try {
            $cvs = Cv::all();
            if(count($cvs) > 0){
                return response()->json(['statut' => 'error', 'message' => "il peut n'y avoir qu'un CV"], 400);
            }
            $data = $request->input('data');
            $validatedData = Validator::make($data, [
                'name' => 'required',
                'desc' => 'required',
            ])->validate();
            $cv = Cv::create($validatedData);
            $result = [
                'statut' => 'ok',
                'message' => 'Le cv a bien été sauvegardé',
                'data' => $cv,
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

    public function deleteCv(Request $request){
        try {
            $data = $request->input('data');
    
            $validatedData = Validator::make($data, [
                'id' => 'required',
            ])->validate();
    
            $cv = Cv::find($validatedData['id']);

            $doc = Documents::find($cv->document_id);
            
            //delete doc
            File::delete(public_path($doc->path));
            $doc->delete();
            $cv->delete();
    
            $result = [
                'statut' => 'ok',
                'message' => 'Le cv a bien été supprimé',
                'data' => $cv,
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

    public function putCv(Request $request){
        try {
            $data = $request->input('data');
            $validatedData = Validator::make($data, [
                'id' => 'required',
                'name' => 'required',
                'desc' => 'required',
                'front_page' => 'required',
            ])->validate();
            $cv = Cv::find($data['id']);
            $cv->update($data);
            $result = [
                'statut' => 'ok',
                'message' => 'Le cv a bien été modifié',
                'data' => $cv,
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

    public function saveCvFile(Request $request)
    {
        try {
            DB::beginTransaction();
    
            // Validate the request inputs
            $this->validate($request, [
                'file' => 'required|max:20480|file', 
                'id' => 'required|integer',  
            ]);
    
            $id = $request->input('id');
            $cv = Cv::findOrFail($id); 
    
            $file = $request->file('file');
            $name = strtolower(str_replace(' ', '_', $cv->name . '_file'));
            $extension = $file->guessExtension();
            $filename = $name . '.' . $extension;
    
            $storagePath = public_path('file');
            if (!File::isDirectory($storagePath)) {
                File::makeDirectory($storagePath, 0777, true, true);
            }

            if($cv['document_id'] != null){
                $document = Documents::find($cv['document_id']);
                $document->delete();
                //delete in public path
                $path = public_path($document->path);
                if (File::exists($path)) {
                    File::delete($path);
                }
            }


            $filePath = 'file/' . $filename;
            $file->move($storagePath, $filename);  
    
            $fileBd = new Documents();
            $fileBd->name = $name;
            $fileBd->path = $filePath;
            $fileBd->type = $extension;
            $fileBd->save();
    
            $cv->document_id = $fileBd->id;
            $cv->save();
    
            $result = [
                'statut' => 'ok',
                'message' => 'Le cv a bien été sauvegardé',
                'data' => $cv,
            ];
    
            DB::commit();
            return response()->json($result);
        } catch (\Throwable $th) {
            DB::rollBack();
    
            $result = [
                'statut' => 'error',
                'message' => $th->getMessage(),
            ];
    
            return response()->json($result, 400);
        }
    }

    public function deleteMessage(Request $request){
        try {
            $data = $request->input('data');
    
            $validatedData = Validator::make($data, [
                'id' => 'required',
            ])->validate();
    
            $message = Messages::find($validatedData['id']);
            $message->delete();
    
            $result = [
                'statut' => 'ok',
                'message' => 'Le message a bien été supprimé',
                'data' => $message,
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
