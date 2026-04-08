<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuideController extends Controller
{
    /**
     * Display a listing of guides.
     */
    public function index(): View
    {
        // Get guides grouped by category
        $guidesByCategory = Guide::published()
            ->orderBy('category')
            ->orderBy('title')
            ->get()
            ->groupBy('category');

        // Get all categories
        $categories = $guidesByCategory->keys()->sort()->values();

        return view('guides.index', [
            'guidesByCategory' => $guidesByCategory,
            'categories' => $categories,
        ]);
    }

    /**
     * Display the specified guide.
     */
    public function show(string $slug): View
    {
        $guide = Guide::published()
            ->where('slug', $slug)
            ->firstOrFail();

        // Get related guides from same category
        $relatedGuides = Guide::published()
            ->where('id', '!=', $guide->id)
            ->where('category', $guide->category)
            ->limit(4)
            ->get();

        // Get guides from other categories
        $otherCategories = Guide::published()
            ->where('category', '!=', $guide->category)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        // Get formatted steps
        $steps = $guide->getFormattedSteps();

        return view('guides.show', [
            'guide' => $guide,
            'steps' => $steps,
            'relatedGuides' => $relatedGuides,
            'otherCategories' => $otherCategories,
        ]);
    }

    /**
     * Display guides by category.
     */
    public function category(string $category): View
    {
        $guides = Guide::published()
            ->where('category', $category)
            ->orderBy('title')
            ->paginate(12);

        // Get all categories
        $guidesByCategory = Guide::published()
            ->get()
            ->groupBy('category');
        $categories = $guidesByCategory->keys()->sort()->values();

        return view('guides.category', [
            'guides' => $guides,
            'categories' => $categories,
            'currentCategory' => $category,
        ]);
    }
}
