<?php

namespace App\Http\Controllers;

use App\Models\CodesDocuments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use App\Models\Documents;
use App\Models\ProjectsDocuments;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
    public function save(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->validate($request, [
                'file' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:20480',
                'name' => 'string',
                'id' => 'integer',
                'table' => 'string'
            ]);

            $photo = $request->file('file'); 
            $name = $request->input('name'); 
            $id = $request->input('id'); 
            $table = $request->input('table'); 

            $name = str_replace(' ', '_', $name);
            $storagePath = public_path('images');
            $extension = $photo->guessExtension();
            $filename = $name . '_' . time() . '.' . $extension;

            if (!File::isDirectory($storagePath)) {
                File::makeDirectory($storagePath, 0777, true, true);
            }

            $filePath = 'images/' . $filename;

            $image = Image::make($photo)->encode('jpg', 60)->resize(null, 800, function ($constraint) {
                $constraint->aspectRatio();
            });

            $image->save(public_path($filePath));

            $document = new Documents();
            $document->path = $filePath;
            $document->name = $filename;
            $document->type = $extension;
            $document->save();
            $documentId = $document->id;

            if ($table == 'project') {
                $documentTable = new ProjectsDocuments();
                $documentTable->id_project = $id;
                $documentTable->id_document = $documentId;
                $documentTable->save();
            }

            if($table == 'code') {
                $documentTable = new CodesDocuments();
                $documentTable->id_code = $id;
                $documentTable->id_document = $documentId;
                $documentTable->save();
            }


            DB::commit();
            return response()->json([
                'path' => url($filePath),
                'statut' => 'ok',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage() . ' | line' . $e->getLine(),
                'statut' => 'error',
            ], 501);
        }
    }

    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();
    
            $data = $request->input('data');
    
            $validatedData = Validator::make($data, [
                'id' => 'required',
                'table' => 'required',
            ])->validate();
    
            $id = $validatedData['id']; 
            $table = $validatedData['table'];            
    
            if ($table == 'project') {
                $documentTable = ProjectsDocuments::where('id_project', $id)->first();
                if ($documentTable) {
                    $documentTable->delete();
                }
            }

            if ($table == 'code') {
                $documentTable = CodesDocuments::where('id_code', $id)->first();
                if ($documentTable) {
                    $documentTable->delete();
                }
            }
    
            $document = Documents::find($id);
            if ($document) {
                $filePath = public_path($document->path);
                $document->delete();
            }
            File::delete($filePath);
            
            DB::commit();
            return response()->json([
                'statut' => 'ok',
            ], 201);            
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage() . ' | line' . $e->getLine()
            ], 501);
        }
    }
    
}
