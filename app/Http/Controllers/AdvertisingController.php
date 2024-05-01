<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertising;
use App\Models\UploadStorage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AdvertisingController extends Controller
{


    public function index(Request $request)
    {
        $city = $request->query('city');
        $ads = Advertising::with('uploadStorages', 'category')->where('city',$city)->orderBy('created_at', 'desc')->paginate(15);
        return response()->json([
            'ads' => $ads
        ]);
    }

    public function find()
    {
        $ads = Advertising::with('category','uploadStorages')->where('user_id',Auth()->user()->id)->get();
        return response()->json(
            [
                'ads' => $ads
            ]
        );
    }

    public function delete($id)
    {
        $ad = Advertising::findOrFail($id);
        if (!\Gate::authorize('delete-ad', $ad)) {
            abort(403);
        }
        $ad->delete();
        return response()->json(['message' => $ad]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'price' => 'required|string|max:255', // Change to appropriate type if needed
            'description' => 'required|string',
            'map' => 'required|string',
            'phone' => 'required|string',
            'city' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $advertising = Advertising::create([
            'category_id' => $request->get('category_id'),
            'title' => $request->title,
            'status' => $request->status,
            'price' => $request->price,
            'description' => $request->description,
            'map' => $request->map,
            'phone' => $request->phone,
            'city' => $request->city,
            'user_id' => auth()->user()->id,
        ]);



        $images = $request->file('images');

        foreach ($images as $image) { // آرایه رو باز میکنه دونه دونه آپلود میکنه بعد هم ادرسشون رو میده به مدل تا ذخیره کنه با آیدی آگهی که بالا ذخیره شده
            $filename = time() . '-' . $image->getClientOriginalName();
            $path = Storage::put('public', $image);
            UploadStorage::create([
                'filename' => $filename,
                'path' => str_replace('public', 'storage', $path),
                'advertising_id' => $advertising->id,
            ]);
        }

        return response()->json($advertising, 201);
    }


    public function adminDelete($id)
    {
        $ad = Advertising::find($id)->delete();
        return response()->json([
            'ad' => $ad,
        ]);
    }


    public function search(Request $request)
    {
        $SearchQuery = $request->query('keyword');
        $SearchData = Advertising::with('category','uploadStorages')->where('title','LIKE',"%" . $SearchQuery . "%")->get();
        return response()->json([
            "data" => $SearchData,
        ]);
    }


}
