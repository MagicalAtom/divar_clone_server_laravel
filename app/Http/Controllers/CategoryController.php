<?php

namespace App\Http\Controllers;

use App\Models\Advertising;
use App\Models\Category;
use App\Models\File;
use App\Models\Upload;
use Illuminate\Http\Request;
use Storage;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        return response()->json(['data'=>$categories]);
    }

    public function store(Request $request){
    $validation = \Validator::make(
        $request->all(),
        [
        'name'=>'string|required',
        'file'=>'required|file',
        ]);

        if ($validation->fails()){
            return response()->json(
                [
                    'message' => $validation->errors()
                ]
            );
        }

        $file = $request->file('file');

        $fileName = $file->getClientOriginalName();


        $path = Storage::put('public',$request->file('file'));


        $category = Category::query()->create([
           'name' => $request->get('name'),
           'filename' => str_replace('public','storage',$path),
        ]);

        return response()->json([
           'message' => 'category created',
            'data' => [
                'name' => $request->get('name'),
                'icon' => str_replace('public','storage',$path),
            ]
        ])->setStatusCode(200);
    }



    public function delete($id){
        $category = Category::find($id)->delete();
        return response()->json([
            'message' => 'deleted'
        ]);
    }



    public function find($category_id){
        $category = Advertising::with('category','uploadStorages')->where('category_id',$category_id)->get();
        return response()->json([
           'data' => $category,
        ]);
    }



}
