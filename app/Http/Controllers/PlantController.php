<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Plant;
use Illuminate\Support\Facades\Validator;

class PlantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plant = Plant::select('plants.*', 'categories.name as category')
        ->join('categories', 'categories.id', '=', 'plants.categorie_id')
        ->get();
        return response()->json([
        "success" => true,
        "message" => "Plants List",
        "data" => $plant
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required | string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);       
        }

        $file = $request->file('image');
        $file_name = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images'), $file_name);
        $image_url = asset('images/' . $file_name);
        
        $plant = new Plant;
        $plant->name = $request->name;
        $plant->image = $image_url;
        $plant->description = $request->description;
        $plant->categorie_id = $request->category_id;
        $plant->price = $request->price;

        $plant->save();
        return response()->json([
        "success" => true,
        "message" => "Plant created successfully.",
        "data" => $plant
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $plant = Plant::find($id);

        if (!$plant) {
            return response()->json(['message' => 'Plant not found'], 404);
        }

        return response()->json([
        "success" => true,
        "message" => "Plant retrieved successfully.",
        "data" => $plant
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $plant = Plant::find($id);
        if (!$plant) {
            return response()->json(['message' => 'Plant not found'], 404);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required | string',
            'image' => 'required|string',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);       
        }

        $plant->name = $request->name;
        $plant->description = $request->description;
        $plant->categorie_id = $request->category_id;
        $plant->price = $request->price;
        $plant->image = $request->image;

        $plant->save();
        return response()->json([
        "success" => true,
        "message" => "Plant updated successfully.",
        "data" => $plant
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $plant = Plant::find($id);
        if (!$plant) {
            return response()->json(['message' => 'Plant not found'], 404);
        }
        $plant->delete();
        return response()->json([
            "success" => true,
            "message" => "Plant deleted successfully.",
            "data" => $plant
        ], 200);



    }
}
