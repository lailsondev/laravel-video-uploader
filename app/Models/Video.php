<?php

namespace App\Models;

use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['title', 'code', 'description', 'thumbnail', 'video', 'is_processed', 'slug'])]
class Video extends Model
{
    use Sluggable;
    protected $slugColumnFrom = 'title';

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}
