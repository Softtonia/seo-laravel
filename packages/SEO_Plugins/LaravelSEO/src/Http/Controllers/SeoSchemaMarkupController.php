<?php

namespace SEO_Plugins\LaravelSEO\Http\Controllers;

use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Exception;
use SEO_Plugins\LaravelSEO\Models\SeoSchemaMarkup;

class SeoSchemaMarkupController extends Controller
{


  //  INDEX
    public function index()
    {
        try {
            $data = SeoSchemaMarkup::all();
            return response()->json(['message' => 'Data fetched successfully', 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch data', 'error' => $e->getMessage()], 500);
        }
    }

    //  STORE
    public function store(Request $request)
    {
        try {
            $request->validate([
                'model_type' => 'required|string',
                'model_id' => 'required|integer',
                'schema_type' => 'required|string',
                'schema_json' => 'required|array', // Must be an array
            ]);

            $seoSchema = SeoSchemaMarkup::create([
                'model_type' => $request->model_type,
                'model_id' => $request->model_id,
                'schema_type' => $request->schema_type,
                'schema_json' => json_encode($request->schema_json), // Encode to JSON string
            ]);

            return response()->json(['message' => 'Schema created successfully', 'data' => $seoSchema], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create schema', 'error' => $e->getMessage()], 500);
        }
    }

    //  SHOW
    public function show($id)
    {
        try {
            $schema = SeoSchemaMarkup::findOrFail($id);
            return response()->json(['message' => 'Schema fetched successfully', 'data' => $schema], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Schema not found', 'error' => $e->getMessage()], 404);
        }
    }

    //  UPDATE
    public function update(Request $request, $id)
    {
        try {
            $schema = SeoSchemaMarkup::findOrFail($id);

            $request->validate([
                'model_type' => 'nullable|string',
                'model_id' => 'nullable|integer',
                'schema_type' => 'nullable|string',
                'schema_json' => 'nullable|array', // Accept as array
            ]);

            $schema->update([
                'model_type' => $request->input('model_type', $schema->model_type),
                'model_id' => $request->input('model_id', $schema->model_id),
                'schema_type' => $request->input('schema_type', $schema->schema_type),
                'schema_json' => $request->has('schema_json') ? json_encode($request->schema_json) : $schema->schema_json,
            ]);

            return response()->json(['message' => 'Schema updated successfully', 'data' => $schema], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update schema', 'error' => $e->getMessage()], 500);
        }
    }

    //  DESTROY
    public function destroy($id)
    {
        try {
            $schema = SeoSchemaMarkup::findOrFail($id);
            $schema->delete();

            return response()->json(['message' => 'Schema deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete schema', 'error' => $e->getMessage()], 500);
        }
    }
}

