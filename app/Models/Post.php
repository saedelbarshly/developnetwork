<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'body', 'cover_image' , 'pinned' ,'user_id'];

    protected static function booted()
    {
        static::created(function () {
            Cache::forget('states');
        });

        static::updated(function () {
            Cache::forget('states');
        });

        static::deleted(function () {
            Cache::forget('states');
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }
}
