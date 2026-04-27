<?php

namespace App\Http\Controllers\Api\Orangtua;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $news = News::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $news,
        ]);
    }

    public function show($slug)
    {
        $news = News::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $news,
        ]);
    }
}
