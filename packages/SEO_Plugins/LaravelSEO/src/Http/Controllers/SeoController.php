<?php

namespace SEO_Plugins\LaravelSEO\Http\Controllers;

use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SEO_Plugins\LaravelSEO\Models\SeoMeta;
use Exception;

class SeoController extends Controller
{

    // Get all SEO meta
    public function index()
    {
        try {
            $seoMeta = SeoMeta::all();
            return response()->json([
                'message' => 'SEO Meta fetched successfully',
                'data' => $seoMeta
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch SEO Meta', 'error' => $e->getMessage()], 500);
        }
    }

    // Get SEO meta by ID
    public function show($id)
    {
        try {
            $seoMeta = SeoMeta::find($id);

            if (!$seoMeta) {
                return response()->json(['message' => 'SEO Meta not found'], 404);
            }

            return response()->json([
                'message' => 'SEO Meta fetched successfully',
                'data' => $seoMeta
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error retrieving SEO Meta', 'error' => $e->getMessage()], 500);
        }
    }

    // Create new SEO meta
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'route_name' => 'nullable|string',
                'model_type' => 'nullable|string',
                'model_id' => 'nullable|integer',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string',
                'meta_keywords' => 'nullable',
                'canonical_url' => 'nullable|string',
                'og_title' => 'nullable|string',
                'og_description' => 'nullable|string',
                'og_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'twitter_title' => 'nullable|string',
                'twitter_description' => 'nullable|string',
                'twitter_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            // JSON encode meta_keywords if it's an array
            if ($request->has('meta_keywords')) {
                $metaKeywords = $request->input('meta_keywords');

                if (is_array($metaKeywords)) {
                    $validated['meta_keywords'] = json_encode($metaKeywords);
                } else {
                    // Convert comma-separated string to array before encoding
                    $validated['meta_keywords'] = json_encode(explode(',', $metaKeywords));
                }
            }

            // Handle og_image file upload
            if ($request->hasFile('og_image')) {
                $ogDir = base_path('packages/SEO_Plugins/LaravelSEO/storage/seo_plugins/images/og');
                if (!File::exists($ogDir)) {
                    File::makeDirectory($ogDir, 0755, true);
                }

                $filename = time() . '_' . $request->file('og_image')->getClientOriginalName();
                $request->file('og_image')->move($ogDir, $filename);
                $validated['og_image'] = 'seo_plugins/images/og/' . $filename;
            }

            // Handle twitter_image file upload
            if ($request->hasFile('twitter_image')) {
                $twitterDir = base_path('packages/SEO_Plugins/LaravelSEO/storage/seo_plugins/images/twitter');
                if (!File::exists($twitterDir)) {
                    File::makeDirectory($twitterDir, 0755, true);
                }

                $filename = time() . '_' . $request->file('twitter_image')->getClientOriginalName();
                $request->file('twitter_image')->move($twitterDir, $filename);
                $validated['twitter_image'] = 'seo_plugins/images/twitter/' . $filename;
            }

            $seoMeta = SeoMeta::create($validated);

            return response()->json([
                'message' => 'SEO Meta created successfully',
                'data' => $seoMeta
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to create SEO Meta',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update SEO meta by ID
    public function update(Request $request, $id)
    {
        try {
            $seoMeta = SeoMeta::findOrFail($id);

            $validated = $request->validate([
                'route_name' => 'nullable|string',
                'model_type' => 'nullable|string',
                'model_id' => 'nullable|integer',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string',
                'meta_keywords' => 'nullable',
                'canonical_url' => 'nullable|string',
                'og_title' => 'nullable|string',
                'og_description' => 'nullable|string',
                'og_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'twitter_title' => 'nullable|string',
                'twitter_description' => 'nullable|string',
                'twitter_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            // Handle meta_keywords JSON encode
            if ($request->has('meta_keywords')) {
                $metaKeywords = $request->input('meta_keywords');
                if (is_array($metaKeywords)) {
                    $validated['meta_keywords'] = json_encode($metaKeywords);
                } else {
                    $validated['meta_keywords'] = json_encode(array_map('trim', explode(',', $metaKeywords)));
                }
            }

            // Handle og_image upload
            if ($request->hasFile('og_image')) {
                $ogDir = base_path('packages/SEO_Plugins/LaravelSEO/storage/seo_plugins/images/og');
                if (!File::exists($ogDir)) {
                    File::makeDirectory($ogDir, 0755, true);
                }

                $filename = time() . '_' . $request->file('og_image')->getClientOriginalName();
                $request->file('og_image')->move($ogDir, $filename);
                $validated['og_image'] = 'seo_plugins/images/og/' . $filename;

                // Delete old image
                if ($seoMeta->og_image && File::exists(base_path('packages/SEO_Plugins/LaravelSEO/storage/' . $seoMeta->og_image))) {
                    File::delete(base_path('packages/SEO_Plugins/LaravelSEO/storage/' . $seoMeta->og_image));
                }
            }

            // Handle twitter_image upload
            if ($request->hasFile('twitter_image')) {
                $twitterDir = base_path('packages/SEO_Plugins/LaravelSEO/storage/seo_plugins/images/twitter');
                if (!File::exists($twitterDir)) {
                    File::makeDirectory($twitterDir, 0755, true);
                }

                $filename = time() . '_' . $request->file('twitter_image')->getClientOriginalName();
                $request->file('twitter_image')->move($twitterDir, $filename);
                $validated['twitter_image'] = 'seo_plugins/images/twitter/' . $filename;

                // Delete old image
                if ($seoMeta->twitter_image && File::exists(base_path('packages/SEO_Plugins/LaravelSEO/storage/' . $seoMeta->twitter_image))) {
                    File::delete(base_path('packages/SEO_Plugins/LaravelSEO/storage/' . $seoMeta->twitter_image));
                }
            }

            // Assign all validated values dynamically
            foreach ($validated as $key => $value) {
                $seoMeta->$key = $value;
            }

            $seoMeta->save();

            return response()->json([
                'message' => 'SEO Meta updated successfully',
                'data' => $seoMeta
            ]);
        } catch (Exception $e) {
            \Log::error('SEO Meta update error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update SEO Meta',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    // Delete SEO meta by ID
    public function destroy($id)
    {
        try {
            $seoMeta = SeoMeta::find($id);

            if (!$seoMeta) {
                return response()->json(['message' => 'SEO Meta not found'], 404);
            }

            $seoMeta->delete();

            return response()->json(['message' => 'SEO Meta deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete SEO Meta', 'error' => $e->getMessage()], 500);
        }
    }
}
