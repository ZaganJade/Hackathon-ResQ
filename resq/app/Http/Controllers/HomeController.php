<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Disaster;
use App\Models\Guide;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $articles   = Article::published()->latest()->limit(3)->get();
        $guides     = Guide::published()->latest()->limit(3)->get();
        $disasters  = Disaster::active()->latest()->limit(5)->get();

        return view('home.index', compact('articles', 'guides', 'disasters'));
    }
}
