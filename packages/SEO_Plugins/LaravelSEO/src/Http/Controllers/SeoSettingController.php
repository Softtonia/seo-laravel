<?php

namespace SEO_Plugins\LaravelSEO\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SEO_Plugins\LaravelSEO\Models\SeoSetting;
use Exception;
class SeoSettingController extends Controller
{
    public function index()
    {
        try {
            $settings = SeoSetting::all();
            return response()->json(['message' => 'All settings fetched', 'data' => $settings]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'key' => 'required|string|unique:seo_settings,key',
                'value' => 'nullable|string',
            ]);

            $setting = SeoSetting::create([
                'key' => $request->key,
                'value' => $request->value,
            ]);

            return response()->json(['message' => 'Setting created', 'data' => $setting]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Create failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $setting = SeoSetting::findOrFail($id);
            return response()->json(['message' => 'Setting found', 'data' => $setting]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Not found', 'error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $setting = SeoSetting::findOrFail($id);

            $request->validate([
                'key' => 'required|string|unique:seo_settings,key,' . $id,
                'value' => 'nullable|string',
            ]);

            $setting->update([
                'key' => $request->key,
                'value' => $request->value,
            ]);

            return response()->json(['message' => 'Setting updated', 'data' => $setting]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Update failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $setting = SeoSetting::findOrFail($id);
            $setting->delete();
            return response()->json(['message' => 'Setting deleted']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Delete failed', 'error' => $e->getMessage()], 500);
        }
    }
}
