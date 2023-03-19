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
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
