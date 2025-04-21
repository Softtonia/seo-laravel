<?php

namespace SEO_Plugins\LaravelSEO\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SEO_Plugins\LaravelSEO\Models\SeoRobot;
use Exception;

class SeoRobotController extends Controller
{
    public function index()
    {
        try {
            $seoRobot = SeoRobot::all();
            return response()->json([
                'message' => 'SEO Robot fetched successfully',
                'data' => $seoRobot
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch SEO Robot', 'error' => $e->getMessage()], 500);
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'user_agent' => 'required|string',
            'disallow' => 'nullable|string',
            'allow' => 'nullable|string',
            'sitemap_url' => 'nullable|url',
        ]);

        try {
            $robot = SeoRobot::create($request->all());
            return response()->json(['message' => 'Robot created successfully', 'data' => $robot], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Create failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $robot = SeoRobot::find($id);
            if (!$robot) {
                return response()->json(['message' => 'Robot not found'], 404);
            }
            return response()->json(['message' => 'Robot fetched successfully', 'data' => $robot], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch Robot', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_agent' => 'required|string',
            'disallow' => 'nullable|string',
            'allow' => 'nullable|string',
            'sitemap_url' => 'nullable|url',
        ]);
        try {
            $robot = SeoRobot::find($id);
            if (!$robot) {
                return response()->json(['message' => 'Robot not found'], 404);
            }
            $robot->update($request->all());
            return response()->json(['message' => 'Robot updated successfully', 'data' => $robot], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Update failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id){
        try {
            $robot = SeoRobot::find($id);
            if (!$robot) {
                return response()->json(['message' => 'Robot not found'], 404);
                }
                $robot->delete();
                return response()->json(['message' => 'Robot deleted successfully'], 200);
            } catch (Exception $e) {
                    return response()->json(['message' => 'Delete failed', 'error' => $e->getMessage()], 500);
        }

    }


}
