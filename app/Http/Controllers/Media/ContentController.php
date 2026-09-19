<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\ContentRequest;
use App\Models\Content;
use Illuminate\Support\Facades\Storage;
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
        $data['cover'] = $data['cover']?->store('contents', 'public');

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
        $content = $this->content->findOrFail($id, [
            'id', 'title', 'cover', 'description', 'body', 'type',
        ]);

        return Inertia::render('media/ContentEdit', compact('content'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContentRequest $request, string $id)
    {
        $data = $request->validated();
        $content = $this->content->findOrFail($id);

        if ($data['cover']) {
            $disk = Storage::disk('public');
            if ($content->cover && $disk->exists($content->cover)) {
                $disk->delete($content->cover);
            }

            $data['cover'] = $data['cover']->store('contents', 'public');
        }

        $content->update($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
