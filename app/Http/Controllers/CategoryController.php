<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::all();
        return response()->json([
        "success" => true,
        "message" => "Category List",
        "data" => $category
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);       
        }
        
        $category = new Category;
        $category->name = $request->name;

        $category->save();
        return response()->json([
        "success" => true,
        "message" => "Category created successfully.",
        "data" => $category
        ], 201);
    }

    public function show(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        
        return response()->json([
        "success" => true,
        "message" => "Category retrieved successfully.",
        "data" => $category
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);       
        }

        $category->name = $request->name;

        $category->save();
        return response()->json([
        "success" => true,
        "message" => "Category updated successfully.",
        "data" => $category
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $category->delete();
        return response()->json([
            "success" => true,
            "message" => "Category deleted successfully.",
            "data" => $category
        ], 200);
    }
}
