<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\TrendingTour;
use App\Models\TopDestination;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home',);
    }

    public function editor()
    {
        return view('editor');
    }

    public function projects()
    {
        return view('projects');
    }
    
    public function profile()
    {
        return view('profile');
    }
}
