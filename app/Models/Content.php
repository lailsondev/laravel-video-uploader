<?php

namespace App\Models;

use App\Traits\Sluggable;
use Database\Factories\ContentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'description', 'code', 'body', 'type', 'slug', 'cover'])]
class Content extends Model
{
    /** @use HasFactory<ContentFactory> */
    use HasFactory, Sluggable;

    protected string $slugColumnFrom = 'title';
}
