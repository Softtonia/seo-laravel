<?php

namespace SEO_Plugins\LaravelSEO\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SEO_Plugins\LaravelSEO\Models\SeoSitemap;

class SeoSitemapController extends Controller
{

    public function getSitemapData()
    {
        $sitemaps = SeoSitemap::where('is_active', true)->get();

        return response()->json([
            'sitemaps' => $sitemaps->map(function ($sitemap) {
                return [
                    'loc' => url($sitemap->url),
                    'lastmod' => optional($sitemap->last_modified)->toW3cString(),
                    'changefreq' => $sitemap->frequency,
                    'priority' => $sitemap->priority,
                ];
            }),
        ]);
    }

    public function index()
    {
        try {
            $sitemaps = SeoSitemap::all();

            return response()->json([
                'message' => 'Sitemap list fetched successfully.',
                'data' => $sitemaps
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch sitemap list.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'url' => 'required|url|unique:seo_sitemaps',
                'frequency' => 'required|in:always,hourly,daily,weekly,monthly,yearly,never',
                'priority' => 'numeric|min:0|max:1',
                'is_active' => 'boolean',
                'last_modified' => 'nullable|date',
            ]);

            $sitemap = SeoSitemap::create($data);

            return response()->json([
                'message' => 'Sitemap created successfully.',
                'data' => $sitemap
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create sitemap.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $sitemap = SeoSitemap::findOrFail($id);

            return response()->json([
                'message' => 'Sitemap fetched successfully.',
                'data' => $sitemap
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Sitemap not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch sitemap.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $sitemap = SeoSitemap::findOrFail($id);

            $data = $request->validate([
                'url' => 'sometimes|url|unique:seo_sitemaps,url,' . $id,
                'frequency' => 'sometimes|in:always,hourly,daily,weekly,monthly,yearly,never',
                'priority' => 'sometimes|numeric|min:0|max:1',
                'is_active' => 'sometimes|boolean',
                'last_modified' => 'nullable|date',
            ]);

            $sitemap->update($data);

            return response()->json([
                'message' => 'Sitemap updated successfully.',
                'data' => $sitemap
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Sitemap not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update sitemap.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $sitemap = SeoSitemap::findOrFail($id);
            $sitemap->delete();

            return response()->json([
                'message' => 'Sitemap deleted successfully.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Sitemap not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete sitemap.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
