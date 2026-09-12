<?php

namespace App\Models;

use Database\Factories\ContentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'description', 'code', 'body', 'type', 'slug'])]
class Content extends Model
{
    /** @use HasFactory<ContentFactory> */
    use HasFactory;
}
