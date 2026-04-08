<?php

namespace App\Http\Controllers;

use App\Models\Disaster;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $query = Disaster::latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        $disasters = $query->paginate(15)->withQueryString();

        $types      = Disaster::select('type')->distinct()->pluck('type');
        $severities = ['low', 'medium', 'high', 'critical'];

        return view('news.index', compact('disasters', 'types', 'severities'));
    }
}
