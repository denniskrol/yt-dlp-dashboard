<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['statusClass', 'statusText'];

    protected $guarded = [];

    public function getStatusClassAttribute(): ?string
    {
        $classes = [
            'not_processed' => 'bg-orange-500',
            'queued' => 'bg-gray-400',
            'getting_info' => 'bg-cyan-800',
            'downloading' => 'bg-sky-800',
            'error' => 'bg-red-800',
        ];

        return $classes[$this->status] ?? null;
    }

    public function getStatusTextAttribute(): string
    {
        $replace = [
            'not_processed' => 'Not Processed',
            'queued' => 'Queued',
            'getting_info' => 'Getting Info...',
            'downloading' => 'Downloading...',
            'done' => 'Done',
            'error' => 'Error',
        ];

        return str_replace(array_keys($replace), array_values($replace), $this->status);
    }

    public function playlist(): BelongsTo
    {
        return $this->belongsTo(Playlist::class);
    }
}
