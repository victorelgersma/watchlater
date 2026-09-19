<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WatchItem extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'url',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A short, readable version of the URL for display — strips the
     * scheme and any trailing slash, same as Link::displayUrl().
     */
    public function displayUrl(): string
    {
        return preg_replace('#^https?://#', '', rtrim($this->url, '/'));
    }
}
