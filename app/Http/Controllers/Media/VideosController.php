<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VideosController extends Controller
{
    public function index()
    {
        return Inertia::render('media/videos/Upload', []);
    }
}
