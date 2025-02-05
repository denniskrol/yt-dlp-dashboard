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
            'not_processed' => 'table-warning',
            'queued' => 'table-light',
            'getting_info' => 'table-info',
            'downloading' => 'table-primary',
            'error' => 'table-danger',
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
