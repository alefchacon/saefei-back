<?php

namespace App\Http\Controllers;

use App\Models\MaterialDifusion;
use Exception;
use Illuminate\Http\Request;

use function Pest\Laravel\json;

class MaterialDifusionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        // $request->validate([
        //     'archivo.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        // ]);
        try {
            
                $file = $request->file('archivo');

                $fileData = file_get_contents($file->getRealPath());
                $material = new MaterialDifusion;
                $material->idEvento = $request->idEvento;
                $material->archivo = $fileData;
                $material->save();

            $message = "Material guardado";
            return response()->json(['message' => $message], 200);
        } catch (Exception $ex) {
            
            return response()->json(['message' => $ex]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MaterialDifusion $materialDifusion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MaterialDifusion $materialDifusion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MaterialDifusion $materialDifusion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaterialDifusion $materialDifusion)
    {
        //
    }
}
