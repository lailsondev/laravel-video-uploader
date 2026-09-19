<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Jobs\VideoEncodingJob;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class VideosController extends Controller
{
    public function index(Content $content)
    {
        return Inertia::render('media/videos/Upload', compact('content'));
    }

    public function store(Request $request, Content $content)
    {
        $video = $content->videos()->create([
            'code' => Str::uuid(),
            'title' => $request->name,
        ]);

        return response()->json(['id' => $video->id, 'name' => $video->title]);
    }

    public function update(Content $content, $video, Request $request)
    {
        $video = $content->videos()->findOrFail($video);

        $video->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->back();
    }

    public function destroy(Content $content, $video)
    {
        $video = $content->videos()->findOrFail($video);
        $video->delete();

        return redirect()->back();
    }

    public function processChunck(Content $content, $video, Request $request): \Illuminate\Http\JsonResponse
    {
        $videoModel = $content->videos()->findOrFail($video);

        $chunkData = $this->resolveChunkData($request);
        abort_if(empty($chunkData), 400, 'Nenhum arquivo enviado');

        [$start, $end, $total] = $this->parseContentRange($request->header('content-range'), $chunkData);
        $isLast = $end >= $total - 1;

        $disk = Storage::disk('videos');
        $dir = (string) $video;
        $tmpPath = "{$dir}/video.tmp";
        $finalPath = "{$dir}/video.mp4";

        if (! $disk->exists($dir)) {
            $disk->makeDirectory($dir);
        }

        $start === 0 ? $disk->put($tmpPath, $chunkData) : $disk->append($tmpPath, $chunkData);

        if ($isLast) {
            $disk->exists($finalPath) && $disk->delete($finalPath);
            $disk->move($tmpPath, $finalPath);
            $videoModel->update(['video' => $finalPath]);
            VideoEncodingJob::dispatch($videoModel);

            return response()->json(['done' => true, 'path' => $finalPath]);
        }

        $progress = $total > 0 ? (int) round(($end + 1) / $total * 100) : 0;

        return response()->json(['done' => $progress, 'progress' => $progress]);
    }

    private function resolveChunkData(Request $request): ?string
    {
        if ($request->hasFile('file')) {
            return file_get_contents($request->file('file')->getPathname()) ?: null;
        }

        $content = $request->getContent();
        if (! empty($content)) {
            return $content;
        }

        $raw = file_get_contents('php://input');
        return $raw !== false && $raw !== '' ? $raw : null;
    }

    private function parseContentRange(?string $header, string $chunkData): array
    {
        if ($header && preg_match('/bytes (\d+)-(\d+)\/(\d+)/', $header, $m)) {
            return [(int) $m[1], (int) $m[2], (int) $m[3]];
        }

        $len = strlen($chunkData);
        return [0, $len - 1, $len];
    }
}
