<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\ContentRequest;
use App\Models\Content;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContentController extends Controller
{
    public function __construct(
        private readonly Content $content
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contents = $this->content->paginate(10);

        return Inertia::render('media/ContentIndex',
            compact('contents')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('media/ContentCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContentRequest $request)
    {
        $data = $request->validated();
        $data['code'] = str()->uuid();
        $data['slug'] = str($data['title'])->slug();

        $this->content->create($data);

        return redirect()->route('media.contents.index')->with('success', 'Content created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

