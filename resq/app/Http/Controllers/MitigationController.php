<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MitigationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Guide::published()->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $guides     = $query->paginate(12)->withQueryString();
        $categories = Guide::published()->select('category')->distinct()->whereNotNull('category')->pluck('category');

        return view('mitigations.index', compact('guides', 'categories'));
    }
}
